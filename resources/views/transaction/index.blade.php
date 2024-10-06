<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <h3 class="text-center my-4">Tutorial Laravel 11</h3>
                    <hr>
                </div>
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <a href="{{ route('transaction.create') }}" class="btn btn-md btn-success mb-3">ADD TRANSACTION</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">TANGGAL TRANSAKSI</th>
                                    <th scope="col">NAMA KASIR</th>
                                    <th scope="col">NAMA PRODUK</th>
                                    <th scope="col">KATEGORI PRODUK</th>
                                    <th scope="col">HARGA</th>
                                    <th scope="col">JUMLAH</th>
                                    <th scope="col">TOTAL HARGA</th>
                                    <th scope="col" style="width: 20%">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaction as $transaksi)
                                    <tr>
                                        <td>{{ $transaksi->created_at }}</td>
                                        <td>{{ $transaksi->nama_kasir }}</td>
                                        <td>{{ $transaksi->title }}</td>
                                        <td>{{ $transaksi->product_category_name }}</td>
                                        <td>{{ "Rp " . number_format($transaksi->price,2,',','.') }}</td>
                                        <td>{{ $transaksi->jumlah }}</td>
                                        <td>{{ "Rp " . number_format($transaksi->total_harga,2,',','.') }}</td>
                                        <td class="text-center">
                                            <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('transaction.destroy', $transaksi->id) }}" method="POST">
                                                <a href="{{ route('transaction.show', $transaksi->id) }}" class="btn btn-sm btn-dark">SHOW</a>
                                                <a href="{{ route('transaction.edit', $transaksi->id) }}" class="btn btn-sm btn-primary">EDIT</a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <div class="alert alert-danger">
                                        Data Transaction belum Tersedia.
                                    </div>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $transaction->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        //message with sweetalert
        @if(session('success'))
            Swal.fire({
                icon: "success",
                title: "BERHASIL",
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @elseif(session('error'))
            Swal.fire({
                icon: "error",
                title: "GAGAL!",
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @endif

    </script>

</body>
</html>