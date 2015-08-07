<?php namespace SourceQuartet\VisitorLog;

use Illuminate\Database\Eloquent\Model;

class VisitorModel extends Model {
	protected $table = 'visitors';
	protected $primaryKey = 'sid';
	public $incrementing = false;
	public $fillable = ['sid', 'ip', 'page', 'useragent', 'user', 'created_at', 'updated_at'];
	
	public $agents = [];
}