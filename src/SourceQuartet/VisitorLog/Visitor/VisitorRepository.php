<?php namespace SourceQuartet\VisitorLog\Visitor;
use Carbon\Carbon;
use SourceQuartet\Exception\InvalidArgumentException;
use SourceQuartet\VisitorLog\Useragent;
use SourceQuartet\VisitorLog\VisitorModel;
use \SourceQuartet\VisitorLog\Contracts\Visitor\VisitorContract;

/**
 * Class VisitorRepository
 * @package SourceQuartet\VisitorLog\Repositories\Visitor
 */
class VisitorRepository implements VisitorContract
{
    /**
     * @var Visitor
     */
    private $model;

    /**
     * @param VisitorModel $visitorModel
     */
    public function __construct(VisitorModel $visitorModel)
    {
        $this->model = $visitorModel;
    }

    /**
     * @param array $attributes
     * @return static
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @param array $attributes
     * @return static
     */
    public function updateOrCreate(array $attributes)
    {
        return $this->model->updateOrCreate($attributes);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param null $time
     * @param Carbon $carbon
     * @return mixed
     */
    public function clear($time = null, Carbon $carbon)
    {
        return $this->model->where('updated_at', '<', $carbon->now()->addMinute($time))->delete();
    }

    /**
     * @return mixed
     */
    public function loggedIn()
    {
        return $this->model->whereNotNull('user')->get();
    }

    /**
     * @return mixed
     */
    public function guests()
    {
        return $this->model->whereNull('user')->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findUser($id)
    {
        return $this->model->where('user', $id)->first();
    }

    /**
     * @param $ip
     * @return mixed
     */
    public function findByIp($ip)
    {
        return $this->model->where('ip', '=', $ip)->first();
    }

    /**
     * @return bool
     */
    public function isUser()
    {
        return ($this->model->getAttribute('user') != 0);
    }

    /**
     * @return bool
     */
    public function isGuest()
    {
        return ($this->user == 0);
    }

    /**
     * @return null|Useragent
     */
    public function getAgentAttribute()
    {
        if(isset($this->model->agents[$this->model->getAttribute('useragent')]))
            return $this->model->agents[$this->model->getAttribute('useragent')];

        if($this->model->getAttributeValue('useragent') == "")
            return null;

        return $this->model->agents[$this->model->getAttributeValue('useragent')] = new Useragent($this->model->getAttributeValue('useragent'));
    }

    /**
     * @return mixed|string
     */
    public function getAgentsAttribute()
    {
        if ($this->is_browser())
        {
            $agent = $this->model->getAttributeValue('browser').' '.$this->model->getAttributeValue('version');
        }
        elseif ($this->is_robot())
        {
            $agent = $this->model->getAttributeValue('robot');
        }
        elseif ($this->is_mobile())
        {
            $agent = $this->model->getAttributeValue('mobile');
        }
        else
        {
            $agent = 'Unidentified User Agent';
        }

        return $agent;
    }

    /**
     * @param $value
     */
    public function setSidAttribute($value)
    {
        $this->model->setAttribute('sid', $value);
    }

    /* Wrapper for the Useragent class */

    /**
     * @param null $key
     * @return null
     */
    public function is_browser($key = null)
    {
        if(is_null($this->model->getAttributeValue('agent'))) return null;
        return $this->model->getAttributeValue('agent')->is_browser($key);
    }

    /**
     * @param null $key
     * @return null
     */
    public function is_robot($key = null)
    {
        if(is_null($this->model->getAttributeValue('agent'))) return null;
        return $this->model->getAttributeValue('agent')->is_robot($key);
    }

    /**
     * @param null $key
     * @return null
     */
    public function is_mobile($key = null)
    {
        if(is_null($this->model->getAttributeValue('agent'))) return null;
        return $this->model->getAttributeValue('agent')->is_mobile($key);
    }

    /**
     * @return null
     */
    public function is_referral()
    {
        if(is_null($this->model->getAttributeValue('agent'))) return null;
        return $this->model->getAttributeValue('agent')->is_referral();
    }

    /**
     * @return null
     */
    public function getPlatformAttribute()
    {
        if(is_null($this->model->getAttributeValue('agent'))) return null;
        return $this->model->getAttributeValue('agent')->platform();
    }

    /**
     * @return null
     */
    public function getBrowserAttribute()
    {
        if(is_null($this->model->getAttributeValue('agent'))) return null;
        return $this->model->getAttributeValue('agent')->browser();
    }

    /**
     * @return null
     */
    public function getVersionAttribute()
    {
        if(is_null($this->model->getAttributeValue('agent'))) return null;
        return $this->model->getAttributeValue('agent')->version();
    }

    /**
     * @return null
     */
    public function getRobotAttribute()
    {
        if(is_null($this->model->getAttributeValue('agent'))) return null;
        return $this->model->getAttributeValue('agent')->robot();
    }

    /**
     * @return null
     */
    public function getMobileAttribute()
    {
        if(is_null($this->model->getAttributeValue('agent'))) return null;
        return $this->model->getAttributeValue('agent')->mobile();
    }

    /**
     * @return null
     */
    public function getReferrerAttribute()
    {
        if(is_null($this->model->getAttributeValue('agent'))) return null;
        return $this->model->getAttributeValue('agent')->referrer();
    }
}