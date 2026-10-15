<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to inject custom CP styles into the HTML response.
 *
 * Add to service provider boot method to apply to all CP requests:
 *
 * use Illuminate\Contracts\Http\Kernel;
 * use Illuminate\Http\Request;
 *
 * public function boot(Kernel $kernel, Request $request): void
 * {
 *   if ($request->isCpRequest()) {
 *      $kernel->pushMiddleware(InjectCpStyles::class);
 *   }
 * }
 */
class InjectCpStyles
{
    public function handle(Request $request, Closure $next): Response
    {
        // Proceed with the request and get the response
        $response = $next($request);

        if (str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            $response->setContent(
                str_replace(
                    '</head>',
                    '<style>' .
                        File::get(resource_path('css/cp-styles.css')) .
                        '</style>' .
                        '</head>',
                    $response->getContent(),
                ),
            );
        }

        return $response;
    }
}
