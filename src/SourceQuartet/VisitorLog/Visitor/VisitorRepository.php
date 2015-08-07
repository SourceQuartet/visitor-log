<?php namespace SourceQuartet\VisitorLog\Visitor;
use Carbon\Carbon;
use SourceQuartet\Exception\InvalidArgumentException;
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
     * @param $value
     */
    public function setSidAttribute($value)
    {
        $this->model->setAttribute('sid', $value);
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function getUseragent($id = null)
    {
        return $this->find($id)->useragent;
    }
}