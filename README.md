# Laravel Filament SEO Package

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-12%2B-red)](https://laravel.com)
[![Filament Version](https://img.shields.io/badge/Filament-4%2B-orange)](https://filamentphp.com)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

A modern, developer-friendly SEO management package for Laravel applications with first-class support for Filament v4.

## Features

- 🎯 **SEO for any Eloquent model** — add a single trait and you're done
- 🗺️ **Route SEO** — manage SEO for named routes without model backing
- ⚡ **Runtime overrides** — dynamically override any SEO field per-request
- 🌍 **Global defaults** — application-wide fallback values for all SEO fields
- 🧠 **Intelligent resolution** — priority chain: Runtime → Model → Route → Global
- 💾 **Aggressive caching** — rendered HTML is cached; zero DB queries on cache hits
- 🔄 **Automatic invalidation** — cache is cleared automatically when data changes
- 🎨 **Filament v4 integration** — `SeoManagerGroup`, `SeoRouteResource`, `SeoSettingsPage`
- 🏷️ **Complete meta tag support** — title, description, canonical, robots, Open Graph, Twitter Cards, JSON-LD

## Requirements

| Dependency | Version |
|---|---|
| PHP | ≥ 8.2 |
| Laravel | ≥ 12.0 |
| Filament | ≥ 4.0 |
| Livewire | ≥ 3.0 |

## Installation

```bash
composer require mahdijd/seo-management
```

### Publish and migrate

```bash
# Publish config
php artisan vendor:publish --tag=seo-config

# Publish migrations
php artisan vendor:publish --tag=seo-migrations

# Run migrations
php artisan migrate
```

## Quick Start

### 1. Add SEO to a model

```php
use Mahdijd\SeoManagement\Traits\HasSeo;

class Post extends Model
{
    use HasSeo;

    // Optional: provide dynamic fallback values
    public function getSeoFallback(): array
    {
        return [
            'title'       => $this->title,
            'description' => $this->excerpt,
        ];
    }
}
```

### 2. Render SEO tags in your Blade layout

```blade
<head>
    <meta charset="UTF-8">
    <x-seo::tags :model="$post" />
</head>
```

### 3. Runtime overrides

```blade
<x-seo::tags
    title="Search results for: {{ $query }}"
    :cache="false"
/>
```

### 4. Cacheable runtime pages

```blade
<x-seo::tags
    :model="$product"
    cacheKey="product-detail-{{ $product->id }}"
/>
```

## Facade Usage

```php
use Mahdijd\SeoManagement\Facades\Seo;

// Resolve SEO data for a model
$seoData = Seo::forModel($post);

// Resolve SEO for a named route
$seoData = Seo::forRoute('home');

// Manually clear model SEO cache
Seo::clearModel($post);

// Manually clear route SEO cache
Seo::clearRoute('home');

// Flush all SEO cache entries
Seo::flushAll();
```

## Filament Integration

Register the plugin in your panel provider:

```php
use Mahdijd\SeoManagement\Filament\SeoPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            SeoPlugin::make(),
        ]);
}
```

Add `SeoManagerGroup` to any Filament resource form:

```php
use Mahdijd\SeoManagement\Filament\Forms\SeoManagerGroup;

public static function form(Form $form): Form
{
    return $form->schema([
        // ... your other fields ...
        SeoManagerGroup::make(),
    ]);
}
```

## Configuration

```php
// config/seo.php
return [
    'cache'                => true,        // Enable/disable SEO caching
    'cache_store'          => null,        // Cache store (null = default)
    'cache_ttl'            => 86400,       // Cache TTL in seconds (24h)
    'default_robots'       => 'index,follow',
    'default_twitter_card' => 'summary_large_image',
    'filament' => [
        'delete_empty_records' => false,   // Delete seo_metadata when all fields null
        'navigation_group'     => 'SEO',   // Filament sidebar group label
    ],
];
```

## Artisan Commands

```bash
# Clear all SEO cache entries
php artisan seo:cache:clear
```

## Architecture

The package follows a strict layered architecture:

```
Blade Component (<x-seo::tags />)
        ↓
    SeoManager
        ↓
SeoResolver     SeoRenderer     SeoCacheManager
        ↓
  Repository Layer (SeoMetadataRepository, SeoRouteRepository, SeoSettingsRepository)
        ↓
    Database (seo_metadata, seo_routes, seo_settings)
```

**Resolution priority (highest to lowest):**
1. Runtime overrides (Blade attributes / `resolve(array $runtime)`)
2. Model SEO (`seo_metadata` record + `getSeoFallback()`)
3. Route SEO (`seo_routes` record)
4. Global defaults (`seo_settings` record)

## Supported Metadata

| Category | Tags |
|---|---|
| Standard | `<title>`, `description`, `canonical`, `robots`, `keywords` |
| Open Graph | `og:title`, `og:description`, `og:image`, `og:type`, `og:url`, `og:site_name` |
| Twitter Cards | `twitter:card`, `twitter:title`, `twitter:description`, `twitter:image` |
| Structured Data | `<script type="application/ld+json">` |

## Testing

```bash
# Run all tests
composer test

# Run with coverage
composer test:coverage

# Static analysis
composer analyse

# Code style check
composer lint:check
```

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Changelog

### v1.0.0 (unreleased)

- Initial release
- Model SEO, Route SEO, Runtime SEO, Global defaults
- Filament v4 integration: `SeoManagerGroup`, `SeoRouteResource`, `SeoSettingsPage`
- Automatic cache management with observer-based invalidation
- PHPStan level 8 compatible
