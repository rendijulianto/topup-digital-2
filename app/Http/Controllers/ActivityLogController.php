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

    public function cekNama(Request $request)
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
        
        $logs = CekNama::search($request)
            ->latest()
            ->paginate(config('app.pagination.default'));

        $brands = Brand::orderBy('nama')->get();
        
        return view('web.pages.log.cek-nama', compact(
            'logs',
            'start',
            'end',
            'brands'
        ));
    }

    public function destroyCekNama($id)
    {
   
        DB::beginTransaction();
        try {
            $cekNama = CekNama::findOrFail($id);
            $cekNama->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
       
    }

    public function cekVoucher(Request $request)
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
        
        $logs = CekVoucher::search($request)
            ->latest()
            ->paginate(config('app.pagination.default'));

        $brands = Brand::orderBy('nama')->get();
        
        return view('web.pages.log.cek-voucher', compact(
            'logs',
            'start',
            'end',
            'brands'
        ));
    }
}
