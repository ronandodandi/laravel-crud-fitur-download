<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
</head>
<body>
    <h1>Tambah Barang</h1>

    <form action="{{ route('barangs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Nama Barang:</label><br>
        <input type="text" name="nama" required><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi"></textarea><br><br>

        <label>Harga:</label><br>
        <input type="number" name="harga" step="0.01" required><br><br>

        <label>File (opsional):</label><br>
        <input type="file" name="file"><br><br>

        <button type="submit">Simpan</button>
    </form>

    <br>
    <a href="{{ route('barangs.index') }}">Kembali ke daftar</a>
</body>
</html>