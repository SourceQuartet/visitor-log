<?php namespace SourceQuartet\VisitorLog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class VisitorModel extends Model
{
    protected $table = 'visitors';
    protected $primaryKey = 'sid';
    public $incrementing = false;
    public $fillable = ['sid', 'ip', 'page', 'useragent', 'user', 'created_at', 'updated_at'];
    
    public $agents = [];

    public function setSidAttribute($value)
    {
        $this->attributes['sid'] = $value;
        Session::put('visitor_log_sid', $value);
    }
}
