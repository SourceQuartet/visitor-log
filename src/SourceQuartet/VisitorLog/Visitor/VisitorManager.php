<?php namespace SourceQuartet\VisitorLog\Visitor;

use Carbon\Carbon;
use Illuminate\Config\Repository as Config;
use Illuminate\Session\Store as Session;
use SourceQuartet\VisitorLog\Useragent;
use SourceQuartet\VisitorLog\Exception\InvalidArgumentException;
use DeviceDetector\DeviceDetector;
class VisitorManager implements Visitor
{
    /**
     * @var VisitorRepository
     */
    protected $visitorRepository;
    /**
     * @var Session
     */
    protected $session;
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var DeviceDetector
     */
    protected $agentDetector;

    /**
     * @param VisitorRepository $visitorRepository
     * @param Session $session
     * @param Config $config
     */
    public function __construct(VisitorRepository $visitorRepository,
                                Session $session,
                                Config $config)
    {
        $this->visitorRepository = $visitorRepository;
        $this->session = $session;
        $this->config = $config;
        $this->setAgentDetector();

    }

    /**
     * Set Agent Detector
     */
    public function setAgentDetector()
    {
        $this->agentDetector = new DeviceDetector($this->getUseragent());
    }

    /**
     * @return DeviceDetector
     */
    public function getAgentDetector()
    {
        return $this->agentDetector;
    }

    /**
     * @param null $id
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function find($id = null)
    {
        if(is_null($id))
        {
            throw new InvalidArgumentException('The argument id should be set');
        }

        return $this->visitorRepository->find($id);
    }

    /**
     * @param array $attributes
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function create(array $attributes)
    {
        if(!is_array($attributes))
        {
            throw new InvalidArgumentException('The attributes argument should be an array');
        }

        return $this->visitorRepository->create($attributes);
    }

    /**
     * @param array $attributes
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function updateOrCreate(array $attributes)
    {
        if(!is_array($attributes))
        {
            throw new InvalidArgumentException('The attributes argument should be an array');
        }

        return $this->visitorRepository->updateOrCreate($attributes);
    }

    /**
     * @param $id
     * @return bool
     */
    public function checkOnline($id)
    {
        $user = $this->visitorRepository->findUser($id);

        if(count($user) == 0){
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function findCurrent()
    {
        if(!$this->session->has('visitor_log_sid'))
            return false;

        $sid = $this->session->get('visitor_log_sid');
        return $this->visitorRepository->find($sid);
    }

    /**
     * @param null $time
     * @param Carbon $carbon
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function clear(Carbon $carbon, $time = null)
    {
        if(is_null($time))
        {
            $this->config->get('visitor-log::onlinetime');
        }

        return $this->visitorRepository->clear($time, $carbon);
    }

    /**
     * @return mixed
     */
    public function loggedIn()
    {
        return $this->visitorRepository->loggedIn();
    }

    /**
     * @return mixed
     */
    public function guests()
    {
        return $this->visitorRepository->guests();
    }

    /**
     * @param $id
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function findUser($id)
    {
        if(!is_int($id))
        {
            throw new InvalidArgumentException('The id argument should be a valid integer');
        }
        return $this->userRepository->findUser($id);
    }

    /**
     * @param $ip
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function findByIp($ip)
    {
        if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE))
        {
            throw new InvalidArgumentException('The ip argument should be a valid IP format and not a private or reserved IP');
        }

        return $this->userRepository->findByIp($ip);
    }

    /**
     * @return bool
     */
    public function isUser()
    {
        return $this->visitorRepository->isUser();
    }

    /**
     * @param null $value
     * @throws InvalidArgumentException
     */
    public function setSidAttribute($value = null)
    {
        if(is_null($value))
        {
            throw new InvalidArgumentException('The value argument should not be empty');
        }

        $this->session->put('visitor_log_sid', $value);
        return $this->visitorRepository->setSidAttribute($value);
    }

    /**
     * @return bool
     */
    public function getUseragent()
    {
        if(!$this->session->has('visitor_log_sid'))
            return false;

        $sid = $this->session->get('visitor_log_sid');
        return $this->visitorRepository->getUseragent($sid);
    }
}