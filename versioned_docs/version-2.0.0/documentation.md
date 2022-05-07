# Documentation

## Initialization
```php
<?php
use Irsyadulibad\DataTables\DataTables;

DataTables::use('table');
?>
```

## Available Methods
The following methods are available to be used in this library
### Select Table
Select the table that you want to use
```php
DataTables::use('table')
```

### Set Output
The default parameter is true, which is automatically return the JSON data. You can return the data's dump by passing the **false** param
```php
DataTables::use('table')
	->make(false);
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
->editColumn('created_at', function($data) {
    return format($data);
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
