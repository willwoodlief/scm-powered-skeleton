<?php

namespace App\Models;

use App\Plugins\Plugin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use TorMorten\Eventy\Facades\Eventy;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @property int id
 * @property int project_id
 * @property int number
 * @property int amount
 * @property int payment
 * @property int status
 * @property string date
 * @property string file
 * @property string created_at
 * @property string updated_at
 *
 * @property Project invoice_project
 */
class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'number',
        'amount',
        'status',
        'date'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {

        static::creating(function (Invoice $invoice) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_INVOICE, $invoice);
        });

        static::created(function (Invoice $invoice) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_INVOICE, $invoice);
        });

        static::updating(function (Invoice $invoice) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_INVOICE, $invoice);
        });

        static::updated(function (Invoice $invoice) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_INVOICE, $invoice);
        });

        static::deleted(function (Invoice $invoice) {
            $invoice->delete_existing_invoice_file();
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_INVOICE, $invoice);
        });

        static::deleting(function (Invoice $invoice) {
            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_INVOICE, $invoice);
        });
    }

    const STATUS_OPEN = 0;
    const STATUS_PAID = 1;


    public function invoice_project() : BelongsTo {
        return $this->belongsTo('App\Models\Project','project_id');
    }



    public function get_invoice_directory() : string  {
        if (!$this->project_id) {throw new \LogicException("Invoice does not have project set");}
        return  Project::UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR . $this->project_id . DIRECTORY_SEPARATOR . Project::INVOICES_FOLDER;
    }

    public function delete_existing_invoice_file() {
        if (!$this->file) {return;}
        $root = $this->get_invoice_directory();
        $relative_file = $root . DIRECTORY_SEPARATOR . $this->file;
        $full_path = storage_path('app/'.$relative_file);
        if (file_exists($full_path)) {
            unlink($full_path);
        }
    }


    public function calculate_relative_path()
    {
        $relative_root = $this->get_invoice_directory();
        return $relative_root . DIRECTORY_SEPARATOR . $this->file;
    }

    /**
     * @param UploadedFile $file
     * @throws \Exception
     */
    public  function process_new_file(UploadedFile $file) : void
    {
        if (!$this->project_id) {
            throw new \LogicException("Need the invoice project set before saving the file");
        }
        $this->delete_existing_invoice_file();
        $full_path = '';
        try {

            $is_image = false;
            if (str_starts_with($file->getMimeType(),'image/') ) { $is_image = true;}

            if ($is_image) {
                $this->file =  $file->hashName() ;
            } else {
                $this->file = $file->getClientOriginalName();
            }

            $file->storeAs($this->get_invoice_directory(), $this->file,['visibility' => 'public']);
            $full_path = storage_path('app/' . $this->get_invoice_directory().DIRECTORY_SEPARATOR . $this->file);

            if(!is_readable($full_path)) {
                throw new \LogicException("Cannot read new invoice file at $full_path");
            }


            $this->save();

        }  catch (\Exception $what) {
            if($full_path) {unlink($full_path);}
            throw $what;
        }
    }

    public static function totalBalance() : float {
        return (float)DB::select("SELECT SUM(payment) AS total_balance FROM invoices")[0]?->total_balance??0;
    }

    /**
     * @return Invoice[]
     */
    public static function getAllInvoices() {
        $query = function() : Builder {
            $query = Invoice::where('id','>',0)

                /**
                 * @uses Invoice::invoice_project()
                 */
                ->with('invoice_project');
            return Eventy::filter(Plugin::FILTER_QUERY_INVOICES, $query);
        };

        return $query()->get();
    }
}
