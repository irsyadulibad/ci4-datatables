# Dokumentasi

## Inisialisasi
### Dengan Helper (Direkomendasikan)
```php
datatables('table');
```

### Dengan Class Name
```php
<?php
use Irsyadulibad\DataTables\DataTables;

DataTables::use('table');
?>
```

## Method yang Tersedia
Berikut adalah method yang tersedia untuk digunakan pada libray ini:
### Select Table
Pilih tabel yang ingin anda gunakan
```php
DataTables::use('table')
```

### Set Output
Parameter default adalah `false` yang otomatis akan mengembalikan data dalam notasi JSON. Anda dapat mengembalikan dump data dengan menempatkan `true` sebagai parameter.
```php
DataTables::use('table')
	->make(true);
```

### Select Fields
Memilih kolom spesifik pada tabel
```php
->select('username, password')
```

### Where Clause
```php
->where(['role' => 'user', 'active' => 1])
```

### orWhere Clause
```php
->orWhere(['role' => 'user', 'active' => 0])
```

### Join Clause
```php
// <table>, <condition>, <type>
->join('address', 'users.id = address.uid', 'INNER JOIN')
```

## Column editing
Anda dapat mengganti struktur kolom
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
