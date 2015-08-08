<?php namespace SourceQuartet\VisitorLog\Visitor;

use Carbon\Carbon;
use SourceQuartet\Exception\InvalidArgumentException;
use SourceQuartet\VisitorLog\VisitorModel;
use \SourceQuartet\VisitorLog\Contracts\Visitor\VisitorContract;
use Illuminate\Database\DatabaseManager;

class VisitorRepository implements VisitorContract
{
    /**
     * @var Visitor
     */
    private $model;

    /**
     * @var DatabaseManager
     */
    private $db;


    /**
     * @param VisitorModel $visitorModel
     * @param DatabaseManager $databaseManager
     */
    public function __construct(VisitorModel $visitorModel,
                                DatabaseManager $databaseManager)
    {
        $this->model = $visitorModel;
        $this->db = $databaseManager;
    }


    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|Collection|static[]
     */
    public function all(array $columns = ['*'])
    {
        return $this->model->all($columns);
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
     * @return mixed
     */
    public function clear($time = null)
    {
        return $this->db->table($this->model->getTable())
            ->where('updated_at', '<', date($this->db->getQueryGrammar()->getDateFormat(), strtotime('-'.$time.' minutes')))
            ->delete();
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
        return ($this->model->getAttribute('user') == 0);
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
