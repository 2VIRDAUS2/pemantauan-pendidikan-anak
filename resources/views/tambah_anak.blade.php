<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="/simpan_anak" method="POST">
    @csrf <label>Nama anak:</label>
    <input type="text" name="nama">
    <br>
    <label>Pendidikan anak:</label>
    <input type="text" name="pendidikan">
    <br>
    <label>Umur anak:</label>
    <input type="text" name="umur">
    <br>
    <label>Alamat anak:</label>
    <input type="text" name="alamat">
    <br>
    <button type="submit">Kirim Data</button>
</form>
</body>
</html>