# Laravel MVC to DDD

Automatically change the default Laravel MVC architecture to a DDD architecture approach.  


 ## Disclaimer
⚠️ I will not be responsible if this package destroys your code. 
It should **only** be used on a fresh installation.

## Compatibilities
 - Laravel 8.0
 - Laravel Fortify
  
## Requirements
  - Fresh installation of Laravel 8
  - Laravel Fortify  _(optional)_ 

## Installation
You can install the package via composer:

    composer require cbaconnier/laravel-mvc-to-ddd --dev

## Usage

    php artisan ddd:install
  
  _Note: You can remove it once it has been installed_

    composer remove cbaconnier/laravel-mvc-to-ddd
  
## Todo
  - Support laravel/jetstream
  - Support laravel/ui
  - Cleanup the empty folders
 
## Credit
Thanks to [Brent Roose](https://github.com/brendt) for his awesome work 
on [Laravel Beyond CRUD](https://spatie.be/products/laravel-beyond-crud) 
on which this architecture is based on.

     
