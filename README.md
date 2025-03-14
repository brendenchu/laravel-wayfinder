# Wayfinder

Wayfinder is a package for efficient management of search operations in your Laravel application. Any Eloquent model that implements the `Searchable` interface can be searched using Wayfinder. Wayfinder is designed to be simple and easy to use, while providing a powerful search experience.

## Installation

You can install the package via composer:

```bash
composer require brendenchu/laravel-wayfinder
```

The package will automatically register itself.

## Configuration

You can publish the configuration file using the following command:

```bash
php artisan vendor:publish --provider="Brendenchu\Wayfinder\WayfinderServiceProvider" --tag="wayfinder-config"
```

This will create a `wayfinder.php` file in your `config` directory. This file contains the configuration options for the package.
