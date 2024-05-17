<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\{Topup, Brand, Kategori, Produk, Supplier, Tipe};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class TopupController extends Controller
{

    public function index(Request $request)
    {   
        $user = Auth::guard('pengguna')->user();

        if ($request->start && $request->end) {
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $request->start = $start->format('Y-m-d');
            $request->end = $end->format('Y-m-d');
        }

        $statusCounts = Topup::search($request)

        ->with('produk')
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
    
    $products = Produk::orderBy('nama', 'asc')->get();
    $brands = Brand::orderBy('nama', 'asc')->get();
    $types = Tipe::orderBy('nama', 'asc')->get();
    $categories = Kategori::orderBy('nama', 'asc')->get();
    $suppliers = Supplier::orderBy('nama', 'asc')->get();
    $users = Pengguna::orderBy('nama', 'asc')->get();
    
    // Mendapatkan data dengan paginasi
    $topups = Topup::search($request)
        ->where('tgl_transaksi', '!=', null)
        ->with('produk')

        ->orderby('tgl_transaksi', 'desc')
        ->paginate(config('app.pagination.default'));

        return view('web.pages.topup.index', compact(
            'topups', 
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

    public function create(Request $request, $category, $brand = null)
    {

       $icon = 'ri-smartphone-line';
       $type_customer = "nomor_telepon";
       switch (Str::slug($category)) {
            case 'pulsa':
                $icon = 'ri-smartphone-line';
                return view('web.pages.topup.seluler', compact('category', 'icon'));
            case 'data':
                $icon = 'ri-wifi-line';
                return view('web.pages.topup.seluler', compact('category', 'icon'));
            case 'paket-sms-telpon':
                $icon = 'ri-phone-line';
                return view('web.pages.topup.seluler', compact('category', 'icon'));
            case 'masa-aktif':
                $icon = 'ri-health-book-line';
                return view('web.pages.topup.seluler', compact('category', 'icon',));
            case 'pln':
                return view('web.pages.topup.pln', compact('category', 'icon'));
            case 'e-money':
                $icon = 'ri-wallet-3-line';
                if($brand) {
                    return view('web.pages.topup.e-wallet-detail', compact('category', 'brand', 'icon'));
                } else {
                    return view('web.pages.topup.e-wallet', compact('category', 'icon'));
                }
            default:
            return abort(404);
       }
    }

    public function detail(Request $request, $id)
    {
        $topup = Topup::with('produk')->findOrFail($id);
        return view('web.pages.topup.detail', compact('topup'));
    }



    // store
    public function store(Request $request)
    {
        $this->validate($request, [
           'produk_id' => 'required|exists:produk,id',
           'nomor' => 'required|numeric',
        ]);

        try {
            $user = Auth::guard('pengguna')->user();
            $pengguna = Pengguna::where('user_id', $user->id)->first();
            $topup = $pengguna->topup()->create([
                'produk_id' => $request->produk_id,
                'nomor' => $request->nomor,
                'status' => 'pending',
            ]);

            return redirect()->route('topup.show', $topup->id);
        } catch (\Throwable $th) {
            //throw $th;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function cashier(Request $request)
    {
        $user = auth()->guard('pengguna')->user();	
        if ($request->start && $request->end) {
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $request->start = $start->format('Y-m-d');
            $request->end = $end->format('Y-m-d');
        }
        $products = Produk::orderBy('nama', 'asc')->get();
        $queryTopup = Topup::search($request)
        ->where('tgl_transaksi', '!=', null)
        ->where('kasir_id', $user->id);
       
        
        $dataTopup = $queryTopup->get()
        ->groupBy('status')
        ->map(function ($group) {
            return [
                'total' => $group->sum('harga_jual'),
                'total_buy' => $group->sum('harga_beli'),
                'count' => $group->count(),
            ];
        });

        $topups = $queryTopup->orderBy('tgl_transaksi', 'desc')->with('produk')
        ->paginate(config('app.pagination.default'));


        $totalPending = $dataTopup['pending']['total'] ?? 0;
        $countPending = $dataTopup['pending']['count'] ?? 0;
        $totalSuccess = $dataTopup['sukses']['total'] ?? 0;
        $countSuccess = $dataTopup['sukses']['count'] ?? 0;
        $totalFailed = $dataTopup['gagal']['total'] ?? 0;
        $countFailed = $dataTopup['gagal']['count'] ?? 0;
        
        $totalTransaction = $countPending + $countSuccess + $countFailed;
        $totalOrder = $totalPending + $totalSuccess + $totalFailed;
        
        return view('web.pages.topup.cashier', compact('topups',
        'totalTransaction',
        'totalOrder',
        'products',
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

    public function voucher(Request $request)
    {

        return view('web.pages.topup.voucher');
    }

    public function voucherCheck(Request $request)
    {
        $brands = Brand::select('id', 'nama', 'slug','logo')->category('aktivasi-voucher')
        ->orderBy('nama', 'asc')->get();
       
        return view('web.pages.topup.voucher-check', compact('brands'));
    }


    public function voucherBrand(Request $request, $brand)
    {
        return view('web.pages.topup.voucher-list', compact('brand'));
    }

    public function print(Topup $topup)
    {
        return view('print.invoice', compact('topup'));
    }
}
