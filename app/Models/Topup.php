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
    protected $table = 'topups';

    // fillable attrib
    protected $fillable = [
        'product_id',
        'category_id',
        'type_id',
        'brand_id',
        'cashier_id',
        'target',
        'note',
        'price_buy',
        'price_sell',
        'whatsapp',
        'status',
        'type',
        'transacted_at',
    ];
    
    public function getCustomerNameAttribute()
    {
       try {
            $customerName = '';
            if($this->type == "e_wallet") {
                $customerName = $this->e_wallet->customer_name;
            } else if ($this->type == "token_listrik") {
                $customerName = $this->token_listrik->customer_name;
            }
            return $customerName;
       } catch (\Throwable $th) {
            return "";
       }
    }

    
    public function getWhatsappMessageAttribute()
    {
        $message = "Halo, pelanggan *".config('app.name')."*.
Berikut adalah rincian topup Anda:
No: ".$this->id."
Nomor: ".$this->target."
Produk: ".$this->product->name."
Harga: Rp ".number_format($this->price_sell, 0, ',', '.')."
Status: ".strtoupper($this->status)."
Waktu: ".$this->transacted_at."
Keterangan: ".$this->note."
Terima kasih telah berbelanja di *".config('app.name')."*.";

        if($this->type == "e_wallet") {
            $message = "Hallo, Pelanggan *".config('app.name')."*.
Berikut adalah rincian topup Anda:
No: ".$this->id."
Nomor: ".$this->target."
Nama Pelanggan: ".$this->e_wallet->customer_name."
Produk: ".$this->product->name."
Harga: Rp ".number_format($this->price_sell, 0, ',', '.')."
Status: ".strtoupper($this->status)."
Waktu: ".$this->transacted_at."
Keterangan: ".$this->note."
Terima kasih telah berbelanja di *".config('app.name')."*.";

        } else if ($this->type == "token_listrik") {
            $message = "Hallo, Pelanggan *".config('app.name')."*.
Berikut adalah rincian topup Anda:
No: ".$this->id."
Nomor: ".$this->target."
Nama Pelanggan: ".$this->token_listrik->customer_name."
Segmen Power: ".$this->token_listrik->segment_power."
Produk: ".$this->product->name."
Harga: Rp ".number_format($this->price_sell, 0, ',', '.')."
Status: ".strtoupper($this->status)."
Waktu: ".$this->transacted_at."
Nomor Token : ".Str::before($this->note, "/")."
Terima kasih telah berbelanja di *".config('app.name')."*.";
        }
        return $message;
    }
      // search
      public function scopeSearch($query, $request)
      {
         // start dan end 
         if ($request->start && $request->end) {
              if($request->filter_date == "created_at") {
                  $query->whereBetween('created_at', [$request->start . ' 00:00:00', $request->end . ' 23:59:59']);
              } else if ($request->filter_date == "expired_at") {
                  $query->whereHas('voucher', function ($query) use ($request) {
                      $query->whereBetween('expired_at', [$request->start . ' 00:00:00', $request->end . ' 23:59:59']);
                  });
              } else {
                  $query->whereBetween('transacted_at', [$request->start . ' 00:00:00', $request->end . ' 23:59:59']);
              }
          }
          //  category
          if ($request->category and $request->category != 'Semua') {
              $query->where('category_id', $request->category);
          }
          // product
          if ($request->product and $request->product != 'Semua') {
              $query->where('product_id', $request->product);
          }
          // brand
          if ($request->brand and $request->brand != 'Semua') {
              $query->where('brand_id', $request->brand);
          }
          // type
          if ($request->type and $request->type != 'Semua') {
              $query->where('type_id', $request->type);
          }
          // status
          if ($request->status and $request->status != 'Semua') {
              $query->where('status', $request->status);
          }
          // cashier =
          if ($request->cashier and $request->cashier != 'Semua') {
              $query->where('cashier_id', $request->cashier);
          }
          // supplier
          if ($request->supplier and $request->supplier != 'Semua') {
              $query->whereHas('topup_api', function ($query) use ($request) {
                  $query->where([
                      ['supplier_id', $request->supplier],
                      ['status', 'sukses']
                  ]);
              });
          }
  
          if ($request->aktivator and $request->aktivator != 'Semua') {
              $query->whereHas('topup_api', function ($query) use ($request) {
                  $query->where([
                      ['user_id', $request->aktivator],
                      ['status', 'sukses']
                  ]);
              });
          }
          if ($request->search) {
              $query->where('target', 'like', '%' . $request->search . '%')->
                  orWhereHas('product', function ($query) use ($request) {
                  $query->where('name', 'like', '%' . $request->search . '%');
              })->orWhereHas('cashier', function ($query) use ($request) {
                  $query->where('name', 'like', '%' . $request->search . '%');
              })->orWhereHas('brand', function ($query) use ($request) {
                  $query->where('name', 'like', '%' . $request->search . '%');
              })->orWhereHas('category', function ($query) use ($request) {
                  $query->where('name', 'like', '%' . $request->search . '%');
              })->orWhereHas('topup_api', function ($query) use ($request) {
                  $query->where('supplier_id', $request->search);
              });
          }
  
        
  
          return $query;
      }
      public function scopeSearchVoucher($query, $request)
      {
         
          if ($request->start && $request->end) {
              $query->whereBetween('created_at', [$request->start . ' 00:00:00', $request->end . ' 23:59:59']);
             
          }
          if ($request->category and $request->category != 'Semua') {
              $query->where('category_id', $request->category);
          }
          if ($request->product and $request->product != 'Semua') {
              $query->where('product_id', $request->product);
          }
          if ($request->brand and $request->brand != 'Semua') {
              $query->where('brand_id', $request->brand);
          }
          if ($request->type and $request->type != 'Semua') {
              $query->where('type_id', $request->type);
          }
          if ($request->status and $request->status != 'Semua') {
              $query->where('status', $request->status);
          }
          if ($request->cashier and $request->cashier != 'Semua') {
              $query->where('cashier_id', $request->cashier);
          }
          if ($request->supplier and $request->supplier != 'Semua') {
              $query->whereHas('topup_api', function ($query) use ($request) {
                  $query->where([
                      ['supplier_id', $request->supplier],
                      ['status', 'sukses']
                  ]);
              });
          }
  
          if ($request->aktivator and $request->aktivator != 'Semua') {
              $query->whereHas('topup_api', function ($query) use ($request) {
                  $query->where([
                      ['user_id', $request->aktivator],
                      ['status', 'sukses']
                  ]);
              });
          }
          // search
          if ($request->search) {
              $query->where('target', 'like', '%' . $request->search . '%')->
                  orWhereHas('product', function ($query) use ($request) {
                  $query->where('name', 'like', '%' . $request->search . '%');
              })->orWhereHas('cashier', function ($query) use ($request) {
                  $query->where('name', 'like', '%' . $request->search . '%');
              })->orWhereHas('brand', function ($query) use ($request) {
                  $query->where('name', 'like', '%' . $request->search . '%');
              })->orWhereHas('category', function ($query) use ($request) {
                  $query->where('name', 'like', '%' . $request->search . '%');
              })->orWhereHas('topup_api', function ($query) use ($request) {
                  $query->where('supplier_id', $request->search);
              });
          }
          return $query;
      }
  
      public function scopeGetProductVoucher($query, $request)
      {
          return $query
          ->whereHas('product', function ($query) use ($request) {
              $query->brand($request->brand);
          })
          ->whereHas('voucher', function ($query) {
              $query->where('expired_at', '>', now());
          })
          ->where('transacted_at', '=', null)
          ->where('status', 'sukses')
          ->orderBy('price_sell', 'desc');
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
            $topupSukses = Topup::whereDate('transacted_at', $date)
                ->where('status', 'sukses');
            $topupPending = Topup::whereDate('transacted_at', $date)
                ->where('status', 'pending');
            $topupGagal = Topup::whereDate('transacted_at', $date)
                ->where('status', 'gagal');

            if ($request->cashier_id) {
                $topupSukses->where('cashier_id', $request->cashier_id);
                $topupPending->where('cashier_id', $request->cashier_id);
                $topupGagal->where('cashier_id', $request->cashier_id);
            }

            $report[$date] = [
                'sukses' => $topupSukses->sum('price_sell'),
                'total_sukses' => $topupSukses->count(),
                'pending' => $topupPending->sum('price_sell'),
                'total_pending' => $topupPending->count(),
                'gagal' => $topupGagal->sum('price_sell'),
                'total_gagal' => $topupGagal->count(),
            ];
            $start->addDay();

        }

        return $report;

    }

    public function scopeGetReportInject($query, $request)
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
                ->where('type', 'voucher')
                ->where('status', 'sukses');
        
            $topupPendingQuery = Topup::whereDate('created_at', $date)
                ->where('type', 'voucher')
                ->where('status', 'pending');
        
            $topupGagalQuery = Topup::whereDate('created_at', $date)
                ->where('type', 'voucher')
                ->where('status', 'gagal');
        
            if ($request->user_id) {
                $topupSuksesQuery->whereHas('topup_api', function ($query) use ($request) {
                    $query->where('user_id', $request->user_id);
                });
                $topupPendingQuery->whereHas('topup_api', function ($query) use ($request) {
                    $query->where('user_id', $request->user_id);
                });
                $topupGagalQuery->whereHas('topup_api', function ($query) use ($request) {
                    $query->where('user_id', $request->user_id);
                });
            }
        
            $report[$date] = [
                'sukses' => $topupSuksesQuery->sum('price_buy'),
                'total_sukses' => $topupSuksesQuery->count(),
                'pending' => $topupPendingQuery->sum('price_buy'),
                'total_pending' => $topupPendingQuery->count(),
                'gagal' => $topupGagalQuery->sum('price_buy'),
                'total_gagal' => $topupGagalQuery->count(),
            ];
        
            $start->addDay();
        }
        
        return $report;
        
    }

    public function scopeGetReportAdmin($query, $request)
    {
          // Common query base
          $queryBase = Topup::whereBetween('transacted_at', [$request->start . ' 00:00:00', $request->end . ' 23:59:59'])
          ->whereIn('status', [ 'sukses'])	
          ->where('transacted_at', '!=', null);

      // Calculate total top-up sum
      $totalTopupSum = (clone $queryBase)->sum('price_sell');

      // Calculate profit
      $totalHargaJual = (clone $queryBase)->sum('price_sell');
      $totalHargaBeli = (clone $queryBase)->sum('price_buy');
      $profit = $totalHargaJual - $totalHargaBeli;

      // Calculate count of top-ups
      $topupCount = (clone $queryBase)->count();

      return [
          'total_topup_sum' => $totalTopupSum,
          'profit' => $profit,
          'topup_count' => $topupCount,
      ];
    }

    // relationship
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id', 'id');
    }

    public function type_product()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
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
        $name = '';
        if($this->topup_api->where('status', 'sukses')->first()) {
            $name = $this->topup_api->where('status', 'sukses')->first()->supplier->name;
        }
        return $name;
    }
 
}
