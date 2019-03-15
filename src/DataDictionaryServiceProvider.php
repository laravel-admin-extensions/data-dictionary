<?php

namespace Encore\Admin\DataDictionary;

use Illuminate\Support\ServiceProvider;

class DataDictionaryServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(DataDictionary $extension)
    {
        if (! DataDictionary::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'data-dictionary');
        }

        $this->app->booted(function () {
            DataDictionary::routes(__DIR__.'/../routes/web.php');
        });
    }
}