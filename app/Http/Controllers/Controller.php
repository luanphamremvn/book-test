<?php

namespace App\Http\Controllers;

use App\Traits\LogTrait;
use App\Traits\ResponseTrait;

abstract class Controller
{
    use ResponseTrait, LogTrait;

    /**
     * log prefix
     *
     * @var string
     */
    protected string $logPrefix = 'Controller';
}
