<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\{Topup, Brand, Category, Product, Supplier, Type};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class TopupController extends Controller
{

    public function index(Request $request)
    {   
        $user = Auth::guard()->user();

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

        ->with('product')
        ->latest()
        ->get()
        ->groupBy('status')
        ->map(function ($group) {
            return [
                'total' => $group->sum('price_sell'),
                'total_buy' => $group->sum('price_buy'),
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
    
    $products = Product::orderBy('name', 'asc')->get();
    $brands = Brand::orderBy('name', 'asc')->get();
    $types = Type::orderBy('name', 'asc')->get();
    $categories = Category::orderBy('name', 'asc')->get();
    $suppliers = Supplier::orderBy('name', 'asc')->get();
    $users = User::orderBy('name', 'asc')->get();
    
    // Mendapatkan data dengan paginasi
    $topups = Topup::search($request)
        ->where('transacted_at', '!=', null)
        ->with('product')

        ->orderby('transacted_at', 'desc')
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
        $topup = Topup::with('product')->findOrFail($id);
        return view('web.pages.topup.detail', compact('topup'));
    }


    public function cashier(Request $request)
    {
        $user = auth()->guard()->user();	
        if ($request->start && $request->end) {
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $request->start = $start->format('Y-m-d');
            $request->end = $end->format('Y-m-d');
        }
        $products = Product::orderBy('name', 'asc')->get();
        $queryTopup = Topup::search($request)
        ->where('transacted_at', '!=', null)
        ->where('cashier_id', $user->id);
       
        
        $dataTopup = $queryTopup->get()
        ->groupBy('status')
        ->map(function ($group) {
            return [
                'total' => $group->sum('price_sell'),
                'total_buy' => $group->sum('price_buy'),
                'count' => $group->count(),
            ];
        });

        $topups = $queryTopup->orderBy('transacted_at', 'desc')->with('product')
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
        $brands = Brand::select('id', 'name','logo')->category('aktivasi-voucher')
        ->orderBy('name', 'asc')->get();
       
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
