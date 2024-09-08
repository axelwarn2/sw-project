<?php

namespace App\Controllers;

use App\Models\DirectoryModel;
use App\Models\FileModel;
use App\Views\RenderDirectoryTree;

class ViewController
{
    private DirectoryModel $directoryModel;
    private FileModel $fileModel;

    public function __construct(DirectoryModel $directoryModel, FileModel $fileModel)
    {
        $this->directoryModel = $directoryModel;
        $this->fileModel = $fileModel;
    }

    public function index()
    {   
        $directories = $this->directoryModel->getDirectories();
        $files = $this->fileModel->getFiles();

        $render = new RenderDirectoryTree();
        $directoryTree  = $render->render($directories, $files);
        
        return [
            "directoryTree" => $directoryTree,
        ];
    }
}
