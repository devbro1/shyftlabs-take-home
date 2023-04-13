<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Auth\RequestGuard;

RequestGuard::macro('logout', function () {
    $this->user = null;
});

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
