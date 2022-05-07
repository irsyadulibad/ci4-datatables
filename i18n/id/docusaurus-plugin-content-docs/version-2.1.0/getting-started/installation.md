# Instalasi

## Persyaratan
- [CodeIgniter4 Framework](https://github.com/codeigniter4/framework)
- [JQuery DataTables](https://github.com/DataTables/DataTablesSrc)

## Menginstall CI4-DataTables
### Metode Composer
Pemasangan sangat dianjurkan dan lebih mudah menggunakan composer. Anda dapat mengetikkan perintah dibawah:
```bash
composer require irsyadulibad/codeigniter4-datatables
```

Itu akan menambahkan versi terbaru dari **codeigniter4-datatables** sebagai modul pada project anda.

### Metode Manual
Jika anda tidak memilih metode composer diatas, anda dapat mengunduhnya dari [repo](https://github.com/irsyadulibad/ci4-datatables/archive/refs/tags/2.0.0.zip) lalu ekstrak dan ganti nama direktorinya menjadi **codeigniter4-datatables**.
Kemudian aktifkan autoloader dengan mengedit file **app/Config/Autoload.php** dan tambahkan namespace **Irsyadulibad\DataTables** pada array **psr4**. Misal jika anda meletakkannya pada direktori **app/Libraries**:
```php
    $psr4 = [
        'Config'      => APPPATH . 'Config',
        APP_NAMESPACE => APPPATH,
        'App'         => APPPATH,
        'Irsyadulibad\DataTables'   => APPPATH .'Libraries/codeigniter4-datatables/src',
    ];
```
