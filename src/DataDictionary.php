<?php

namespace Encore\Admin\DataDictionary;

use Encore\Admin\Extension;

class DataDictionary extends Extension
{
    public $name = 'data-dictionary';

    public $views = __DIR__.'/../resources/views';

    public $menu = [
        'title' => 'Data dictionary',
        'path'  => 'data-dictionary',
        'icon'  => 'fa-database',
    ];
}