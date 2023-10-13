<?php

namespace App\Models;

use App\Plugins\Plugin;
use App\Providers\ScmServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use TorMorten\Eventy\Facades\Eventy;


/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @property int id
 * @property string name
 * @property string address
 * @property string city
 * @property string state
 * @property int zip
 * @property string phone
 * @property string logo
 * @property string created_at
 * @property string updated_at
 */
class Contractor extends Model
{
    use HasFactory;

    protected $table = 'contractors';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'zip',
        'phone',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Contractor $contractor) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_CONTRACTOR, $contractor);
        });

        static::created(function (Contractor $contractor) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_CONTRACTOR, $contractor);
        });

        static::updating(function (Contractor $contractor) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_CONTRACTOR, $contractor);
        });

        static::updated(function (Contractor $contractor) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_CONTRACTOR, $contractor);
        });


        static::deleting(function (Contractor $contractor) {
            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_CONTRACTOR, $contractor);
        });

        static::deleted(function (Contractor $contractor) {
            $contractor->cleanup_contractor_resources();
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_CONTRACTOR, $contractor);
        });
    }

    const UPLOAD_DIRECTORY = 'uploads/contractors';
    const UPLOAD_THUMBNAIL_DIRECTORY = 'uploads/contractors/thumbnails';

    const THUMBNAIL_WIDTH = 200;

    function countOpenProjectsByContractor() : int {
        return Project::where('contractor',$this->id)->where('status',Project::STATUS_IN_PROGRESS)->count();
    }

    function countOpenInvoicesByContractor() : int {
        $that = $this;
        return Invoice::where('invoices.status',Invoice::STATUS_OPEN)
            ->join('projects as owning_project',
                /**
                 * @param JoinClause $join
                 */
                function ($join) use($that) {
                            $join
                                ->on('owning_project.id','=','invoices.project_id')
                                ->where('owning_project.contractor','=',$that->id);
                            }
                        )

            ->count();
    }

    function countAllProjectsByContractor() : int {
        return Project::where('contractor',$this->id)->count();
    }

    public function cleanup_contractor_resources() :void {
        if (!$this->logo) { return ;}

        if (file_exists($this->get_image_full_path())) {
            unlink($this->get_image_full_path());
        }
        if (file_exists($this->get_image_full_path(true))) {
            unlink($this->get_image_full_path(true));
        }
    }

    public function get_image_asset_path(bool $thumbnail = false) {
        if (!$this->image_exists($thumbnail)) {
            return ScmServiceProvider::getContactorDefaultImage();
        }
        else {
            $what = $this->get_image_relative_path($thumbnail);
        }
        return asset($what);
    }


    public function get_image_relative_path(bool $thumbnail = false) : ?string {
        if (!$this->logo) {
            return null;
        }
        $partial = static::UPLOAD_DIRECTORY;
        if ($thumbnail) {
            $partial = static::UPLOAD_THUMBNAIL_DIRECTORY;
        }
        return $partial.'/'.$this->logo;
    }

    public function get_image_full_path(bool $thumbnail = false) : ?string  {
        $partial = $this->get_image_relative_path($thumbnail);
        if (!$partial) {return null;}
        return storage_path('app/'.$partial);
    }

    public function image_exists(bool $thumbnail = false) : bool {
        $path = $this->get_image_full_path($thumbnail);
        if (!$path) {return false;}
        return file_exists($path);
    }

    /**
     * @param UploadedFile $file
     * @return void
     * @throws
     */
    public function process_uploaded_image(UploadedFile $file ) : void {
        $full_path = null;
        $thumbnail_full_path = null;
        try {

            $relative_path = $file->storePublicly(Contractor::UPLOAD_DIRECTORY);
            $full_path = storage_path('app/'.$relative_path);
            $thumbnail_relative_path = $file->storePublicly(Contractor::UPLOAD_THUMBNAIL_DIRECTORY);
            $thumbnail_full_path = storage_path('app/'.$thumbnail_relative_path);
            $image_name = $file->hashName();
            Image::make($thumbnail_full_path)->resize(Contractor::THUMBNAIL_WIDTH, null,function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save();
            if ($this->logo) {
                if (file_exists($this->get_image_full_path())) {
                    unlink($this->get_image_full_path());
                }
                if (file_exists($this->get_image_full_path(true))) {
                    unlink($this->get_image_full_path(true));
                }

            }
            $this->logo = $image_name;



        } catch (\Exception $what) {
            if($thumbnail_full_path) {unlink($thumbnail_full_path);}
            if($full_path) {unlink($full_path);}
            throw $what;
        }
    }

    public function getName() : string  {
        return $this->name ;
    }

    /**
     * @return Contractor[]
     */
    public static function getAllContractors() {
        $query = function() : Builder {
            $query = Contractor::where('id','>',0);
            return Eventy::filter(Plugin::FILTER_QUERY_CONTRACTORS, $query);
        };

        return $query()->get();
    }
}
