<?php
namespace App\PayPalService;

use Silex\ServiceProviderInterface;
use Silex\Application;

/**
 * Class PayPalServiceProvider
 * @package App\PayPalService
 */
class PayPalServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['payPalApiContext'] = $app->share(function () use ($app) {
            return new PayPalApiContext();
        });

        $app['payPalHandler'] = $app->share(function () use ($app) {
            return new PayPalHandler();
        });
    }

    public function boot(Application $app)
    {
    }
}