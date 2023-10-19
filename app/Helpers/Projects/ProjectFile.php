<?php

namespace App\Helpers\Projects;

use App\Models\Project;
use App\Plugins\Plugin;
use Illuminate\Support\Facades\Log;
use TorMorten\Eventy\Facades\Eventy;

class ProjectFile implements \JsonSerializable {

    protected ?string $full_file_path;
    protected ?string $public_file_path;
    protected ?string $file_extension;
    protected ?string $file_date;
    protected float $file_size;
    protected ?int $file_unix_timestamp;
    protected string $file_icon;
    protected string $file_name;
    protected bool $downloadable_from_this_server;

    /**
     * Derived classes that are handling resources not on the file system, pass in null for the first two params and the file extension for the third
     * @param string|null $full_file_path
     * @param string|null $parent_public_path
     * @param string|null $file_extension
     */
    public function __construct(?string $full_file_path,?string $parent_public_path,?string $file_extension = null )
    {
        $this->downloadable_from_this_server = true;
        if ($full_file_path) {
            $this->full_file_path = $full_file_path;
            $this->file_extension = pathinfo($this->full_file_path, PATHINFO_EXTENSION);
            $this->file_name = pathinfo($this->full_file_path, PATHINFO_FILENAME);
            $this->public_file_path = $parent_public_path . DIRECTORY_SEPARATOR . $this->file_name . '.' . $this->file_extension;
            $this->file_unix_timestamp = filemtime($full_file_path);
            $this->file_date = date('F j, Y', $this->file_unix_timestamp);
            $this->file_size = round(filesize($full_file_path) / 1048576, 2);
        } else {
            $this->full_file_path = null;
            $this->file_extension = '';
            $this->file_name = '';
            $this->public_file_path = null;
            $this->file_date = null;
            $this->file_unix_timestamp = null;
            $this->file_size = 0;
        }
        if ($file_extension) {
            $this->file_extension = $file_extension;
        }

        $this->file_icon = match ($this->file_extension) {
            "pdf" => "file-pdf.svg",
            "doc", "docx" => "file-word.svg",
            "xls", "xlsx" => "file-excel.svg",
            "ppt", "pptx" => "file-powerpoint.svg",
            "jpg", "jpeg", "png", "gif" => "file-image.svg",
            "txt" => "file.svg",
            default => "file-lines.svg",
        };
    }

    /**
     * @param Project $project
     * @return ProjectFile[]
     */
    public static function getFilesForProject(Project $project) : array  {
        Eventy::action(Plugin::ACTION_START_DISCOVERY_PROJECT_FILES, $project);
        $document_directory = $project->get_document_directory();
        if (!is_readable($document_directory)) {return [];}

        $initial_finds = [];
        $files = array_diff(scandir($document_directory), array('.', '..'));

        if (count($files) > 0) {
            foreach ($files as $file) {
                $file_path = $document_directory . DIRECTORY_SEPARATOR . $file;
                if (!file_exists($file_path)) {
                    throw new \LogicException("File does not exist $file_path");
                }
                $node = new ProjectFile($file_path,$project->get_document_directory());
                $maybe_changed = Eventy::filter(Plugin::FILTER_DISCOVER_PROJECT_FILE, $node);
                if (empty($maybe_changed)) { continue;}
                $initial_finds[] = $maybe_changed;
            }
        }
        $other_files = [];
        $other_files = Eventy::filter(Plugin::FILTER_APPEND_PROJECT_FILES, $other_files);
        $presorted = array_merge($other_files,$initial_finds);
        $ret =  Eventy::filter(Plugin::FILTER_SORT_PROJECT_FILES, $presorted);
        return $ret;
    }

    /**
     * @return string
     */
    public function getFullFilePath(): ?string
    {
        return $this->full_file_path;
    }


    public function getFileExtension(): ?string
    {
        return $this->file_extension;
    }


    public function getFileDate(): ?string
    {
        return $this->file_date;
    }


    public function getFileSize(): ?string
    {
        return $this->file_size;
    }

    /**
     * @return string
     */
    public function getFileIcon(): string
    {
        return $this->file_icon;
    }

    /**
     *  returns the file name (without extension)
     */
    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    /**
     * returns the full file name (not path) for showing in the display
     */
    public function getFileNameWithExtention(): ?string
    {
        if(!$this->getFileExtension()) {
            return $this->getFileName();
        }
        return $this->getFileName(). '.'. $this->getFileExtension();
    }

    /**
     * Gets the full url the file can be downloaded at
     */
    public function getPublicFilePath(): ?string
    {
        if (empty($this->public_file_path)) { return null;}
        return asset($this->public_file_path);
    }

    /**
     * if the file download site is not on this url, return false
     * @return bool
     */
    public function isDownloadableFromThisServer(): bool
    {
        return $this->downloadable_from_this_server;
    }


    public function toArray() : array  {
        $ret = [];
        foreach ($this as $name => $val) {
            $ret[$name] = $val;
        }
        return $ret;
    }
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function deleteProjectFile() {

        try {
            Eventy::action(Plugin::ACTION_BEFORE_DELETING_PROJECT_FILE, $this);
        } catch (\Exception $e) {
            Log::warning("Plugin actions threw an exception for ACTION_BEFORE_DELETING_PROJECT_FILE ". $e->getMessage());
        }
        if ($this->getFullFilePath() && is_readable($this->getFullFilePath())) {
            unlink($this->getFullFilePath());
            return true;
        }
        return false;
    }

    public function getFileUnixTimestamp(): ?int
    {
        return $this->file_unix_timestamp;
    }


}
