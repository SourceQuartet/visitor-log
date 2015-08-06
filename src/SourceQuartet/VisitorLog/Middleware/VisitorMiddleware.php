<?php namespace SourceQuartet\VisitorLog\Middleware;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use SourceQuartet\VisitorLog\VisitorLogFacade as Visitor;

class VisitorMiddleware
{

    public function handle($request, Closure $next)
    {
        // First clear out all "old" visitors
        Visitor::clear(new Carbon, config('visitor-log::onlinetime'));

        $page = $request->path();
        $ignore = config('visitor-log::ignore');
        if(is_array($ignore) && in_array($page, $ignore))
            //We ignore this site
            return;
        
        $sid = str_random(25);

        $visitor = Visitor::findCurrent();

        $user = null;
        $usermodel = strtolower(config('visitor-log::usermodel'));
        if($usermodel == "auth" && Auth::check())
        {
            $user = Auth::user()->id;
        }
        
        if(!visitor)
        {
            Visitor::setSidAttribute($sid)

            Visitor::updateOrCreate([
                'ip' => $request->getClientIp(),
                'useragent' => $request->server('HTTP_USER_AGENT'),
                'sid' => $sid,
                'user' => $user,
                'page' => $page
            ]);
        }

        return $next($request);
    }
}
