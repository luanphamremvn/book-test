<?php

namespace App\Services;
use App\Traits\LogTrait;

class BaseService
{
    use LogTrait;
    /**
     * log prefix
     *
     * @var string
     */
    protected string $logPrefix = 'Service';
}
