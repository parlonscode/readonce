<?php

namespace App;

use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function boot()
    {
        parent::boot();
        // https://github.com/symfony/symfony/issues/35575
        if ($_SERVER['APP_DEBUG']) {
            $showDeprecations = $_ENV['APP_DEPRECATIONS'] ?? $_SERVER['APP_DEPRECATIONS'] ?? false;
            $showDeprecations = filter_var($showDeprecations, FILTER_VALIDATE_BOOLEAN);

            if (!$showDeprecations) {
                ErrorHandler::register(null, false)->setLoggers([
                    \E_DEPRECATED => [null],
                    \E_USER_DEPRECATED => [null],
                ]);
            }
        }
    }
}
