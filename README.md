# Automatically change the default Laravel MVC architecture to DDD architecture

At the moment, I do not have the attention to add new classes / helpers or any feature that's not provided by Laravel.

 ## Status
  - Work in progress but **should** work.   
I will not be responsible if this package destroys your code. 
It should be used on a fresh installation **only**.
  
## Requirements
  - Fresh installation of Laravel 8

## Installation
You can install the package via composer:

    composer require cbaconnier/laravel-mvc-to-ddd --dev

## Usage

    php artisan ddd:install
  
  
## Todo
  - Support laravel/jetstream
  - Support laravel/ui
  - Cleanup the empty folders
 
## Credit
Thanks to [Brent Roose](https://github.com/brendt) for his awesome work 
on [Laravel Beyond CRUD](https://spatie.be/products/laravel-beyond-crud) 
on which this architecture is based on.

     