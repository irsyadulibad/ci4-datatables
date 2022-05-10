---
sidebar_position: 1
category: intro-index
description: Pengfenalan untuk CodeIgniter4 DataTables 
---

# Pengenalan
CI4-DataTables adalah sebuah library yang akan membantu anda membuat API datatables serverside dengan cepat menggunakan framework [CodeIgniter v4](https://codeigniter.com).

Berikut adalah contoh penggunaannya

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

		// Atau dengan helper (direkomendasikan)
		return datatables('users')->make();
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
