<?php

namespace App\Views;

class RenderDirectoryTree
{
    public function render($directories, $files)
    {
        return $this->buildTreeHtml($directories, $files);
    }

    protected function buildTreeHtml($directories, $files, $parentId = null)
    {
        $html = '';

        foreach ($directories as $directory) {
            if ($directory['parent_id'] == $parentId) {
                $html .= '<div class="directory__item">';
                $html .= '<p class="directory__folder" data-id="' . htmlspecialchars($directory['id']) . '" data-path="' . htmlspecialchars($directory['path']) . '/' .'">
                            <img src="../public/images/Folder.jpg" alt="Folder"> ' . htmlspecialchars($directory['name']) . '
                          </p>';

                foreach ($files as $file) {
                    if ($file['directory_id'] == $directory['id']) {
                        $html .= '<p class="directory__file" data-id="' . htmlspecialchars($file['id']) . '" data-path="' . htmlspecialchars($directory['path'] . '/' . $file['filename']) . '">
                                    <img src="../public/images/File.png" alt="File"> ' . htmlspecialchars($file['filename']) . '
                                  </p>';
                    }
                }

                $html .= $this->buildTreeHtml($directories, $files, $directory['id']);
                $html .= '</div>';
            }
        }

        return $html;
    }
}
