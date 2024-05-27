<html>
   <head>
      <title>
         
      </title>
      <style>
         #tabel
         {
         font-size:15px;
         border-collapse:collapse;
         }
         #tabel  td
         {
         padding-left:5px;
         border: 1px solid black;
         }
          th, td {
            vertical-align: top;
        }
         @page { margin: 0 }
         body { margin: 0; font-family: monospace;}

         
 
      
      </style>
   </head>
   <body style='font-family:tahoma; font-size:8pt;'>
      <center>
        
         <table style='
          font-size:16pt; font-family:calibri; border-collapse: collapse;' border = '0'>
               
            <td align='CENTER' vertical-align:top'><span style='color:black;'>
              
                     <div style='display: flex; justify-content: center; align-items: center;'>
                        <img src="{{$websiteData->logo_website_url}}" width="30px" />
                     <b
                     style="font-size:16pt; margin-left: 10px; color:black;"
                     >
                        {{$websiteData->nama}}
                  </b>
            
                     </div>
                     <span style='font-size:12pt'>
                     {{$websiteData->alamat}}
                  </span></br>
                     <span style='font-size:12pt'>
                     Telepon : 
                     {{$websiteData->nomor_telepon}}
                     <br />

                     {{$topup->created_at->format('d M Y H:i:s')}}
                     <br />
                     Kasir: {{$topup->kasir->nama}}
                     </span></br>
                 
               </div>
            </td>
         </table>
         <style>
            hr { 
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 1px;
            } 
         </style>
         <hr style="width: 350px; color: black; height: 1px; background-color:black;">
         <b style='font-size:12pt'>
            Struk Pembelian Topup</b>
         <p style='font-size:10pt'> Harap simpan struk ini sebagai bukti pembayaran</p>


         <table cellspacing='0' cellpadding='0' style='font-size:12pt; font-family:calibri;  border-collapse: collapse;' border='0' class="nowrap">
         <tr >
            <td width='100px'>
               
               ID Transaksi</td>
            <td style="max-width: 150px;">: 
               {{$topup->id}}
            </td>
         </tr>
         <tr>
            <td width='100px'>Nomor</td>
            <td style="max-width: 150px;">: 
               {{$topup->nomor}}
            </td>
         </tr>
         @if ($topup->tipe == 'token_listrik')
         <tr>
            <td width='100px'>Nama Pelanggan</td>
            <td style="max-width: 150px;">: 
               {{$topup->token_listrik->nama_pelanggan}}
            </td>
         </tr>
         {{-- id_pelanggan --}}
         <tr>
            <td width='100px'>ID Pelanggan</td>
            <td style="max-width: 150px;">: 
               {{$topup->token_listrik->id_pelanggan}}
            </td>
         </tr>
         {{-- nomor meter --}}
         <tr>
            <td width='100px'>Nomor Meter</td>
            <td style="max-width: 150px;">: 
               {{$topup->token_listrik->nomor_meter}}
            </td>
         </tr>
         {{-- daya --}}
         <tr>
            <td width='100px'>Daya</td>
            <td style="max-width: 150px;">: 
               {{$topup->token_listrik->segment_power}}
            </td>
         </tr>
         @elseif($topup->tipe == 'e_wallet')
         <tr>
            <td width='100px'>Nama Pelanggan</td>
            <td style="max-width: 150px;">: 
               {{$topup->e_wallet->nama_pelanggan}}
            </td>
         </tr>
         @endif
         <tr>
            <td width='100px'>Produk</td>
            <td style="max-width: 150px;">: 
               {{$topup->produk->nama}}
            </td>
         </tr>
         <tr>
            <td width='100px'>Harga</td>
            <td>: 
               Rp. {{number_format($topup->harga_jual, 0, ',', '.')}}
            </td>
         </tr>
         <tr>
            <td width='100px'>Status</td>
            <td style="max-width: 150px;">: 
               {{Str::title($topup->status)}}
            </td>
         </tr>
         @if ($topup->tipe == 'token_listrik')
         <tr >
            <td width='100px'>Nomor Token</td>
            <td style="word-wrap: break-word; max-width: 150px;">
               : 
               <span 
                  style='font-size:15pt; color:rgb(43, 43, 43);
                  font-weight:bold;
                  '
                  class="nowrap"
                  >
                  @php 
                  $token = explode('/', $topup->keterangan);
                  @endphp
                  {{$token[0]}}
             
               </span>
            </td>
         </tr>
         @else
         <tr >
            <td width='100px'>Ket. / SN</td>
            <td style="word-wrap: break-word;max-width: 150px;">
               : 
               <span 
                  
                  class="nowrap"
                  >
                  {{$topup->keterangan}}
               </span>
            </td>
         </tr>
         @endif
       
         </table>
         <table style='font-size:12pt;' cellspacing='2'>
            <tr>
               </br>
               <td align='center'>****** TERIMAKASIH ******</br></td>
               
            </tr>
         </table>
      </center>
      <br />
      <br />
      <hr style="width: 350px; color: black; height: 1px; background-color:black;">
      <br />
   </body>
</html>