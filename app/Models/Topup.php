<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
use Carbon\Carbon;
class Topup extends Model
{
    use HasFactory;

    // table name
    protected $table = 'topup';

    // fillable attrib
    protected $fillable = [
        'produk_id',
        'kategori_id',
        'tipe_id',
        'brand_id',
        'kasir_id',
        'nomor',
        'keterangan',
        'harga_beli',
        'harga_jual',
        'whatsapp',
        'status',
        'tipe',
        'tgl_transaksi',
    ];
    
    // append attrib
    protected $appends = [
        'nomor_sensor',
        'nomor_sensor',
    ];


    // tgl_transaksi

    // nomor_sensor
    public function getNomorSensorAttribute()
    {
        return substr($this->nomor, 0, 4) . 'xxxx' . substr($this->nomor, -4);
    }

    // nomor pecah menjadi 0821-xxxx-xxxx
    public function getNomorPecahAttribute()
    {
        return substr($this->nomor, 0, 4) . '-' . substr($this->nomor, 4, 4) . '-' . substr($this->nomor, -4);
    }

    public function getStatusHtmlAttribute()
    {
        switch(strtolower($this->status)) {
        case 'pending':
            return '<span class="badge bg-warning-subtle text-warning rounded-pill">Pending</span>';
            break;
        case 'sukses':
            return '<span class="badge bg-success-subtle text-success rounded-pill">Sukses</span>';
            break;
        case 'gagal':	
            return '<span class="badge bg-danger-subtle text-danger rounded-pill">Gagal</span>';
            break;
        default :
            return '<span class="badge bg-warning-subtle text-warning rounded-pill">Pending</span>';
        }
    }

    public function getCustomerNameAttribute()
    {
       try {
            $customerName = '';
            if($this->tipe == "e_wallet") {
                $customerName = $this->e_wallet->nama_pelanggan;
            } else if ($this->tipe == "token_listrik") {
                $customerName = $this->token_listrik->nama_pelanggan;
            }
            return $customerName;
       } catch (\Throwable $th) {
            return "";
       }
    }

    public function getProductNameAttribute()
    {
        $productName = '';
        if($this->tipe_produk->nama == "Custom") {
            $productName = $this->produk->nama . " - Rp " . number_format($this->custom->nominal, 0, ',', '.');
        } else {
            $productName = $this->produk->nama;
        }
        // product_name
        $productName = Str::replaceFirst('Aktivasi ', '', $productName);
        return $productName;
    }
    


    public function getWhatsappMessageAttribute()
    {
        $message = "Halo, pelanggan *".config('app.name')."*.
Berikut adalah rincian topup Anda:
No: ".$this->id."
Nomor: ".$this->nomor."
Produk: ".$this->produk->nama."
Harga: Rp ".number_format($this->harga_jual, 0, ',', '.')."
Status: ".strtoupper($this->status)."
Waktu: ".$this->tgl_transaksi."
Keterangan: ".$this->keterangan."
Terima kasih telah berbelanja di *".config('app.name')."*.";

        if($this->tipe == "e_wallet") {
            $message = "Hallo, Pelanggan *".config('app.name')."*.
Berikut adalah rincian topup Anda:
No: ".$this->id."
Nomor: ".$this->nomor."
Nama Pelanggan: ".$this->e_wallet->nomor_pelanggan."
Produk: ".$this->produk->nama."
Harga: Rp ".number_format($this->harga_jual, 0, ',', '.')."
Status: ".strtoupper($this->status)."
Waktu: ".$this->tgl_transaksi."
Keterangan: ".$this->keterangan."
Terima kasih telah berbelanja di *".config('app.name')."*.";

        } else if ($this->tipe == "token_listrik") {
            $message = "Hallo, Pelanggan *".config('app.name')."*.
Berikut adalah rincian topup Anda:
No: ".$this->id."
Nomor: ".$this->nomor."
Nama Pelanggan: ".$this->token_listrik->nama_pelanggan."
Segmen Power: ".$this->token_listrik->segment_power."
Produk: ".$this->produk->nama."
Harga: Rp ".number_format($this->harga_jual, 0, ',', '.')."
Status: ".strtoupper($this->status)."
Waktu: ".$this->tgl_transaksi."
Nomor Token : ".Str::before($this->keterangan, "/")."
Terima kasih telah berbelanja di *".config('app.name')."*.";
        }
        return $message;
    }

    // scopeType
    public function scopeGetTopUpCountByRange($query, $start_date, $end_date, 
    $statuses =['pending', 'sukses'],
    $types = [], $user_id = null)
    {
        $start_date = new \DateTime($start_date);
        $end_date = new \DateTime($end_date);
        $topupCounts = [];

        for ($date = $start_date; $date <= $end_date; $date->modify('+1 day')) {
            $formatted_date = $date->format('Y-m-d');
            $topupCounts[$formatted_date] = Topup::
                whereDate('tgl_transaksi', $formatted_date)
                ->whereIn('status',$statuses)
                ->whereIn('tipe', $types)
                ->when($user_id, function ($query) use ($user_id) {
                    return $query->where('kasir_id', $user_id);
                })
                ->count();
        }

        return $topupCounts;
    }

    public function scopeGetTopupSumByRange($query, $start_date, $end_date,$statuses =['pending', 'sukses'], $types = [], $user_id = null)
    {
        $start_date = new \DateTime($start_date);
        $end_date = new \DateTime($end_date);
        $topupSums = [];

        for ($date = $start_date; $date <= $end_date; $date->modify('+1 day')) {
            $formatted_date = $date->format('Y-m-d');
            $topupSums[$formatted_date] = Topup::
                whereDate('tgl_transaksi', $formatted_date)
                ->whereIn('status',$statuses)
                ->whereIn('tipe', $types)
                ->when($user_id, function ($query) use ($user_id) {
                    return $query->where('kasir_id', $user_id);
                })
                ->sum('harga_jual');
        }

        return $topupSums;
    }

    public function scopeReportAdmin($query, $request)
    {
  
        // Common query base
        $queryBase = Topup::whereBetween('tgl_transaksi', [$request->start . ' 00:00:00', $request->end . ' 23:59:59'])
            ->whereIn('status', ['pending', 'sukses'])	
            ->where('tgl_transaksi', '!=', null);

        // Calculate total top-up sum
        $totalTopupSum = (clone $queryBase)->sum('harga_jual');

        // Calculate profit
        $totalHargaJual = (clone $queryBase)->sum('harga_jual');
        $totalHargaBeli = (clone $queryBase)->sum('harga_beli');
        $profit = $totalHargaJual - $totalHargaBeli;

        // Calculate count of top-ups
        $topupCount = (clone $queryBase)->count();

        return [
            'total_topup_sum' => $totalTopupSum,
            'profit' => $profit,
            'topup_count' => $topupCount,
        ];
    }

    public function scopeGetTopupSumByRange2($query, $start_date, $end_date, $statuses =['pending', 'sukses'], $types = [], $user_id = null)
    {
        $start_date = new \DateTime($start_date);
        $end_date = new \DateTime($end_date);
      
        $total = Topup::whereBetween('tgl_transaksi', [$start_date->format('Y-m-d').' 00:00:00', $end_date->format('Y-m-d').' 23:59:59'])
        ->whereIn('status',$statuses)
        ->whereIn('tipe', $types)
        ->when($user_id, function ($query) use ($user_id) {
            return $query->where('kasir_id', $user_id);
        })
        ->sum('harga_jual');
        return $total;
    }

    public function scopeGetProfitByRange($query, $start_date, $end_date, $statuses =['pending', 'sukses'], $types = [])
    {
        $start_date = new \DateTime($start_date);
        $end_date = new \DateTime($end_date);
       
        $harga_jual = Topup::whereBetween('tgl_transaksi', [$start_date->format('Y-m-d').' 00:00:00', $end_date->format('Y-m-d').' 23:59:59'])
        ->whereIn('status',$statuses)
        ->whereIn('tipe', $types)
        ->sum('harga_jual');

        $harga_beli = Topup::whereBetween('tgl_transaksi', [$start_date->format('Y-m-d').' 00:00:00', $end_date->format('Y-m-d').' 23:59:59'])
        ->whereIn('status',$statuses)
        ->whereIn('tipe', $types)
        ->sum('harga_beli');
        
        return $harga_jual - $harga_beli;
    }

    public function scopeGetCountByRange($query, $start_date, $end_date, $statuses =['pending', 'sukses'], $types = [], $user_id = null)
    {
        $start_date = new \DateTime($start_date);
        $end_date = new \DateTime($end_date);
        $topupCounts = Topup::whereBetween('tgl_transaksi', [$start_date->format('Y-m-d').' 00:00:00', $end_date->format('Y-m-d').' 23:59:59'])
        ->whereIn('status',$statuses)
        ->whereIn('tipe', $types)
        ->when($user_id, function ($query) use ($user_id) {
            return $query->where('kasir_id', $user_id);
        })
        ->count();
        
        return $topupCounts;
    }

    public function scopeGetReport($query, $request)
    {
        if ($request->start && $request->end) {
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        }
        
        while ($start <= $end) {
            $date = $start->format('Y-m-d');
            $topupSukses = Topup::whereDate('tgl_transaksi', $date)
                ->where('status', 'sukses');
            $topupPending = Topup::whereDate('tgl_transaksi', $date)
                ->where('status', 'pending');
            $topupGagal = Topup::whereDate('tgl_transaksi', $date)
                ->where('status', 'gagal');

            if ($request->kasir_id) {
                $topupSukses->where('kasir_id', $request->kasir_id);
                $topupPending->where('kasir_id', $request->kasir_id);
                $topupGagal->where('kasir_id', $request->kasir_id);
            }

            $report[$date] = [
                'sukses' => $topupSukses->sum('harga_jual'),
                'total_sukses' => $topupSukses->count(),
                'pending' => $topupPending->sum('harga_jual'),
                'total_pending' => $topupPending->count(),
                'gagal' => $topupGagal->sum('harga_jual'),
                'total_gagal' => $topupGagal->count(),
            ];
            $start->addDay();

        }

        return $report;

    }

    public function scopeReportInject($query, $request)
    {
        if ($request->start && $request->end) {
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        }
        
        $report = [];
        
        while ($start <= $end) {
            $date = $start->format('Y-m-d');
        
            $topupSuksesQuery = Topup::whereDate('created_at', $date)
                ->where('tipe', 'voucher')
                ->where('status', 'sukses');
        
            $topupPendingQuery = Topup::whereDate('created_at', $date)
                ->where('tipe', 'voucher')
                ->where('status', 'pending');
        
            $topupGagalQuery = Topup::whereDate('created_at', $date)
                ->where('tipe', 'voucher')
                ->where('status', 'gagal');
        
            if ($request->user_id) {
                $topupSuksesQuery->whereHas('topup_api', function ($query) use ($request) {
                    $query->where('pengguna_id', $request->user_id);
                });
                $topupPendingQuery->whereHas('topup_api', function ($query) use ($request) {
                    $query->where('pengguna_id', $request->user_id);
                });
                $topupGagalQuery->whereHas('topup_api', function ($query) use ($request) {
                    $query->where('pengguna_id', $request->user_id);
                });
            }
        
            $report[$date] = [
                'sukses' => $topupSuksesQuery->sum('harga_beli'),
                'total_sukses' => $topupSuksesQuery->count(),
                'pending' => $topupPendingQuery->sum('harga_beli'),
                'total_pending' => $topupPendingQuery->count(),
                'gagal' => $topupGagalQuery->sum('harga_beli'),
                'total_gagal' => $topupGagalQuery->count(),
            ];
        
            $start->addDay();
        }
        
        return $report;
        
    }

    public function scopeGetReportAdmin($query, $request)
    {

    }

    // relationship
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function kasir()
    {
        return $this->belongsTo(Pengguna::class, 'kasir_id', 'id');
    }

    public function tipe_produk()
    {
        return $this->belongsTo(Tipe::class, 'tipe_id', 'id');
    }

    // topup_api
    public function topup_api()
    {
        return $this->hasMany(TopupApi::class);
    }

    // topup_e_wallet
    public function e_wallet()
    {
        return $this->hasOne(TopupEWallet::class);
    }

    // topup_token_listrik
    public function token_listrik()
    {
        return $this->hasOne(TopupTokenListrik::class);
    }
    
    // topup_voucher
    public function voucher()
    {
        return $this->hasOne(TopupVoucher::class);
    }

    // supplier
    public function getSupplierNameAttribute()
    {
        $nama = '';
        if($this->topup_api->where('status', 'sukses')->first()) {
            $nama = $this->topup_api->where('status', 'sukses')->first()->supplier->nama;
        }
        return $nama;
    }

    // searchGuest
    public function scopeSearchGuest($query, $request)
    {
        // start dan end 
        if ($request->start && $request->end) {
            $query->whereBetween('tgl_transaksi', [$request->start . ' 00:00:00', $request->end . ' 23:59:59']);
        }
        //  kategori
        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }
        // produk
        if ($request->produk) {
            $query->where('produk_id', $request->produk);
        }
        // status
        if ($request->status) {
            $query->where('status', $request->status);
        }
        // kasir =
        if ($request->kasir) {
            $query->where('kasir_id', $request->kasir);
        }
        // supplier
        if ($request->supplier) {
            // didapat dari relasi dengan topup_api
            $query->whereHas('topup_api', function ($query) use ($request) {
                $query->where('supplier_id', $request->supplier);
            });
        }
        // search
        if ($request->search) {
            $query->where('nomor', 'like', '%' . $request->search . '%')->
            orWhereHas('produk', function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->search . '%');
            });
        }
        return $query;
    }

    // search
    public function scopeSearch($query, $request)
    {
       // start dan end 
       if ($request->start && $request->end) {
            if($request->filter_date == "created_at") {
                $query->whereBetween('created_at', [$request->start . ' 00:00:00', $request->end . ' 23:59:59']);
            } else if ($request->filter_date == "tgl_kadaluwarsa") {
                $query->whereHas('voucher', function ($query) use ($request) {
                    $query->whereBetween('tgl_kadaluarsa', [$request->start . ' 00:00:00', $request->end . ' 23:59:59']);
                });
            } else {
                $query->whereBetween('tgl_transaksi', [$request->start . ' 00:00:00', $request->end . ' 23:59:59']);
            }
        }
        //  kategori
        if ($request->kategori and $request->kategori != 'Semua') {
            $query->where('kategori_id', $request->kategori);
        }
        // produk
        if ($request->produk and $request->produk != 'Semua') {
            $query->where('produk_id', $request->produk);
        }
        // brand
        if ($request->brand and $request->brand != 'Semua') {
            $query->where('brand_id', $request->brand);
        }
        // tipe
        if ($request->tipe and $request->tipe != 'Semua') {
            $query->where('tipe_id', $request->tipe);
        }
        // status
        if ($request->status and $request->status != 'Semua') {
            $query->where('status', $request->status);
        }
        // kasir =
        if ($request->kasir and $request->kasir != 'Semua') {
            $query->where('kasir_id', $request->kasir);
        }
        // supplier
        if ($request->supplier and $request->supplier != 'Semua') {
            // didapat dari relasi dengan topup_api
            $query->whereHas('topup_api', function ($query) use ($request) {
                $query->where([
                    ['supplier_id', $request->supplier],
                    ['status', 'sukses']
                ]);
            });
        }

        // activator relasi dengan topup_api dan statusnya sukses
        if ($request->aktivator and $request->aktivator != 'Semua') {
            // didapat dari relasi dengan topup_api
            $query->whereHas('topup_api', function ($query) use ($request) {
                $query->where([
                    ['user_id', $request->aktivator],
                    ['status', 'sukses']
                ]);
            });
        }
        // search
        if ($request->search) {
            $query->where('nomor', 'like', '%' . $request->search . '%')->
                orWhereHas('produk', function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->search . '%');
            })->orWhereHas('kasir', function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->search . '%');
            })->orWhereHas('brand', function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->search . '%');
            })->orWhereHas('kategori', function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->search . '%');
            })->orWhereHas('topup_api', function ($query) use ($request) {
                $query->where('supplier_id', $request->search);
            });
        }

      

        return $query;
    }
      public function scopeSearchVoucher($query, $request)
    {
       
       // start dan end 
        if ($request->start && $request->end) {
            $query->whereBetween('created_at', [$request->start . ' 00:00:00', $request->end . ' 23:59:59']);
           
        }
        //  kategori
        if ($request->kategori and $request->kategori != 'Semua') {
            $query->where('kategori_id', $request->kategori);
        }
        // produk
        if ($request->produk and $request->produk != 'Semua') {
            $query->where('produk_id', $request->produk);
        }
        // brand
        if ($request->brand and $request->brand != 'Semua') {
            $query->where('brand_id', $request->brand);
        }
        // tipe
        if ($request->tipe and $request->tipe != 'Semua') {
            $query->where('tipe_id', $request->tipe);
        }
        // status
        if ($request->status and $request->status != 'Semua') {
            $query->where('status', $request->status);
        }
        // kasir =
        if ($request->kasir and $request->kasir != 'Semua') {
            $query->where('kasir_id', $request->kasir);
        }
        // supplier
        if ($request->supplier and $request->supplier != 'Semua') {
            // didapat dari relasi dengan topup_api
            $query->whereHas('topup_api', function ($query) use ($request) {
                $query->where([
                    ['supplier_id', $request->supplier],
                    ['status', 'sukses']
                ]);
            });
        }

        // activator relasi dengan topup_api dan statusnya sukses
        if ($request->aktivator and $request->aktivator != 'Semua') {
            // didapat dari relasi dengan topup_api
            $query->whereHas('topup_api', function ($query) use ($request) {
                $query->where([
                    ['user_id', $request->aktivator],
                    ['status', 'sukses']
                ]);
            });
        }
        // search
        if ($request->search) {
            $query->where('nomor', 'like', '%' . $request->search . '%')->
                orWhereHas('produk', function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->search . '%');
            })->orWhereHas('kasir', function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->search . '%');
            })->orWhereHas('brand', function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->search . '%');
            })->orWhereHas('kategori', function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->search . '%');
            })->orWhereHas('topup_api', function ($query) use ($request) {
                $query->where('supplier_id', $request->search);
            });
        }
        return $query;
    }

    public function scopeGetProductVoucher($query, $request)
    {
        return $query
        ->whereHas('produk', function ($query) use ($request) {
            $query->brand($request->brand);
        })
        ->whereHas('voucher', function ($query) {
            $query->where('tgl_kadaluarsa', '>', now());
        })
        ->where('tgl_transaksi', '=', null)
        ->orderBy('harga_jual', 'desc');
    }
    
    
}
