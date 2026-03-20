<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $articles = Article::all();
        $galleries = Gallery::all();

        $today = Visit::whereDate('visited_at', Carbon::today())->count();
        $week  = Visit::whereBetween('visited_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $month = Visit::whereMonth('visited_at', Carbon::now()->month)->count();
        $year  = Visit::whereYear('visited_at', Carbon::now()->year)->count();

        $homepageUrls = ['https://kamcup.com', 'https://kamcup.com/'];

        $homeVisitToday = Visit::whereIn('url', $homepageUrls)->whereDate('visited_at', Carbon::today())->count();
        $homeVisitWeek  = Visit::whereIn('url', $homepageUrls)->whereBetween('visited_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $homeVisitMonth = Visit::whereIn('url', $homepageUrls)->whereMonth('visited_at', Carbon::now()->month)->count();
        $homeVisitYear  = Visit::whereIn('url', $homepageUrls)->whereYear('visited_at', Carbon::now()->year)->count();

        $homeVisit    = Visit::whereIn('url', $homepageUrls)->count();
        $articleVisit = Visit::where('url', 'https://kamcup.com/articles')->count();
        $galleryVisit = Visit::where('url', 'https://kamcup.com/galleries')->count();
        $contactVisit = Visit::where('url', 'https://kamcup.com/contact')->count();

        // Pakai Cache agar persist (tidak hilang saat session expire)
        $hideUserPages = Cache::get('hide_user_pages', false);

        $visitData = [
            'Homepage' => $homeVisit,
            'Articles' => $articleVisit,
            'Galleries' => $galleryVisit,
            'Contact'  => $contactVisit,
        ];

        return view('dashboard.admin', compact(
            'articles', 'galleries',
            'today', 'week', 'month', 'year',
            'homeVisitToday', 'homeVisitWeek', 'homeVisitMonth', 'homeVisitYear',
            'homeVisit', 'articleVisit', 'galleryVisit', 'contactVisit',
            'hideUserPages', 'visitData'
        ));
    }

    /**
     * Toggle visibility tombol Komunitas, Teman, Feeds di profile user
     * Dipanggil via AJAX dari dashboard
     */
    public function toggleUserPages(Request $request)
    {
        $current  = Cache::get('hide_user_pages', false);
        $newValue = !$current;

        Cache::forever('hide_user_pages', $newValue);

        return response()->json([
            'success' => true,
            'hidden'  => $newValue,
        ]);
    }
}