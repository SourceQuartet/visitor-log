<?php namespace SourceQuartet\VisitorLog\Middleware;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use SourceQuartet\VisitorLog\VisitorLogFacade as Visitor;

class VisitorMiddleware
{

    public function handle($request, Closure $next)
    {
        // Clearing users and passing Carbon instance and time through
        Visitor::clear(new Carbon, config('visitor-log::onlinetime'));

        // Getting current user path.
        $page = $request->path();
        $ignore = config('visitor-log::ignore');

        // If this path is ignored, send the request.
        if(is_array($ignore) && in_array($page, $ignore))
        {
            return $next($request);
        }

        // Generating random visitor ID
        $sid = str_random(25);

        // Attempting to get the current visitor
        $visitor = Visitor::findCurrent();

        // If visitor is logged in, we try to get the User ID
        $user = null;
        $usermodel = strtolower(config('visitor-log::usermodel'));
        if($usermodel == "auth" && Auth::check())
        {
            $user = Auth::user()->id;
        }

        // If the attempt to find the current visitor is a failure, we store him into the database
        if(!$visitor)
        {
            Visitor::setSidAttribute($sid);

            Visitor::create([
                'ip' => $request->getClientIp(),
                'useragent' => $request->server('HTTP_USER_AGENT'),
                'sid' => $sid,
                'user' => $user,
                'page' => $page
            ]);
        }

        // Returning the request
        return $next($request);
    }
}
