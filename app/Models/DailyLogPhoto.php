<?php /** @noinspection PhpRedundantOptionalArgumentInspection */

namespace App\Models;

use App\Plugins\Plugin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use TorMorten\Eventy\Facades\Eventy;

/**
 * @property int id
 * @property int daily_log_id
 * @property string user_type
 * @property string file_name
 * @property string thumbnail_name
 * @property string created_at
 * @property string updated_at
 *
 * @property DailyLog parent_log
 */
class DailyLogPhoto extends Model
{
    use HasFactory;

    protected $table = 'log_photos';

    public function parent_log() : BelongsTo {
        return $this->belongsTo('App\Models\DailyLog','daily_log_id','id');
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (DailyLogPhoto $log_photo) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_DAILY_LOG_PHOTO, $log_photo);
        });

        static::created(function (DailyLogPhoto $log_photo) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_DAILY_LOG_PHOTO, $log_photo);
        });

        static::updating(function (DailyLogPhoto $log_photo) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_DAILY_LOG_PHOTO, $log_photo);
        });

        static::updated(function (DailyLogPhoto $log_photo) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_DAILY_LOG_PHOTO, $log_photo);
        });


        static::deleting(function (DailyLogPhoto $log_photo) {
            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_DAILY_LOG_PHOTO, $log_photo);
        });
        static::deleted(function (DailyLogPhoto $log_photo) {
            $log_photo->cleanup_log_photo_resources();
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_DAILY_LOG_PHOTO, $log_photo);
        });
    }

    public function calculate_relative_directory(bool $thumbnail = false,?int $override_project_id = null)
    {
        if ($override_project_id) { $project_id = $override_project_id; }
        else {
            $project_id = $this->parent_log?->project_id??null;
        }
        $relative_root = Project::UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR . $project_id . DIRECTORY_SEPARATOR . Project::LOG_FOLDER;
        if ($thumbnail) {
            $relative_root .= DIRECTORY_SEPARATOR . static::THUMB_DIRECTORY;
        }
        return $relative_root ;
    }
    public function calculate_relative_path(bool $thumbnail = false,?int $override_project_id = null)
    {
        $relative_root = $this->calculate_relative_directory($thumbnail,$override_project_id);
        $name = $this->file_name;
        if ($thumbnail) {
            $name = $this->thumbnail_name;
        }
        return $relative_root . DIRECTORY_SEPARATOR . $name;

    }

    const THUMB_DIRECTORY = 'thumbnails';


    const MAX_REGULAR_FILE_SIZE_BYTES = 5000000;
    const ADJUSTED_WIDTH = 1024;
    const THUMBNAIL_WIDTH = 200;
    const THUMBNAIL_FILE_PREFIX = "thumbnail_";

    /**
     * @param UploadedFile $file
     * @param DailyLog $daily_log
     * @return DailyLogPhoto
     * @throws \Exception
     */
    public static function createDailyPhoto(UploadedFile $file, DailyLog $daily_log) : DailyLogPhoto
    {

        $full_path = '';
        $thumb_full_path = '';
        try {
            $node = new DailyLogPhoto();
            $node->daily_log_id = $daily_log->id;

            $is_image = false;
            if (str_starts_with($file->getMimeType(),'image/') ) { $is_image = true;}


            if ($is_image) {
                $node->file_name =  pathinfo($file->hashName(), PATHINFO_FILENAME) .'.jpg' ;
            } else {
                $node->file_name = $file->getClientOriginalName();
            }

            $file->storeAs($node->calculate_relative_directory(false,$daily_log->project_id), $node->file_name,['visibility' => 'public']);
            $full_path = storage_path('app/' . $node->calculate_relative_path(false,$daily_log->project_id));

            if(!is_readable($full_path)) {
                throw new \LogicException("Cannot read moved file at $full_path");
            }

            if ($is_image )
            {
                if ($file->getSize() > static::MAX_REGULAR_FILE_SIZE_BYTES) {

                    Image::make($full_path)->resize(static::ADJUSTED_WIDTH, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                        ->encode('jpg', 90)
                        ->save();

                } else {

                    Image::make($full_path)
                        ->encode('jpg', 90)
                        ->save();
                }

                // Create a thumbnail of the photo

                $thumb_directory = $node->calculate_relative_directory(true,$daily_log->project_id);
                $thumb_file_name = static::THUMBNAIL_FILE_PREFIX  . $node->file_name;
                $file->storeAs($thumb_directory, $thumb_file_name,['visibility' => 'public']);
                $node->thumbnail_name = $thumb_file_name;
                $thumb_full_path = storage_path('app/' . $node->calculate_relative_path(true,$daily_log->project_id));

                Image::make($thumb_full_path)->resize(static::THUMBNAIL_WIDTH, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save();
            } else {
                $node->thumbnail_name = '';
            }


            $node->save();
            return $node;
        }  catch (\Exception $what) {
            if($thumb_full_path) {unlink($thumb_full_path);}
            if($full_path) {unlink($full_path);}
            throw $what;
        }
    }

    public function cleanup_log_photo_resources() {
        if (!($this->file_name || $this->thumbnail_name )) {return;}
        $parent = $this->parent_log;
        if (empty($parent)) {
            $parent = $this->parent_log()->first();
        }
        if(!$parent) {return;}

        $full_file_relative = $this->calculate_relative_path(false,$parent->project_id);
        $full_file_absolute = storage_path('app/'.$full_file_relative);
        if (file_exists($full_file_absolute)) {
            unlink($full_file_absolute);
        }

        $thumb_file_relative = $this->calculate_relative_path(false,$parent->project_id);
        $thumb_file_absolute = storage_path('app/'.$thumb_file_relative);
        if (file_exists($thumb_file_absolute)) {
            unlink($thumb_file_absolute);
        }
    }
}
