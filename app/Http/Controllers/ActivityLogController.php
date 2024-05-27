<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use App\Models\{CekNama, CekVoucher, Brand};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{

    public function index(Request $request)
    {
        if ($request->start && $request->end) {
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $request->start = $start->format('Y-m-d');
            $request->end = $end->format('Y-m-d');
        }

        $logs = LogAktivitas::search($request)
            ->latest()
            ->paginate(config('app.pagination.default'));

        
        return view('web.pages.log.index', compact(
            'logs',
            'start',
            'end'
        ));
    }
}
