<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/recta/dist/recta.js"></script>
<script type="text/javascript">
    var printer = new Recta('6591632140', '1811')
    @if($order->tipe == "token_listrik")
    printer.open().then(function () {
                printer.align('center')
                    .bold(true)
                    .text('Warung Teh Usi')
                    .bold(false)
                    .raw('\n')
                    .text('Jl Madesa GG Madesa III No 36')
                    .text('0895-2400-8323')
                    .text(new Date())
                    .raw('\n')
                    .bold(true)
                    .text('STRUK PEMBELIAN TOKEN PLN')
                    .raw('\n')
                    .bold(false)
                    .align('left')
                    .text('ID TRX: {{ $order->id }}')
                    .text('ID PELANGGAN: {{ $order->pesanan_token_listrik->id_pelanggan }}')
                    .text('NOMOR METER: {{ $order->pesanan_token_listrik->nomor_meter }}')
                    .text('NAMA: {{ $order->pesanan_token_listrik->nama_pelanggan }}')
                    .text('SEGMENT POWER: {{ $order->pesanan_token_listrik->segment_power }}')
                    .text('PRODUK: {{ $order->produk->nama }}')
                    .text('HARGA: Rp {{ number_format($order->harga, 0, ',', '.') }}')
                    .text('STATUS: {{ $order->status }}')
                    .raw('\n')
                    .align('center')
                    .text('Ket/SN: ')
                    .bold(true)
                    .text('{{ $order->keterangan }}')
                    .bold(false)
                    .raw('\n')
                    .text('TERIMA KASIH')
                    .text('Telah Berbelanja, Ditunggu Transaksi Berikutnya')
                    .cut()
                    .print();
        });
    @elseif($order->tipe == "e_wallet")
        printer.open().then(function () {
                printer.align('center')
                    .bold(true)
                    .text('Warung Teh Usi')
                    .bold(false)
                    .raw('\n')
                    .text('Jl Madesa GG Madesa III No 36')
                    .text('0895-2400-8323')
                    .text(new Date())
                    .raw('\n')
                    .bold(true)
                    .text('STRUK PEMBELIAN TOKEN PLN')
                    .raw('\n')
                    .bold(false)
                    .align('left')
                    .text('ID TRX: {{ $order->id }}')
                    .text('NOMOR: {{ $order->pesanan_token_listrik->id_pelanggan }}')
                    .text('NAMA: {{ $order->pesanan_token_listrik->nama_pelanggan }}')
                    .text('PRODUK: {{ $order->produk->nama }}')
                    .text('HARGA: Rp {{ number_format($order->harga, 0, ',', '.') }}')
                    .text('STATUS: {{ $order->status }}')
                    .raw('\n')
                    .align('center')
                    .text('Ket/SN: ')
                    .bold(true)
                    .text('{{ $order->keterangan }}')
                    .bold(false)
                    .raw('\n')
                    .text('TERIMA KASIH')
                    .text('Telah Berbelanja, Ditunggu Transaksi Berikutnya')
                    .cut()
                    .print();
        });
    @elseif($order->tipe == "seluler")
        printer.open().then(function () {
                printer.align('center')
                    .bold(true)
                    .text('Warung Teh Usi')
                    .bold(false)
                    .raw('\n')
                    .text('Jl Madesa GG Madesa III No 36')
                    .text('0895-2400-8323')
                    .text(new Date())
                    .raw('\n')
                    .bold(true)
                    .text('STRUK PEMBELIAN')
                    .raw('\n')
                    .bold(false)
                    .align('left')
                    .text(`ID TRX: {{ $order->id }}`)
                    .text(`NO HP: {{$order->target}}`)
                    .text(`PRODUK: {{$order->produk->nama}}`)
                    .text(`HARGA: Rp {{ number_format($order->harga, 0, ',', '.') }}`)
                    .text(`STATUS: {{$order->status}}`)
                    .raw('\n')
                    .align('left')
                    .text('Ket/SN: ')
                    .bold(true)
                    .text(`{{ $order->keterangan }}`)
                    .bold(false)
                    .raw('\n')
                    .align('center')
                    .text('TERIMA KASIH')
                    .text('Telah Berbelanja, Ditunggu Transaksi Berikutnya')
                    .cut()
                    .print();
            });
    @endif
</script>   
</body>
</html>