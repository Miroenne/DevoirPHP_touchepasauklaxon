<?php

namespace App\Controllers;

class ViewController
{

    private const VIEW_PATH = __DIR__ . '/../Views/';

    private function render(string $view, array $data = []): string
    {
        $__file = self::VIEW_PATH . $view . '.php';

        if (!is_file($__file)) {
            throw new \RuntimeException("View was not found : $view");
        }

        ob_start();
        try {
            (static function (string $__file, array $__data): void {
                extract($__data, EXTR_SKIP);
                require $__file;
            })($__file, $data);
            return ob_get_contents();
        } finally {
            ob_end_clean();
        }
    }



    public function dashboard(): string
    {
        return $this->render('dashboard');
    }
}
