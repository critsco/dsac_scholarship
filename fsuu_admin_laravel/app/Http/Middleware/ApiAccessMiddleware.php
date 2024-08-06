<?php

namespace App\Http\Middleware;

use App\Events\UnauthorizedAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;

class ApiAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $allowedHosts = explode(',', env('ALLOWED_DOMAINS'));

        $requestHost = $request->headers->get('host');

        if (!app()->runningUnitTests()) {
            if (!in_array($requestHost, $allowedHosts, true)) {
                $requestInfo = [
                    'host' => $requestHost,
                    'ip' => $request->getClientIp(),
                    'url' => $request->getRequestUri(),
                    'agent' => $request->header('User-Agent'),
                ];
                event(new UnauthorizedAccess($requestInfo));

                throw new SuspiciousOperationException('This host is not allowed');
            }
        }

        return $next($request);
    }
}
