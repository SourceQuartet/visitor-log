<?php namespace SourceQuartet\VisitorLog\Visitor;

use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use SourceQuartet\VisitorLog\Exception\InvalidArgumentException;

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
     * @var \Jenssegers\Agent\Agent
     */
    protected $agentDetector;

    /**
     * @param VisitorRepository $visitorRepository
     * @param Request $request
     * @param Config $config
     */
    public function __construct(VisitorRepository $visitorRepository,
                                Request $request,
                                Config $config)
    {
        $this->visitorRepository = $visitorRepository;
        $this->request = $request;
        $this->config = $config;
        $this->setAgentDetector();
    }

    /**
     * Set Agent Detector
     */
    public function setAgentDetector()
    {
        $this->agentDetector = new Agent;
    }

    /**
     * @return Agent
     */
    public function getAgentDetector()
    {
        return $this->agentDetector;
    }

    /**
     * @param null $userAgent
     * @return null|string
     */
    public function setAgentToDetector($userAgent = null)
    {
        if (is_null($userAgent)) {
            $this->agentDetector->setUserAgent($this->getUseragent());
        }

        return $this->agentDetector->setUserAgent($userAgent);
    }

    /**
     * Get session instance.
     *
     * @return \Illuminate\Session\Store
     */
    public function getSession()
    {
        return $this->request->session();
    }


    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|Collection|static[]
     * @throws InvalidArgumentException
     */
    public function all(array $columns = ['*'])
    {
        if (!is_array($id)) {
            throw new InvalidArgumentException('The argument columns should be an array');
        }
        
        return $this->visitorRepository->all($columns);
    }

    /**
     * @param null $id
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function find($id = null)
    {
        if (is_null($id)) {
            throw new InvalidArgumentException('The argument id should be set');
        }

        return $this->visitorRepository->find($id);
    }

    /**
     * @param array $attributes
     * @return VisitorRepository
     * @throws InvalidArgumentException
     */
    public function create(array $attributes)
    {
        if (!is_array($attributes)) {
            throw new InvalidArgumentException('The attributes argument should be an array');
        }

        return $this->visitorRepository->create($attributes);
    }

    /**
     * @param array $attributes
     * @return VisitorRepository
     * @throws InvalidArgumentException
     */
    public function updateOrCreate(array $attributes)
    {
        if (!is_array($attributes)) {
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

        if (count($user) == 0) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function findCurrent()
    {
        $visitor = $this->findByIp($this->request->getClientIp());
        if (!$visitor) {
            return false;
        }

        $this->getSession()->put('visitor_log_sid', $visitor->sid);
        $sid = $this->getSession()->get('visitor_log_sid');
        return $this->visitorRepository->find($sid);
    }

    /**
     * @param null $time
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function clear($time = null)
    {
        if (is_null($time)) {
            $this->config->get('visitor-log::onlinetime');
        }

        return $this->visitorRepository->clear($time);
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
        if (!is_int($id)) {
            throw new InvalidArgumentException('The id argument should be a valid integer');
        }
        return $this->visitorRepository->findUser($id);
    }

    /**
     * @param $ip
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function findByIp($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            throw new InvalidArgumentException('The ip argument should be a valid IP format and not a private or reserved IP');
        }

        return $this->visitorRepository->findByIp($ip);
    }

    /**
     * @return bool
     */
    public function isUser()
    {
        return $this->visitorRepository->isUser();
    }
    
    /**
     * @return bool
     */
    public function isGuest()
    {
        return $this->visitorRepository->isGuest();
    }

    /**
     * @return bool
     */
    public function getUseragent()
    {
        $visitor = $this->findByIp($this->request->getClientIp());
        if (!$visitor) {
            return false;
        }

        $this->getSession()->put('visitor_log_sid', $visitor->sid);
        $sid = $this->getSession()->get('visitor_log_sid');
        return $this->visitorRepository->getUseragent($sid);
    }
}
