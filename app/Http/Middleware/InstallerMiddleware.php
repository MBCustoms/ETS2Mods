<?php

namespace App\Http\Middleware;

use App\Services\InstallerService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InstallerMiddleware
{
    protected $installer;

    public function __construct(InstallerService $installer)
    {
        $this->installer = $installer;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isInstalled = $this->installer->isInstalled();
        
        // If trying to access install routes but already installed -> redirect to home
        if ($request->is('install*') && $isInstalled) {
            return redirect('/');
        }

        // If NOT installed and NOT accessing install routes -> redirect to install
        if (!$isInstalled && !$request->is('install*')) {
            return redirect('/install');
        }

        return $next($request);
    }
}
