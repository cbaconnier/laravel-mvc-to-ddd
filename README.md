# Laravel MVC to DDD

Automatically change the default Laravel MVC architecture to a DDD architecture approach.  
You can see an example of what it will do on a fresh Laravel application using Livewire, Jetstream and teams feature: [cbaconnier/laravel-mvc-to-ddd-demo](https://github.com/cbaconnier/laravel-mvc-to-ddd-demo)


 ## Disclaimer
⚠️ I will not be responsible if this package destroys your code. 
It should **only** be used on a fresh installation.

## Requirements
  - Fresh installation of Laravel 9
  - Laravel Fortify  _(optional)_ 
  - Laravel Jetstream  _(optional)_ 
  - Laravel Pest  _(optional)_

## Installation
You can install the package via composer:

    composer require cbaconnier/laravel-mvc-to-ddd --dev

## Usage
Since the command may be destructive to your application, it's **strongly recommended** to commit before you run this command

    php artisan ddd:install
  
  _Note: You can remove it once it has been installed_

    composer remove cbaconnier/laravel-mvc-to-ddd
 
## Architecture

```
.
├── boostrap
├── config
├── database
├── public
├── resources
├── routes
├── src
│   ├── App
│   │   ├── Controllers
│   │   ├── Exceptions
│   │   ├── Providers
│   │   └── View
│   ├── Domain  
│   │   ├── Team
│   │   │   ├── Actions
│   │   │   ├── Models
│   │   │   └── Policies
│   │   └── User   
│   │       ├── Actions
│   │       ├── Models
│   │       └── Rules
│   └── Support  
│       └── Middleware
├── storage
└── tests
    ├── App
    │   ├── ApiTokens
    │   ├── Auth
    │   ├── Profile
    │   └── Teams
    ├── Domain
    └── Support
```
   
## Todo
  - Support laravel/ui
  - Cleanup the empty folders

## Credit
Thanks to [Brent Roose](https://github.com/brendt) for his awesome work 
on [Laravel Beyond CRUD](https://spatie.be/products/laravel-beyond-crud) 
on which this architecture is based on.

     
