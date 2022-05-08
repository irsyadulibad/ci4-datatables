---
sidebar_position: 2
---
# Builder DataTable

## Pengenalan

`Builder datatable` lebih fleksibel dibandingkan `Query DataTable` sebelumnya. Anda dapat menggunakan semua builder yang telah codeigniter4 sediakan. [Baca dokumentasinya disini](http://codeigniter.com/user_guide/database/query_builder.html)

## Inisialisasi

### Helper Method (Recommended)
```php
$query = db_connect()->table('table');
return datatables($query)->make();
```

### Class Name Method
```php
use Irsyadulibad\DataTables\DataTables;

$query = db_connect()->table('table');
return DataTables::use($query)->make();
```

## Penggunaan
Anda dapat menggunakan query builder seperti biasa, lalu meneruskannya pada datatables

```php
$table = db_connect()->table('users');
$query = $table->select('users.*, addresses.name as address')
            ->where('users.role', 'admin')
            ->join('addresses', 'users.id = addresses.user_id');

return datatables($query)->make();
```

### Set Output
Parameter default adalah `false` yang otomatis akan mengembalikan data dalam notasi JSON. Anda dapat mengembalikan dump data dengan menempatkan `true` sebagai parameter.
```php
DataTables::use('table')
	->make(true);
```

## Column Editing
`Builder DataTable` juga dapat melakukan penyuntingan pada kolom sebelum dikeluarkan menjadi JSON

### Add Column
Menambah kolom yang tidak terdapat pada tabel
```php
// <name>, <callback>
->addColumn('action', function($data) {
	return '<a href="/edit/'.$data->id.'">edit</a>';
})
```

### Edit Column
```php
// <name>, <callback>
->editColumn('created_at', function($value) {
	return format($value);
})

// atau dengan argumen data pada parameter kedua
->editColumn('color', function($value, $data) {
	return "$value {$data->type}";
})
```

### Raw Columns
Secara default, semua data yang dikeluarkan akan diescape terlebih dahulu. Hal itu untuk mencegah serangan XSS. Tetapi anda tetap dapat melewatkannya dengan method berikut
```php
->rawColumns(['bio'])
```

### Hide Columns
Menghilangkan kolom dari output JSON
```php
->hideColumns(['password'])
```
