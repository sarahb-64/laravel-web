<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;

class WebTrafficController extends Controller
{
    /**
     * Display a list of projects for the user to choose from.
     */
    public function index()
    {
        $projects = Auth::user()->projects()->whereNotNull('analytics_property_id')->get();
        $configExists = !empty(config('analytics.property_id')) && file_exists(config('analytics.service_account_credentials_json'));

        return view('web-traffic.index', compact('projects', 'configExists'));
    }

    /**
     * Show the web traffic analysis for a specific project.
     */
    public function show(Project $project)
    {
        // Authorize that the user owns the project
        $this->authorize('view', $project);

        // Temporarily override the analytics property ID for this request
        config(['analytics.property_id' => $project->analytics_property_id]);

        $configExists = !empty($project->analytics_property_id) && file_exists(config('analytics.service_account_credentials_json'));
        $analyticsData = null;
        $error = null;

        if ($configExists) {
            try {
                $period = Period::days(30);

                // Fetch metrics
                $totalVisitorsAndPageViews = Analytics::fetchTotalUsersAndSessions($period);
                $mostVisitedPages = Analytics::fetchMostVisitedPages($period, 10);
                $topReferrers = Analytics::fetchTopReferrers($period, 10);
                $userTypes = Analytics::fetchUserTypes($period);

                // Fetch data for the chart (sessions for the last 30 days)
                $chartDataQuery = Analytics::performQuery(
                    $period,
                    'sessions',
                    ['date']
                );
                
                $chartData = $chartDataQuery->map(function ($row) {
                    return ['date' => Carbon::createFromFormat('Ymd', $row['date'])->format('Y-m-d'), 'sessions' => (int) $row['sessions']];
                });

                $analyticsData = [
                    'totalVisitors' => $totalVisitorsAndPageViews->sum('sessions'), // Using sessions as 'visits'
                    'totalUsers' => $totalVisitorsAndPageViews->sum('activeUsers'),
                    'mostVisitedPages' => $mostVisitedPages,
                    'topReferrers' => $topReferrers,
                    'userTypes' => $userTypes,
                    'chartData' => $chartData->pluck('sessions'),
                    'chartLabels' => $chartData->pluck('date'),
                ];

            } catch (\Exception $e) {
                $error = 'Error al conectar con Google Analytics: ' . $e->getMessage();
            }
        } else {
            $error = 'La configuración de Google Analytics no está completa para este proyecto o las credenciales generales no se encuentran.';
        }

        return view('web-traffic.show', compact('project', 'analyticsData', 'configExists', 'error'));
    }
}
