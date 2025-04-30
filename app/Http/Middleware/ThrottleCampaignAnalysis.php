<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ThrottleCampaignAnalysis
{
    protected $maxAttempts = 10;
    protected $decayMinutes = 1;

    public function handle($request, Closure $next)
    {
        $key = 'campaign_analysis_' . $request->campaign_id;

        if ($this->hasTooManyAttempts($key)) {
            return $this->buildResponse($key);
        }

        $this->hit($key);

        return $next($request);
    }

    protected function hasTooManyAttempts($key)
    {
        return Cache::has($key);
    }

    protected function hit($key)
    {
        Cache::put($key, 1, now()->addMinutes($this->decayMinutes));
    }

    protected function buildResponse($key)
    {
        return response()->json([
            'error' => 'Too Many Attempts',
            'message' => 'You have exceeded the maximum number of campaign analysis attempts. Please try again later.'
        ], Response::HTTP_TOO_MANY_REQUESTS);
    }
}