<?php


namespace ajohnson6494\AdobeSign\Laravel;


use Illuminate\Support\ServiceProvider;
use ajohnson6494\AdobeSign\AdobeSign;

class AdobeSignLaravelServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $config = $this->app['path.config'] . '/adobe-sign.php';

        $this->publishes([
            __DIR__ . '/config/config.php' => $config
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'adobe-sign');

        $this->app->bind('adobe-sign-laravel', $this->createAdobeSignLaravelClosure());
    }

    /**
     * @return \Closure
     */
    protected function createAdobeSignLaravelClosure()
    {
        return function ($app) {
            $provider = new \ajohnson6494\AdobeSign\AdobeSignProvider($app['config']['adobe-sign']);

            return new AdobeSign($provider);
        };
    }
}