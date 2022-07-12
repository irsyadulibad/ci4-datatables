# ci4-datatables
[![Donate](https://img.shields.io/badge/donate-paypal-blue.svg)](https://www.paypal.me/irsyadulibad7)
[![Donate](https://img.shields.io/badge/donate-kofi-blue.svg)](https://ko-fi.com/irsyadulibad)

![GitHub](https://img.shields.io/github/license/irsyadulibad/ci4-datatables)
![GitHub repo size](https://img.shields.io/github/repo-size/irsyadulibad/ci4-datatables?label=size)
![Hits](https://hits.seeyoufarm.com/api/count/incr/badge.svg?url=irsyadulibad/ci4-datatables)
![Packagist Downloads](https://img.shields.io/packagist/dt/irsyadulibad/codeigniter4-datatables)
![Testing Status](https://github.com/irsyadulibad/ci4-datatables/workflows/tests/badge.svg)

Library that will speed up you to create serverside DataTables API using the CodeIgniter v4 framework.

```php
return datatables('users')->make();

// With Codeigniter4 Query Builder
$query = db_connect()->table('table');
return datatables($query)->make();
```


## Requirements
* [CodeIgniter Framework v4](https://github.com/codeigniter4/CodeIgniter4)
* [JQuery DataTables](https://datatables.net)

## Installation

### Composer Installation

Installation is best done via Composer, you may use the following command:

  > composer require irsyadulibad/codeigniter4-datatables

This will add the latest release of **codeigniter4-datatables** as a module to your project

### Manual Installation

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

## Documentations
> make sure you match the documentation version with the library version you're using

- [Website](https://ci4-datatables.netlify.app)

## Author's Profile:

Github: [https://github.com/irsyadulibad]\
Website: [http://irsyadulibad.my.id]\
Facebook: [https://facebook.com/irsyadulibad.dev]
