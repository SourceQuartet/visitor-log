<?php namespace SourceQuartet\VisitorLog;
use Illuminate\Support\Facades\Facade;

class Visitor extends Facade {

    protected static function getFacadeAccessor() { return 'visitor'; }

}