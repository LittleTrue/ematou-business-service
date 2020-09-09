<?php

namespace EPort\EPortWmsClient\OrderSubmit;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 */
class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['bbc_order'] = function ($app) {
            return new Client($app);
        };
    }
}
