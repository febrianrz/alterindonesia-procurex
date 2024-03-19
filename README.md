# Alter Indonesia - ProcureX

## Installation

You can install the package via composer:

```bash
composer require alterindonesia/procurex
```

Publish the configuration:

```bash
php artisan vendor:publish --provider="Alterindonesia\Procurex\Providers\AlterindonesiaProcurexProvider"
```

### Octane Configuration
Add `FlushAuthPermissionsCache` to `config/octane.php` in 'RequestTerminated::class' listeners:

```php
    'listeners' => [
        // ...
    
        RequestTerminated::class => [
            'Alterindonesia\Procurex\Http\Middleware\FlushAuthPermissionsCache',
        ],
        
        // ...
    ],
```