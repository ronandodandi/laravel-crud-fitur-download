<!DOCTYPE html>
<html lang="id">
<html>
    <head>
        <meta charset="UTF-8">
        <title>Data Barang</title>
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    </head>

    <body>
        <div class="container">
        <h1>Data Barang</h1>
        <div class="toolbar">
            <a href="{{ route('barangs.create') }}" class="btn">Tambah Barang</a>
            <a href="{{ route('barangs.export') }}" class="btn">Download Data</a>
        </div>

        <table>
            <tr>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th>File</th>
                <th>Aksi</th>
            </tr>
            @forelse ($barangs as $barang)
            <tr>
                <td>{{ $barang->nama }}</td>
                <td>Rp {{ number_format ($barang->harga, 0, ',', '.') }}</td>
                <td>{{ $barang->deskripsi }}</td>
                <td>
                    @if ($barang->file)
                        @php
                            $ext = pathinfo($barang->file, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        @endphp

                        @if ($isImage)
                            <img src="{{ asset('storage/' . $barang->file) }}" width="60"><br>
                        @else
                        {{ $barang->file}}
                        @endif
                    @else
                        -
                    @endif   
                </td>
                <td>
                    <a href="{{ route('barangs.edit', $barang->id) }}" class="btn secondary">Edit</a>
                    <form action="{{ route('barangs.destroy', $barang->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                    <button type="submit" class="btn secondary" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5"> Belum ada data</td>
            </tr>
            @endforelse
        </table>
        </div>
    </body>
</html>
