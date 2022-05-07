# Installation

## Requirements
- [CodeIgniter4 Framework](https://github.com/codeigniter4/framework)
- [JQuery DataTables](https://github.com/DataTables/DataTablesSrc)

## Installing CI4-DataTables
### Composer method
Installation is best done via Composer, you may use the following command:
```bash
composer require irsyadulibad/codeigniter4-datatables
```

This will add the latest release of **codeigniter4-datatables** as a module to your project.

### Manual method
Should you choose not to use Composer to install, you can download this repo, extract and rename this folder to **codeigniter4-datatables**. 
Then enable it by editing **app/Config/Autoload.php** and adding the **Irsyadulibad\DataTables**
namespace to the **$psr4** array. For example, if you copied it into **app/Libraries**:
```php
    $psr4 = [
        'Config'      => APPPATH . 'Config',
        APP_NAMESPACE => APPPATH,
        'App'         => APPPATH,
        'Irsyadulibad\DataTables'   => APPPATH .'Libraries/codeigniter4-datatables/src',
    ];
```
