---
sidebar_position: 1
category: intro-index
description: Introduction of CodeIgniter4 DataTables 
---

# Introduction
CI4-DataTables is a datatables library that will speed up you to create serverside datatable API using the [CodeIgniter v4](https://codeigniter.com) framework.

This is an example code for using this library:

PHP:
```php
<?php namespace App\Controllers;

use Irsyadulibad\DataTables\DataTables;

class Home extends BaseController
{
	public function json()
	{
		return DataTables::use('users')
			->where(['role' => 'admin'])
			->hideColumns(['password'])
			->rawColumns(['bio'])
			->make(true);
	}
}
```

JavaScript:
```javascript
$('#table').DataTable({
  processing: true,
  serverSide: true,
  ajax:{
    url: 'http://localhost:8080/json'
  },
  columns: [
	  {data: 'username', name: 'username'},
	  {data: 'email', name: 'email'},
	  {data: 'fullname', name: 'fullname'}
	  {data: 'bio', name: 'bio'}
  ]
});
```
