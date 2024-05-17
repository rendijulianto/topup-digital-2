<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Topup, Brand, Kategori, Pelanggan, Produk, Supplier, Tipe, Pengguna};

// Carbon
use Carbon\Carbon;

class VoucherController extends Controller
{
    public function index(Request $request)
    {   

        if ($request->start && $request->end) {
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);
            $request->merge(['start' => Carbon::parse($request->start)->format('Y-m-d')]);
            $request->merge(['end' => Carbon::parse($request->end)->format('Y-m-d')]);

        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $request->merge(['start' => $start->format('Y-m-d')]);
            $request->merge(['end' => $end->format('Y-m-d')]);
        }

        $statusCounts = Topup::searchVoucher($request)
        ->with('produk')
        ->where('tipe','voucher')
        ->latest()
        ->get()
        ->groupBy('status')
        ->map(function ($group) {
            return [
                'total' => $group->sum('harga_jual'),
                'total_buy' => $group->sum('harga_beli'),
                'count' => $group->count(),
            ];
        });
    
    $totalPending = $statusCounts['pending']['total'] ?? 0;
    $countPending = $statusCounts['pending']['count'] ?? 0;
    
    $totalSuccess = $statusCounts['sukses']['total'] ?? 0;
    $countSuccess = $statusCounts['sukses']['count'] ?? 0;
    
    $totalFailed = $statusCounts['gagal']['total'] ?? 0;
    $countFailed = $statusCounts['gagal']['count'] ?? 0;
    
    // Anda juga dapat menghitung total transaksi secara keseluruhan jika diperlukan
    $totalTransaction = $countPending + $countSuccess + $countFailed;
    
    // Jumlah total order adalah jumlah total entri dalam objek paginasi
    $totalOrder = $totalPending + $totalSuccess + $totalFailed;
    
    // Menghitung total profit
    $totalProfit = $totalSuccess - ($statusCounts['sukses']['total_buy'] ?? 0);
    
    $products = Produk::category('aktivasi-voucher')->orderBy('nama', 'asc')->get();
    $brands = Brand::category('aktivasi-voucher')->orderBy('nama', 'asc')->get();
    $types = Tipe::category('aktivasi-voucher')->orderBy('nama', 'asc')->get();
    $categories = Kategori::orderBy('nama', 'asc')->get();
    $suppliers = Supplier::orderBy('nama', 'asc')->get();
    $users = Pengguna::orderBy('nama', 'asc')->get();
    
    // Mendapatkan data dengan paginasi
    $vouchers = Topup::searchVoucher($request)
        ->with('produk')
        ->where('tipe','voucher')
        ->latest()
        ->paginate(config('app.pagination.default'));
     
        return view('web.pages.voucher.index', compact(
            'vouchers', 
            'totalTransaction', 
            'totalOrder', 
            'totalProfit', 
            'products', 
            'categories', 
            'suppliers', 
            'brands',
            'types',
            'users', 
            'totalPending', 
            'countPending', 
            'totalSuccess', 
            'countSuccess', 
            'totalFailed', 
            'countFailed',
            'start',
            'end'
        ));

    }

    public function cashier(Request $request)
    {
        return view('web.pages.voucher.cashier');
    }


    /**
     * Display a listing of the resource.
     */
    public function injector(Request $request)
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
        $vouchers = Topup::
        search($request)->
        with('produk')->
        latest()->
        where('tipe','voucher')->orderBy('created_at', 'desc')->paginate(config('app.pagination.default'));
        $products = Produk::category('aktivasi voucher')->orderBy('nama', 'asc')->get();
        return view('web.pages.voucher.injector', compact('vouchers', 'products', 'start', 'end'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = Kategori::where('nama', 'Aktivasi Voucher')->first();
        $brands = Brand::category($category->id)->get();
        return view('web.pages.inject.create', compact('brands', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}