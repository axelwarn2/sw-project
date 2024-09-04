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

    protected function buildTreeHtml($directories, $files, $parentId = null, $parentPath = '')
    {
        $html = '';

        foreach ($directories as $directory) {
            if ($directory['parent_id'] == $parentId) {
                $currentPath = $parentPath . $directory['name'] . '/';
                $html .= '<div class="directory__item">';
                $html .= '<p class="directory__folder" data-id="' . $directory['id'] . '" data-path="' . $currentPath . '">
                            <img src="../public/images/Folder.jpg" alt="Folder"> ' . $directory['name'] . '
                          </p>';

                foreach ($files as $file) {
                    if ($file['directory_id'] == $directory['id']) {
                        $html .= '<p class="directory__file" data-id="' . $file['id'] . '">
                                    <img src="../public/images/File.png" alt="File"> ' . $file['filename'] . '
                                  </p>';
                    }
                }

                $html .= $this->buildTreeHtml($directories, $files, $directory['id'], $currentPath);
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
    
    public function createFile()
    {
        if (isset($_FILES['file'])) {
            $directoryId = $_POST['directoryId'] ?? null;
            $filename = $_FILES['file']['name'];

            if (!empty($filename) && $directoryId) {
                $this->fileModel->createFile($filename, $directoryId);
            }
        }
        header('Location: /');
        exit;
    }    

    public function delete()
    {
        $itemId = $_POST['itemId'] ?? null;
        $itemType = $_POST['itemType'] ?? '';

        if ($itemType === 'file') {
            $this->fileModel->deleteFile($itemId);
        } elseif ($itemType === 'directory') {
            $this->directoryModel->deleteDirectory($itemId);
        }

        header('Location: /');
    }
}
