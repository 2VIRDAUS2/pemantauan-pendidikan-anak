<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="/update_anak/{{ $anak->id }}" method="POST">
        @csrf
        <label for="nama">Nama:</label>
        <input type="text" name="nama" id="nama" value="{{ $anak->nama }}">
        <br>
        <label for="pendidikan">Pendidikan:</label>
        <input type="text" name="pendidikan" id="pendidikan" value="{{ $anak->pendidikan }}">
        <br>
        <label for="umur">Umur:</label>
        <input type="number" name="umur" id="umur" value="{{ $anak->umur }}">
        <br>
        <label for="alamat">Alamat:</label>
        <input type="text" name="alamat" id="alamat" value="{{ $anak->alamat }}">
        <br>
        <button type="submit">Update</button>
    </form>
</body>
</html>