---
sidebar_position: 1
---
# Query DataTable

## Initialization

### With helper (Recommended)
```php
datatables('users')
```

### With Class Name
```php
use Irsyadulibad\DataTables\DataTables;

DataTables::use('table')
```

## Usage

### Select Table
Select the table that you want to use
```php
DataTables::use('table')
```

### Set Output
The default parameter is false, which is automatically return the JSON data. You can return the data's dump by passing the **true** param
```php
DataTables::use('table')
	->make(true);
```

### Select Fields
Select the sepicifics column in the table
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
You can edit columns before output on json
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

### Index Column
In some cases, you need to track the index of the records on your response. This method will add another column on your response with default name is `DT_RowIndex`.
```php
->addIndexColumn()

// with custom column name
->addIndexColumn('CustomRowIndex')
```
