<?php

namespace App\Models;

use App\Helpers\Projects\ProjectFile;
use App\Helpers\Utilities;
use App\Plugins\Plugin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use TorMorten\Eventy\Facades\Eventy;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @property int id
 * @property int contractor
 * @property int pm
 * @property string foreman
 * @property string project_name
 * @property string address
 * @property string city
 * @property string state
 * @property string zip
 * @property float latitude
 * @property float longitude
 * @property string budget
 * @property string start_date
 * @property string end_date
 * @property string super_name
 * @property string super_phone
 * @property string pm_name
 * @property string pm_phone
 * @property int status
 * @property string created_at
 * @property string updated_at
 *
 * @property Contractor project_contractor
 * @property Employee project_manager
 * @property Employee project_foreman
 * @property Invoice[] project_invoices
 * @property ConstructionExpense[] project_expenses
 * @property DailyLog[] project_logs
 * @property ProjectAssign[] project_assigns
 */
class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contractor',
        'pm',
        'project_name',
        'address',
        'city',
        'state',
        'zip',
        'latitude',
        'longitude',
        'budget',
        'start_date',
        'end_date',
        'super_name',
        'super_phone',
        'pm_name',
        'pm_phone',
        'status',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {

        static::saving(function(Project $project) {
            if (empty($project->latitude)) {$project->latitude = 0;}
            if (empty($project->longitude)) {$project->longitude = 0;}
        });

        static::saved(function(Project $project) {

            /**
             * @var ProjectAssign[] $assigns
             */
            $assigns = $project->project_assigns()->get();

            $employee_ids = [];
            foreach ($assigns as $assign) {
                $assign->status = $project->status;
                $assign->save();
                $employee_ids[] = $assign->employee_id;
            }

            $old_status  = $project->getOriginal('status');

            if ($old_status !== $project->status && $project->status === Project::STATUS_IN_PROGRESS && count($assigns))
            {
                Notification::createNotification($employee_ids,
                    "New Project Added!","You have been added to $project->project_name",
                    Notification::NOTIFICATION_TYPE_SELECT_EMPLOYEE );
            }
        });

        static::deleted(function (Project $project) {
            $project->cleanup_project_resources();
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_PROJECT, $project);
        });

        static::deleting(function (Project $project) {

            if (!$project->id) {return false;}
            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_PROJECT, $project);
            //remove invoices and daily logs, and assigned  here

            /**
             * @var Invoice[] $project_invoices
             */
            $project_invoices = $project->project_invoices()->get();
            foreach ( $project_invoices as $project_invoice) {
                $project_invoice->delete();
            }

            /**
             * @var DailyLog[] $project_invoices
             */
            $project_logs = $project->project_logs()->get();
            foreach ( $project_logs as $log) {
                $log->delete();
            }

            /**
             * @var ProjectAssign[] $project_assigns
             */
            $project_assigns = $project->project_assigns()->get();
            foreach ( $project_assigns as $an_assign) {
                $an_assign->delete();
            }

            /**
             * @var ConstructionExpense[] $construction_expenses
             */
            $construction_expenses = $project->project_expenses()->get();
            foreach ( $construction_expenses as $an_expense) {
                $an_expense->delete();
            }

            return true; //allow deletion
        }
        );

        static::creating(function (Project $project) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_PROJECT, $project);
        });

        static::created(function (Project $project) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_PROJECT, $project);
        });

        static::updating(function (Project $project) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_PROJECT, $project);
        });

        static::updated(function (Project $project) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_PROJECT, $project);
        });
    }

    const STATUS_NOT_STARTED = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;

    public function project_contractor() : BelongsTo {
        return $this->belongsTo('App\Models\Contractor','contractor');
    }

    public function project_manager() : BelongsTo {
        return $this->belongsTo('App\Models\Employee','pm');
    }

    public function project_expenses() : HasMany {
        return $this->hasMany('App\Models\ConstructionExpense');
    }

    public function project_logs() : HasMany {
        return $this->hasMany('App\Models\DailyLog')
            /** @uses DailyLog::daily_log_files() */
            ->with('daily_log_files');
    }

    public function project_assigns() : HasMany {
        return $this->hasMany('App\Models\ProjectAssign')
            /**
             * @uses ProjectAssign::assigned_employee()
             */
            ->with('assigned_employee')
            ;
    }

    public function project_invoices() : HasMany {
        return $this->hasMany('App\Models\Invoice');
    }


    public function setForemen(array $employee_ids) {
        $employee_ids = Utilities::to_int_array($employee_ids);
        $new_foremen_employee_ids = [];

        $akey = 'a-';
        /**
         * @var array<string,ProjectAssign> $assigned_hash
         */
        $assigned_hash = [];
        foreach ($this->getProjectAssigns() as $assigned) {
            $assigned_hash[$akey.$assigned->employee_id] = $assigned;
        }

        //remove all the ones that are the same to eventually make the the delete list, mark the ones to be added
        foreach ($employee_ids as $given_employee_id) {
            if (isset($assigned_hash[$akey.$given_employee_id])) {
                unset($assigned_hash[$akey.$given_employee_id]);
            } else {
                $new_foremen_employee_ids[] = $given_employee_id;
            }
        }

        //remove the now old ones no longer used
        foreach ($assigned_hash as $old_assigned ) {
            $old_assigned->delete();
        }

        foreach ($new_foremen_employee_ids as $new_foreman_id) {
            $node = new ProjectAssign();
            $node->project_id = $this->id;
            $node->status = $this->status;
            $node->employee_id = $new_foreman_id;
            $node->save();
        }

        if (count($employee_ids)) {
            $this->foreman = implode(',',$employee_ids);
        } else {
            $this->foreman = null;
        }
    }


    public function humanStatus() {
        if($this->status === static::STATUS_IN_PROGRESS) {return 'In Progress';}
        else if($this->status === static::STATUS_COMPLETED) {return 'Completed';}
        else {return 'Not Started';}
    }

    const UPLOAD_DIRECTORY = 'uploads/projects';
    const DOCUMENTS_FOLDER = 'documents';
    const INVOICES_FOLDER = 'invoices';
    const LOG_FOLDER = 'daily-logs';

    /**
     * @param UploadedFile $file
     * @return string
     * @throws
     */
    public function process_uploaded_file(UploadedFile $file ) : string {
        $full_path = null;
        if (!$this->id) { throw new \LogicException("Trying to save a file to an unsaved project");}
        try {
            $file->storeAs($this->get_document_directory(), $file->getClientOriginalName(),['visibility' => 'public']);
            $relative_path = $this->get_document_directory() . DIRECTORY_SEPARATOR . $file->getClientOriginalName();
            $full_path = storage_path('app/'.$relative_path);
            try {
                Eventy::action(Plugin::ACTION_ON_UPLOADED_PROJECT_FILE, $full_path);
            } catch (\Exception $e) {
                Log::warning("Plugin actions threw an exception on ACTION_ON_UPLOADED_PROJECT_FILE ". $e->getMessage());
            }
            return $full_path;

        } catch (\Exception $what) {
            if($full_path) {unlink($full_path);}
            throw $what;
        }
    }



    public function get_document_directory() : string  {
        if (!$this->id) { throw new \LogicException("Trying get project directory with no project_id");}
        return static::UPLOAD_DIRECTORY. DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR . static::DOCUMENTS_FOLDER;
    }


    /**
     * @return ProjectFile[]
     */
    public function get_project_files() {
        return ProjectFile::getFilesForProject($this);
    }

    public function delete_project_file(string $file_name) {
        foreach ($this->get_project_files() as $project_file) {
            if ($file_name === $project_file->getFileNameWithExtention()) {
                $project_file->deleteProjectFile();
                return true;
            }
        }
        return false;
    }


    public function chartTotalsProjectTransactions(bool $b_include_label, bool $b_include_type) : array {


        $query = function() : Builder {
            $query = ConstructionExpense::select('transaction_type',DB::raw('sum(amount) as total'))
                ->where('project_id',$this->id)
                ->groupBy('transaction_type');
            return Eventy::filter(Plugin::FILTER_QUERY_EXPENSE_SUMS, $query,$this);
        };
        $sums =  $query()->pluck('total','transaction_type');

        $ret = [];
        foreach ($sums as $total => $transaction_type) {
            $formatted_total = number_format(floatval($total), 2, '.', '');
            $formatted_total_type = '"'. $transaction_type.'"';
            if ($b_include_label && $b_include_type) {
                $ret[$transaction_type] = $formatted_total;
            } elseif ($b_include_label) {
                $ret[] = $formatted_total_type;
            }
            else {
                $ret[] = $formatted_total;
            }
        }
        return $ret;
    }

    public function getProjectAssigns() {
        $project_assigns = $this->project_assigns();
        $query =  Eventy::filter(Plugin::FILTER_QUERY_PROJECT_ASSIGNS, $project_assigns,$this);
        return $query->get();
    }

    /**
     * @return Employee[]|array|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getForemen() {


        $assigned = $this->project_assigns;
        if (count($assigned) === 0) {
            $assigned = $this->getProjectAssigns();
        }
        $employee_ids = [];
        foreach ($assigned as $a) {
            $employee_ids[] = $a->employee_id;
        }
        if (empty($employee_ids)) {$employee_ids[] = -1;}

        $query = function() use ($employee_ids) : Builder {
            $query = Employee::whereIn('id',$employee_ids);
            return Eventy::filter(Plugin::FILTER_QUERY_PROJECT_FOREMEN, $query,$this);
        };
        return $query()->get();
    }


    public function cleanup_project_resources() {
        foreach ($this->get_project_files() as $what_file) {
            $what_file->deleteProjectFile();
        }
    }

    public static function salesYTD() : float {
        return (float)DB::select("SELECT SUM(budget) AS total_budget FROM projects")[0]?->total_budget??0;
    }

    /**
     * @return Project[]
     */
    public static function getProjectsStartingThisMonth() {

        $query = function() : Builder {
            $currentMonth = date('m');
            $query = Project::whereRaw("MONTH(start_date) = $currentMonth")
                /**  @uses Project::project_contractor()*/
                ->with('project_contractor')
                ->orderBy('id');
            return Eventy::filter(Plugin::FILTER_QUERY_PROJECTS_STARTING_THIS_MONTH, $query);
        };

        /**
         * @var Project[] $projects
         */
        $projects = $query()->get();
        return $projects;

    }

    /**
     * @return Project[]
     */
    public static function getAllProjects() {
        $query = function() : Builder {
            $query = Project::where('id','>',0)
                /**
                 * @uses Project::project_contractor()
                 */
                ->with('project_contractor')
                ->orderBy('project_name');
            return Eventy::filter(Plugin::FILTER_QUERY_PROJECTS, $query);
        };

        return $query()->get();
    }

    /**
     * @return Project[]
     */
    public static function getAllActiveProjects() {
        $query = function() : Builder {
            $query = Project::where('status','<>',Project::STATUS_COMPLETED)
                /**  @uses Project::project_contractor()*/
                ->with('project_contractor')

                /**  @uses Project::project_assigns()*/
                ->with('project_assigns')

                ->orderBy('project_name');
            return Eventy::filter(Plugin::FILTER_QUERY_ACTIVE_PROJECTS, $query);
        };

        return $query()->get();
    }


}
