---
sidebar_position: 2
---
# Builder DataTable

## Introduction
The `Builder DataTable` is more flexible than the previous `Query DataTable`. You can use all the builders that codeigniter4 has provided. [Read the docs here](http://codeigniter.com/user_guide/database/query_builder.html)

`Builder datatable` lebih fleksibel dibandingkan `Query DataTable` sebelumnya. Anda dapat menggunakan semua builder yang telah codeigniter4 sediakan. [Baca dokumentasinya disini](http://codeigniter.com/user_guide/database/query_builder.html)

## Initialization

### Helper Method
Recommended for shortest syntax
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

## Usage
You can use the query builder as usual, then pass it on the `datatables`
Anda dapat menggunakan pembuat kueri seperti biasa, lalu meneruskannya pada datatables

```php
$table = db_connect()->table('users');
$query = $table->select('users.*, addresses.name as address')
            ->where('users.role', 'admin')
            ->join('addresses', 'users.id = addresses.user_id');

return datatables($query)->make();
```

### Set Output
The default parameter is false, which is automatically return the JSON data. You can return the data's dump by passing the **true** param
```php
DataTables::use('table')
	->make(true);
```

## Column Editing
The `Builder DataTable` can also make edits to the columns before they are output to JSON

### Add Column
Add custom column which is not in the table
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
// or with data as second parameter
->editColumn('color', function($value, $data) {
	return "$value {$data->type}";
})
```

### Raw Columns
By default, all of the data is escaped to prevent XSS. But if you want to unescape them, you can use this method
```php
->rawColumns(['bio'])
```

### Hide Columns
Hide columns from JSON output
```php
->hideColumns(['password'])
```
