<?php namespace SourceQuartet\VisitorLog\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use SourceQuartet\VisitorLog\Visitor\VisitorManager;
use SourceQuartet\VisitorLog\VisitorLogFacade as Visitor;

class VisitorMiddleware
{
    private $visitorManager;
    public function __construct(VisitorManager $visitorManager)
    {
        $this->visitorManager = $visitorManager;
    }

    public function handle($request, Closure $next)
    {
        // Clearing users and passing Carbon instance and time through
        $this->visitorManager->clear(config('visitor-log::onlinetime'));

        // Getting current user path.
        $page = $request->path();
        $ignore = config('visitor-log::ignore');

        // If this path is ignored, send the request.
        if (is_array($ignore) && in_array($page, $ignore)) {
            return $next($request);
        }

        // Attempting to get the current visitor
        $visitor = $this->visitorManager->findCurrent();

        // If the attempt to find the current visitor is a failure, we store him into the database
        if (!$visitor) {
            $visitor = $this->visitorManager->create([
                'ip' => $request->getClientIp(),
                'useragent' => $request->server('HTTP_USER_AGENT'),
                'sid' => str_random(25),
                'page' => $page
            ]);
        }

        // If visitor is logged in, we try to get the User ID
        $user = null;
        $usermodel = strtolower(config('visitor-log.usermodel'));
        if ($usermodel == "laravel" && Auth::check()) {
            $user = Auth::user()->id;
        }

        $request->session()->put('visitor_log_sid', $visitor->sid);
        $visitor->user = $user;
        $visitor->page = $page;
        $visitor->save();

        // Returning the request
        return $next($request);
    }
}
