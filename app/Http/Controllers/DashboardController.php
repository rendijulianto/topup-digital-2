<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
// Topup
use App\Models\{Topup, Produk,Banner};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{


    public function customer(Request $request)
    {
        $banners = Banner::
        where('status', true)->get();
        return view('web.pages.dashboard.guest', compact('banners'));
    }

    /**
     * Display a listing of the resource.
     */
    public function admin(Request $request)
    {
        if ($request->start && $request->end) {
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        }
        $request->start = $start->format('Y-m-d');
        $request->end = $end->format('Y-m-d');
        $request->jumlah = 10;
    
        $reportAdmin = Topup::reportAdmin($request);
        $products = Produk::bestSeller($request)->get();
        $products = $products->map(function ($product) {
            $product->nama = Str::replaceFirst('Aktivasi ', '', $product->nama);
            return $product;
        });
        return view('web.pages.dashboard.admin', compact('reportAdmin', 'products', 'start', 'end'));
    }

    public function cashier(Request $request)
    {   
        $user = Auth::guard('pengguna')->user();

        $request->start = $request->start ??  Carbon::now()->startOfWeek();
        $request->kasir_id = $user->id;
        $request->end = $request->end ?? Carbon::now()->endOfWeek();
        $today= Carbon::now();
        $yesterday = Carbon::yesterday();
        $report = Topup::getReport($request);
    
        $report = collect($report);
        $incomeToday = $report[$today->toDateString()]['sukses'] ? $report[$today->toDateString()]['sukses'] + $report[$today->toDateString()]['pending'] : 0;
        $totalToday = $report[$today->toDateString()]['total_sukses'] ? $report[$today->toDateString()]['total_sukses'] + $report[$today->toDateString()]['total_pending'] : 0;
        $reportsTotalSuccess = $report->map(function ($item) {
            return $item['total_sukses'];
        });
 
        $report = collect($report);
        $todayString = $today->toDateString();

        $incomeToday = $report[$todayString]['sukses'] ?? 0 + $report[$todayString]['pending'] ?? 0;
        $totalToday = $report[$todayString]['total_sukses'] ?? 0 + $report[$todayString]['total_pending'] ?? 0;

        $reportsTotalSuccess = $report->pluck('total_sukses');
        $reportsTotalPending = $report->pluck('total_pending');
        $reportsTotalFailed = $report->pluck('total_gagal');
        $reportsSumSuccess = $report->pluck('sukses');
        $reportsSumPending = $report->pluck('pending');
        $reportsSumFailed = $report->pluck('gagal');

        $reportCount = $report->map(function ($item) {
            return $item['total_sukses'] + $item['total_pending'] + $item['total_gagal'];
        });

        return view('web.pages.dashboard.cashier', compact('report', 'request', 'incomeToday', 'totalToday', 'reportsTotalSuccess', 'reportsTotalPending', 'reportsTotalFailed', 'reportsSumSuccess', 'reportsSumPending', 'reportsSumFailed', 'reportCount'));
    }

    public function injector(Request $request)
    {
        $user = Auth::guard('pengguna')->user();

        $request->start = $request->start ??  Carbon::now()->startOfWeek();
        $request->user_id = $user->id;
        $request->end = $request->end ?? Carbon::now()->endOfWeek();
        $today= Carbon::now();
        $yesterday = Carbon::yesterday();
        $report = Topup::reportInject($request);
    
        $report = collect($report);
        $incomeToday = $report[$today->toDateString()]['sukses'] ? $report[$today->toDateString()]['sukses'] + $report[$today->toDateString()]['pending'] : 0;
        $totalToday = $report[$today->toDateString()]['total_sukses'] ? $report[$today->toDateString()]['total_sukses'] + $report[$today->toDateString()]['total_pending'] : 0;
        $reportsTotalSuccess = $report->map(function ($item) {
            return $item['total_sukses'];
        });
 
        $report = collect($report);
        $todayString = $today->toDateString();

        $incomeToday = $report[$todayString]['sukses'] ?? 0 + $report[$todayString]['pending'] ?? 0;
        $totalToday = $report[$todayString]['total_sukses'] ?? 0 + $report[$todayString]['total_pending'] ?? 0;

        $reportsTotalSuccess = $report->pluck('total_sukses');
        $reportsTotalPending = $report->pluck('total_pending');
        $reportsTotalFailed = $report->pluck('total_gagal');
        $reportsSumSuccess = $report->pluck('sukses');
        $reportsSumPending = $report->pluck('pending');
        $reportsSumFailed = $report->pluck('gagal');

        $reportCount = $report->map(function ($item) {
            return $item['total_sukses'] + $item['total_pending'] + $item['total_gagal'];
        });

        return view('web.pages.dashboard.injector', compact('report', 'request', 'incomeToday', 'totalToday', 'reportsTotalSuccess', 'reportsTotalPending', 'reportsTotalFailed', 'reportsSumSuccess', 'reportsSumPending', 'reportsSumFailed', 'reportCount'));
    }

}
