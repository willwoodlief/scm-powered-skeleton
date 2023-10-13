<?php

namespace App\Helpers\Projects;

use App\Models\Project;

class ProjectFile {

    private string $full_file_path;
    private string $public_file_path;
    private string $file_extension;
    private string $file_date;
    private string $file_size;
    private string $file_icon;
    private string $file_name;

    public function __construct(string $full_file_path,string $parent_public_path)
    {
        $this->full_file_path = $full_file_path;
        $this->file_extension = pathinfo($this->full_file_path, PATHINFO_EXTENSION);
        $this->file_name = pathinfo($this->full_file_path, PATHINFO_FILENAME);
        $this->public_file_path = $parent_public_path . DIRECTORY_SEPARATOR . $this->file_name . '.'. $this->file_extension;
        $this->file_date = date('F j, Y', filemtime($full_file_path));
        $this->file_size = round(filesize($full_file_path) / 1048576, 2);

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
        $document_directory = $project->get_document_directory();
        if (!is_readable($document_directory)) {return [];}

        $ret = [];
        $files = array_diff(scandir($document_directory), array('.', '..'));

        if (count($files) > 0) {
            foreach ($files as $file) {
                $file_path = $document_directory . DIRECTORY_SEPARATOR . $file;
                if (!file_exists($file_path)) {
                    throw new \LogicException("File does not exist $file_path");
                }
                $ret[] = new ProjectFile($file_path,$project->get_document_directory());
            }
        }
        return $ret;
    }

    /**
     * @return string
     */
    public function getFullFilePath(): string
    {
        return $this->full_file_path;
    }

    /**
     * @return string
     */
    public function getFileExtension(): string
    {
        return $this->file_extension;
    }

    /**
     * @return string
     */
    public function getFileDate(): string
    {
        return $this->file_date;
    }

    /**
     * @return string
     */
    public function getFileSize(): string
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
     * @return string
     */
    public function getFileName(): string
    {
        return $this->file_name;
    }

    public function getFileNameWithExtention(): string
    {
        if(!$this->getFileExtension()) {
            return $this->getFileName();
        }
        return $this->getFileName(). '.'. $this->getFileExtension();
    }

    /**
     * @return string
     */
    public function getPublicFilePath(): string
    {
        return $this->public_file_path;
    }


}
