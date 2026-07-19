<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
   @foreach ($profil as $item)
   <ul>
        <a href="/edit_anak/{{ $item->id }}">Edit Data Ini</a>
       <li>Nama anak: {{ $item->nama }}</li>
       <li>Pendidikan anak: {{ $item->pendidikan }}</li>
       <li>Umur anak: {{ $item->umur }}</li>
       <li>Alamat anak: {{ $item->alamat }}</li>
       <a href="/hapus_anak/{{ $item->id }}">Hapus Data Ini</a>
   </ul>
    @endforeach
   <br>
   <a href="/tambah_anak">Tambah Data Anak</a>
</body>
</html>