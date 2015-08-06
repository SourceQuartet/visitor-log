<?php namespace SourceQuartet\VisitorLog\Middleware;
use Closure;
use SourceQuartet\VisitorLog\Repositories\Visitor\VisitorManager;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Auth\Guard as Auth;

class VisitorMiddleware
{
    private $visitor;
    private $config;
    private $auth;
    public function __construct(VisitorManager $visitorManager,
                                Config $config,
                                Auth $auth)
    {
        $this->visitor = $visitorManager;
        $this->config = $config;
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        // First clear out all "old" visitors
        $this->visitor->clear();

        $page = $request->path();
        $ignore = $this->config->get('visitor-log::ignore');
        if(is_array($ignore) && in_array($page, $ignore))
            //We ignore this site
            return;

        $visitor = $this->visitor->findCurrent();

        $user = null;
        $usermodel = strtolower($this->config->get('visitor-log::usermodel'));
        if($usermodel == "auth" && $this->auth->check())
        {
            $user = Auth::user()->id;
        }

        $this->visitor->updateOrCreate([
            'ip' => $request->getClientIp(),
            'useragent' => $request->server('HTTP_USER_AGENT'),
            'sid' => str_random(25),
            'user' => $user,
            'page' => $page
        ]);

        return $next($request);
    }
}