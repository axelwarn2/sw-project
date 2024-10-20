<?php

namespace App\Views;

class RenderDirectoryTree
{
    public function render(array $directories, array $files): string
    {
        return $this->buildTreeHtml($directories, $files, null);
    }

    protected function buildTreeHtml(array $directories, array $files, ?int $parentId): string
    {
        $html = '';

        foreach ($directories as $directory) {
            if ($directory['parent_id'] == $parentId) {
                $html .= '<div class="directory__item">';
                $html .= $this->renderDirectory($directory);

                foreach ($files as $file) {
                    if ($file['directory_id'] == $directory['id']) {
                        $html .= $this->renderFile($file, $directory);
                    }
                }
                
                $html .= $this->buildTreeHtml($directories, $files, $directory['id']);
                $html .= '</div>';
            }
        }

        return $html;
    }

    protected function renderDirectory(array $directory): string
    {
        ob_start();
        include __DIR__ . '/Template/directoryTemplate.php';
        return ob_get_clean();
    }

    protected function renderFile(array $file, array $directory): string
    {
        ob_start();
        include __DIR__ . '/Template/fileTemplate.php';
        return ob_get_clean();
    }
}
