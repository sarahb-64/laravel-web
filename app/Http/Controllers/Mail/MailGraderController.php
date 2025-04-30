<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MailchimpMarketing\ApiClient;

class MailGraderController extends Controller
{
    private $mailchimp;

    public function __construct()
    {
        $this->mailchimp = new ApiClient();
        $this->mailchimp->setConfig([
            'apiKey' => config('services.mailchimp.key'),
            'server' => config('services.mailchimp.server')
        ]);
    }

    public function index()
    {
        $header = [
            'title' => 'Email Campaign Analyzer',
            'description' => 'Analyze your email marketing campaigns and get actionable insights'
        ];

        return view('mail.grader.index', ['header' => $header]);
    }

    public function analyzeCampaign(Request $request)
    {
        $campaignId = $request->input('campaign_id');
        
        try {
            // Get campaign details
            $campaign = $this->mailchimp->campaigns->get($campaignId);
            
            // Get campaign reports
            $report = $this->mailchimp->reports->get($campaignId);
            
            // Analyze metrics
            $analysis = $this->analyzeMetrics($report);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'campaign' => $campaign,
                    'report' => $report,
                    'analysis' => $analysis
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    private function analyzeMetrics($report)
    {
        $analysis = [
            'open_rate' => [
                'value' => $report->opens / $report->recipients,
                'rating' => $this->rateMetric($report->opens / $report->recipients, 0.2),
                'suggestions' => $this->getOpenRateSuggestions($report)
            ],
            'click_rate' => [
                'value' => $report->clicks / $report->recipients,
                'rating' => $this->rateMetric($report->clicks / $report->recipients, 0.1),
                'suggestions' => $this->getClickRateSuggestions($report)
            ],
            'unsubscribe_rate' => [
                'value' => $report->unsubscribed / $report->recipients,
                'rating' => $this->rateMetric($report->unsubscribed / $report->recipients, 0.02),
                'suggestions' => $this->getUnsubscribeSuggestions($report)
            ]
        ];

        return $analysis;
    }

    private function rateMetric($value, $benchmark)
    {
        if ($value >= $benchmark * 1.5) return 'excellent';
        if ($value >= $benchmark * 1.2) return 'good';
        if ($value >= $benchmark) return 'average';
        return 'below_average';
    }

    private function getOpenRateSuggestions($report)
    {
        return [
            'Improve subject line by testing different variations',
            'Optimize send time based on audience behavior',
            'Segment your list for more targeted content'
        ];
    }

    private function getClickRateSuggestions($report)
    {
        return [
            'Add more clear call-to-action buttons',
            'Improve content relevance',
            'Test different email layouts'
        ];
    }

    private function getUnsubscribeSuggestions($report)
    {
        return [
            'Improve email frequency',
            'Add more unsubscribe options',
            'Enhance content value'
        ];
    }
}