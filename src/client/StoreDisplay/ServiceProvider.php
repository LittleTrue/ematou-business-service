<?php

namespace EPort\EPortWmsClient\StoreDisplay;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 */
class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['bbc_display'] = function ($app) {
            return new Client($app);
        };
    }
}
