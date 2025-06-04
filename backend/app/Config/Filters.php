<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public $aliases = [
        'cors' => \App\Filters\CORS::class,
    ];

    public $globals = [
        'before' => [
            'cors',
        ],
        'after'  => [
        ],
    ];
}
