<?php

namespace PhxCargo\Gnre;

use Illuminate\Support\ServiceProvider;

/**
 * Class GnreServiceProvider
 * @package PhxCargo\Gnre
 */
class GnreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $publishPath = $this->app['path.config'] . DIRECTORY_SEPARATOR . 'gnre.php';
        $this->publishes([
            $this->getConfigPath() => $publishPath,
        ]);
    }

    /**
     * @return string
     */
    protected function getConfigPath()
    {
        $root = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        $path = $root . 'config' . DIRECTORY_SEPARATOR . 'gnre.php';
        return $path;
    }
}