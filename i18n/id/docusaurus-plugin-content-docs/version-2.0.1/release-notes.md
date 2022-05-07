# Catatan Rilis

### CI4-DataTables v2.0.1
- Mendukung semua metode `POST` dan `GET`
    ```php
    $routes->get('datatables/json', 'Controller::method', ['as' => 'dt-json']);
    // or
    $routes->post('datatables/json', 'Controller::method', ['as' => 'dt-json']);
    ```
    
- Mengeset parameter dari fungsi `dump()` ke default ``false``
- Menambahkan ``orWhere`` method
