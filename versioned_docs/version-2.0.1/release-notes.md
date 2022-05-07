# Release Notes

### CI4-DataTables v2.0.1
- Supported both `POST` and `GET` method
    ```php
    $routes->get('datatables/json', 'Controller::method', ['as' => 'dt-json']);
    // or
    $routes->post('datatables/json', 'Controller::method', ['as' => 'dt-json']);
    ```
    
- Set default `dump()` method parameter to ``false``
- Added ``orWhere`` method
