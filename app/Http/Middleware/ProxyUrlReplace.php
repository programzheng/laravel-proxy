<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProxyUrlReplace
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $proxyReplaceUrls = config('proxy');
        $url = data_get($proxyReplaceUrls, $request->route()->getName(), '');
        if ($url == '') {
            return response('', Response::HTTP_BAD_REQUEST);
        }

        if (count($request->query()) > 0) {
            $url .= sprintf('?%s', http_build_query($request->query()));
        }
        $request->merge([
            'url' => $url
        ]);

        return $next($request);
    }
}
