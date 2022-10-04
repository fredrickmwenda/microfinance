# An ACL Management Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tafaqari/user-manager.svg?style=flat-square)](https://packagist.org/packages/tafaqari/user-manager)
[![Build Status](https://img.shields.io/travis/tafaqari/user-manager/master.svg?style=flat-square)](https://travis-ci.org/tafaqari/user-manager)
[![Quality Score](https://img.shields.io/scrutinizer/g/tafaqari/user-manager.svg?style=flat-square)](https://scrutinizer-ci.com/g/tafaqari/user-manager)
[![Total Downloads](https://img.shields.io/packagist/dt/tafaqari/user-manager.svg?style=flat-square)](https://packagist.org/packages/tafaqari/user-manager)

This is an extension of Spatie's Laravel Permissions system to provide necessary scaffolding to manage users, roles and permissions out of the box.

## Installation

You can install the package via composer:

```bash
composer require tafaqari/user-manager
```

## Usage

Publish assets

``` php
// Usage description here
```

Add this line to the AuthServiceProvider to give a user with role "Super Admin" all rights. Please refer to extensive
documentation at the <a href="https://docs.spatie.be/laravel-permission/v3/basic-usage/super-admin/">spatie/laravel-permission</a>
official site.

``` bash
Gate::before(function ($user, $ability) {
    return $user->hasRole('Super Admin') ? true : null;
});
```
Also add these lines to `app/Http/Kernel.php`

```bash
// ...
    'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
    'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
    'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
```
### Testing
...
### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mouggey@yahoo.com instead of using the issue tracker.

## Credits

- [Jack Mugi](https://github.com/tafaqari)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).