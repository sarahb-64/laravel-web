<?php

namespace App\Http\Controllers\Ads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Ads\GoogleAds\Client\GoogleAdsClient;
use Facebook\Facebook;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\CampaignMetric;

class AdsGraderController extends Controller
{
    private $googleAdsClient;
    private $facebook;
    protected $useMocks;

    public function __construct()
    {
        $this->middleware(['auth', 'throttle.campaign']);

        $this->useMocks = env('ADS_USE_MOCKS', true);

        if (!$this->useMocks) {
            try {
                $this->googleAdsClient = new GoogleAdsClient([
                    'clientId' => config('services.google_ads.client_id'),
                    'clientSecret' => config('services.google_ads.client_secret'),
                    'developerToken' => config('services.google_ads.developer_token'),
                    'loginCustomerId' => config('services.google_ads.login_customer_id'),
                    'refreshToken' => config('services.google_ads.refresh_token')
                ]);

                $this->facebook = new Facebook([
                    'app_id' => config('services.facebook_ads.app_id'),
                    'app_secret' => config('services.facebook_ads.app_secret'),
                    'default_graph_version' => 'v18.0'
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to initialize ads clients: ' . $e->getMessage());
                throw $e;
            }
        }
    }

    public function index()
    {
        return Inertia::render('Ads/AdsGrader/Index');
    }

    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|in:google,facebook',
            'campaign_id' => 'required|string|regex:/^[0-9]+$/'
        ]);

        try {
            $cacheKey = 'campaign_analysis_' . $validated['campaign_id'];
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $metrics = $this->getMetrics($validated['platform'], $validated['campaign_id']);
            $recommendations = $this->generateRecommendations($metrics);
            $this->storeAnalysisHistory($validated['campaign_id'], $metrics, $recommendations, $validated['platform']);

            $history = CampaignMetric::where('campaign_id', $validated['campaign_id'])
                ->where('platform', $validated['platform'])
                ->orderBy('analyzed_at', 'desc')
                ->take(10)
                ->get();

            $trends = $this->analyzePerformanceTrends($history);

            $response = [
                'metrics' => $metrics,
                'recommendations' => $recommendations,
                'history' => $history,
                'trends' => $trends
            ];

            Cache::put($cacheKey, $response, now()->addMinutes(15));

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Campaign analysis failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to analyze campaign',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getMetrics($platform, $campaignId)
    {
        switch ($platform) {
            case 'google':
                return $this->getGoogleAdsMetrics($campaignId);
            case 'facebook':
                return $this->getFacebookAdsMetrics($campaignId);
            default:
                throw new \Exception('Invalid platform');
        }
    }

    private function getGoogleAdsMetrics($campaignId)
    {
        if ($this->useMocks) {
            $clicks = rand(100, 1000);
            $impressions = rand(1000, 10000);
            $conversions = rand(10, 100);
            $cost = rand(100, 1000);

            return [
                'clicks' => $clicks,
                'impressions' => $impressions,
                'conversions' => $conversions,
                'cost' => $cost,
                'ctr' => $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0,
                'cpc' => $clicks > 0 ? round($cost / $clicks, 2) : 0,
                'conversion_rate' => $clicks > 0 ? round(($conversions / $clicks) * 100, 2) : 0
            ];
        }

        try {
            $query = "SELECT campaign.id, metrics.clicks, metrics.impressions, metrics.cost_micros, metrics.conversions
                      FROM campaign
                      WHERE campaign.id = {$campaignId}";

            $response = $this->googleAdsClient->search($query);
            $row = $response->getIterator()->current();

            $clicks = $row->getMetrics()->getClicks();
            $impressions = $row->getMetrics()->getImpressions();
            $cost = $row->getMetrics()->getCostMicros() / 1_000_000;
            $conversions = $row->getMetrics()->getConversions();

            return [
                'clicks' => $clicks,
                'impressions' => $impressions,
                'cost' => $cost,
                'conversions' => $conversions,
                'ctr' => $impressions > 0 ? ($clicks / $impressions) * 100 : 0,
                'cpc' => $clicks > 0 ? $cost / $clicks : 0,
                'conversion_rate' => $clicks > 0 ? ($conversions / $clicks) * 100 : 0
            ];
        } catch (\Exception $e) {
            throw new \Exception('Failed to fetch Google Ads metrics: ' . $e->getMessage());
        }
    }

    private function getFacebookAdsMetrics($campaignId)
    {
        if ($this->useMocks) {
            $clicks = rand(100, 1000);
            $impressions = rand(1000, 10000);
            $conversions = rand(10, 100);
            $cost = rand(100, 1000);

            return [
                'clicks' => $clicks,
                'impressions' => $impressions,
                'conversions' => $conversions,
                'cost' => $cost,
                'ctr' => $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0,
                'cpc' => $clicks > 0 ? round($cost / $clicks, 2) : 0,
                'conversion_rate' => $clicks > 0 ? round(($conversions / $clicks) * 100, 2) : 0
            ];
        }

        try {
            $accessToken = config('services.facebook_ads.access_token');
            $response = $this->facebook->get("/{$campaignId}?fields=insights{clicks,impressions,spend,actions}", $accessToken);
            $campaign = $response->getDecodedBody();
            $insights = $campaign['insights']['data'][0];

            $clicks = $insights['clicks'];
            $impressions = $insights['impressions'];
            $cost = $insights['spend'];
            $conversions = $insights['actions'][0]['value'];

            return [
                'clicks' => $clicks,
                'impressions' => $impressions,
                'cost' => $cost,
                'conversions' => $conversions,
                'ctr' => $impressions > 0 ? ($clicks / $impressions) * 100 : 0,
                'cpc' => $clicks > 0 ? $cost / $clicks : 0,
                'conversion_rate' => $clicks > 0 ? ($conversions / $clicks) * 100 : 0
            ];
        } catch (\Exception $e) {
            throw new \Exception('Failed to fetch Facebook Ads metrics: ' . $e->getMessage());
        }
    }

    private function generateRecommendations($metrics)
    {
        $recommendations = [];

        if ($metrics['ctr'] < 1) {
            $recommendations[] = [
                'type' => 'warning',
                'title' => 'Low CTR',
                'message' => 'The click-through rate is below 1%. Consider optimizing your ad copy and targeting.',
                'suggestions' => [
                    'Test different ad headlines',
                    'Improve targeting criteria',
                    'Use more engaging visuals'
                ]
            ];
        } elseif ($metrics['ctr'] < 2) {
            $recommendations[] = [
                'type' => 'info',
                'title' => 'Average CTR',
                'message' => 'The click-through rate is average. There might be room for improvement.',
                'suggestions' => [
                    'A/B test different ad variations',
                    'Refine targeting parameters',
                    'Consider different ad formats'
                ]
            ];
        }

        if ($metrics['conversion_rate'] < 2) {
            $recommendations[] = [
                'type' => 'warning',
                'title' => 'Low Conversion Rate',
                'message' => 'The conversion rate is below 2%.',
                'suggestions' => [
                    'Optimize landing page for conversions',
                    'Review and refine targeting criteria',
                    'Test different landing page variations'
                ]
            ];
        } elseif ($metrics['conversion_rate'] < 5) {
            $recommendations[] = [
                'type' => 'info',
                'title' => 'Average Conversion Rate',
                'message' => 'Consider testing improvements.',
                'suggestions' => [
                    'Implement A/B testing',
                    'Optimize landing page elements',
                    'Review conversion funnel'
                ]
            ];
        }

        if ($metrics['cpc'] > 2) {
            $recommendations[] = [
                'type' => 'warning',
                'title' => 'High CPC',
                'message' => 'The cost per click is above $2.',
                'suggestions' => [
                    'Adjust bid strategies',
                    'Optimize targeting parameters',
                    'Review and refine ad groups'
                ]
            ];
        }

        $cost_per_conversion = $metrics['conversions'] > 0 ? $metrics['cost'] / $metrics['conversions'] : 0;
        if ($cost_per_conversion > 50) {
            $recommendations[] = [
                'type' => 'warning',
                'title' => 'High Cost per Conversion',
                'message' => 'Potential efficiency issues.',
                'suggestions' => [
                    'Review and optimize conversion tracking',
                    'Implement conversion-based bidding',
                    'Consider retargeting',
                    'Test ad formats'
                ]
            ];
        } elseif ($cost_per_conversion > 30) {
            $recommendations[] = [
                'type' => 'info',
                'title' => 'Above Average Cost per Conversion',
                'message' => 'There might be optimization opportunities.',
                'suggestions' => [
                    'Optimize bid strategies',
                    'Test ad variations',
                    'Refine targeting'
                ]
            ];
        }

        if ($metrics['ctr'] < 0.5) {
            $recommendations[] = [
                'type' => 'warning',
                'title' => 'Low Engagement',
                'message' => 'Engagement rate is very low.',
                'suggestions' => [
                    'Improve creative quality',
                    'Refine targeting',
                    'Try different formats'
                ]
            ];
        }

        return $recommendations;
    }

    private function storeAnalysisHistory($campaignId, $metrics, $recommendations, $platform)
    {
        CampaignMetric::create([
            'campaign_id' => $campaignId,
            'metrics' => $metrics,
            'recommendations' => $recommendations,
            'platform' => $platform,
            'analyzed_at' => now()
        ]);
    }

    public function getAnalysisHistory(Request $request)
    {
        $validated = $request->validate([
            'campaign_id' => 'required|string|regex:/^[0-9]+$/',
            'platform' => 'required|in:google,facebook'
        ]);

        try {
            $history = CampaignMetric::where('campaign_id', $validated['campaign_id'])
                ->where('platform', $validated['platform'])
                ->orderBy('analyzed_at', 'desc')
                ->take(10)
                ->get();

            return response()->json(['history' => $history]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch analysis history: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch analysis history',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function analyzePerformanceTrends($history)
    {
        return [
            'metrics' => [
                'clicks' => $this->calculateTrend($history, 'clicks'),
                'impressions' => $this->calculateTrend($history, 'impressions'),
                'ctr' => $this->calculateTrend($history, 'ctr'),
                'cpc' => $this->calculateTrend($history, 'cpc'),
                'conversions' => $this->calculateTrend($history, 'conversions'),
                'conversion_rate' => $this->calculateTrend($history, 'conversion_rate')
            ]
        ];
    }

    private function calculateTrend($history, $metric)
    {
        if (count($history) < 2) {
            return [
                'value' => $history[0]->metrics[$metric] ?? 0,
                'trend' => 'stable',
                'percentage' => 0
            ];
        }

        $latest = $history[0]->metrics[$metric] ?? 0;
        $previous = $history[1]->metrics[$metric] ?? 0;

        if ($previous == 0) {
            return [
                'value' => $latest,
                'trend' => 'up',
                'percentage' => 100
            ];
        }

        $percentage = (($latest - $previous) / $previous) * 100;
        $trend = abs($percentage) < 2 ? 'stable' : ($percentage > 0 ? 'up' : 'down');

        return [
            'value' => $latest,
            'trend' => $trend,
            'percentage' => round($percentage, 2)
        ];
    }
}
