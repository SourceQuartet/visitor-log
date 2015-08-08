<?php namespace SourceQuartet\VisitorLog;

use Illuminate\Support\Facades\Facade;

class VisitorLogFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'visitor';
    }
}
