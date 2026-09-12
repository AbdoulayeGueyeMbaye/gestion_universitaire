<?php

declare(strict_types=1);

namespace App\View;

use InvalidArgumentException;

final class ViewRenderer
{
    public function __construct(private string $templatesPath)
    {
    }

    public function render(string $template, array $data = []): string
    {
        $templatePath = $this->templatesPath . '/' . $template . '.php';

        if (!is_file($templatePath)) {
            throw new InvalidArgumentException("Template introuvable : {$template}");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $templatePath;
        $content = (string) ob_get_clean();
        $title = $data['title'] ?? 'Gestion des salles';

        ob_start();
        require $this->templatesPath . '/layout/base.php';

        return (string) ob_get_clean();
    }
}
