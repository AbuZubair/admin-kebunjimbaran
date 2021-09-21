<p>Terima kasih, pesanan akan dikirim pada tanggal {{ $data->delivery }} </p>
<p>Nama: {{ $data->fullname }}</p>
<p>No. Telepon: {{ $data->phone }}</p>
<p>Alamat: {{ $data->address }}</p>
<p>Order No: {{ $data->order_no }}</p>
<p>Total harga: Rp {{ $data->amount }}</p>
<p>Detail order dapat dilihat di: <a href="{{$data->link}}">link</a></p>

<p>Best Regards,</p>