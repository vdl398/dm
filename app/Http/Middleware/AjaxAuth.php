<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\AjaxResource;
use Illuminate\Support\Facades\Auth;

class AjaxAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		if (!Auth::check()) {
		    $resource = new AjaxResource();
		    $resource->setError(['auth error']);
		    return response(['data' => $resource->toArray(null)]);
		}
		return $next($request);
    }
}
