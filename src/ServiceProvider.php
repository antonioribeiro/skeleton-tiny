<?php 
 
/**
 * Part of the Skeleton package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Skeleton
 * @version    1.0.0
 * @author     Antonio Carlos Ribeiro @ PragmaRX
 * @license    BSD License (3-clause)
 * @copyright  (c) 2013, PragmaRX
 * @link       http://pragmarx.com
 */

namespace PragmaRX\Skeleton;
 
use Illuminate\Skeleton\ServiceProvider as IlluminateServiceProvider;

use Illuminate\Foundation\AliasLoader as IlluminateAliasLoader;

abstract class ServiceProvider extends IlluminateServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    abstract protected function getRootDirectory();

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package($this->packageNamespace, $this->packageNamespace, $this->getRootDirectory());

        if( $this->app['config']->get($this->packageNamespace.'::create_'.$this->packageName.'_alias') )
        {
            IlluminateAliasLoader::getInstance()->alias(
                                                            $this->getConfig($this->packageName.'_alias'),
                                                            'PragmaRX\\'.$this->packageNameCapitalized.'\Vendor\Laravel\Facade'
                                                        );
        }

        $this->wakeUp();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function preRegister()
    {   
        $this->registerConfig();
    }

    private function wakeUp()
    {
        $this->app['helpers']->boot();
    }

    public function registerConfig()
    {
        $this->app[$this->packageName.'.config'] = $this->app->share(function($app)
        {
            return new Config($app['config'], $this->packageNamespace);
        });
    }

    private function getConfig($key)
    {
        return $this->app['config']->get($this->packageNamespace.'::'.$key);
    }
}
