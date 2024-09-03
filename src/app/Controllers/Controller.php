<?php

namespace App\Controllers;

use App\Models\DirectoryModel;
use App\Models\FileModel;

class Controller
{
    protected DirectoryModel $directoryModel;
    protected FileModel $fileModel;

    public function __construct()
    {
        $this->directoryModel = new DirectoryModel();
        $this->fileModel = new FileModel();
    }
    public function index()
    {    
        $directories = $this->directoryModel->getDirectories();
        $files = $this->fileModel->getFiles();
        $treeHtml = $this->buildTreeHtml($directories, $files);

        require __DIR__ . '/../Views/index.php';
    }

    protected function buildTreeHtml($directories, $files, $parentId = null)
    {
        $html = '';

        foreach ($directories as $directory) {
            if ($directory['parent_id'] == $parentId) {
                $html .= '<div class="directory__item">';
                $html .= '<p class="directory__folder" data-id="' . $directory['id'] . '">
                            <img src="../public/images/Folder.jpg" alt="Folder"> ' . $directory['name'] . '
                          </p>';

                foreach ($files as $file) {
                    if ($file['directory_id'] == $directory['id']) {
                        $html .= '<p class="directory__file" data-id="' . $file['id'] . '">
                                    <img src="../public/images/File.png" alt="File"> ' . $file['filename'] . '
                                  </p>';
                    }
                }

                $html .= $this->buildTreeHtml($directories, $files, $directory['id']);
                $html .= '</div>';
            }
        }

        return $html;
    }

    public function createDirectory()
    {
        $parentId = $_POST['parentId'] ?? null;
        $name = $_POST['name'] ?? '';
    
        if (!empty($name)) {
            $this->directoryModel->createDirectory($name, $parentId);
        }
    
        header('Location: /');
    }
    
}
