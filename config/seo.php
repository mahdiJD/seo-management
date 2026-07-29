<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | SEO Cache
    |--------------------------------------------------------------------------
    |
    | Enable or disable caching of rendered SEO HTML output. When enabled,
    | rendered SEO tags are stored in the cache to avoid redundant database
    | queries and rendering on subsequent requests.
    |
    | Default: true
    |
    */
    'cache' => true,

    /*
    |--------------------------------------------------------------------------
    | SEO Cache Store
    |--------------------------------------------------------------------------
    |
    | The cache store to use for SEO caching. Set to null to use the default
    | cache store configured in your Laravel application. You may specify any
    | named cache store defined in config/cache.php (e.g. 'redis', 'file').
    |
    | Default: null (uses the application default cache store)
    |
    */
    'cache_store' => null,

    /*
    |--------------------------------------------------------------------------
    | SEO Cache TTL (Time-To-Live)
    |--------------------------------------------------------------------------
    |
    | The number of seconds that cached SEO output should be retained.
    | After this period, the cache entry expires and SEO tags will be
    | re-resolved and re-rendered on the next request.
    |
    | Default: 86400 (24 hours)
    |
    */
    'cache_ttl' => 86400,

    /*
    |--------------------------------------------------------------------------
    | Default Robots Directive
    |--------------------------------------------------------------------------
    |
    | The default robots meta tag value used as a global fallback when no
    | higher-priority source (model SEO, route SEO, or runtime override)
    | provides a robots directive.
    |
    | Common values: 'index,follow' | 'noindex,nofollow' | 'index,nofollow'
    |
    | Default: 'index,follow'
    |
    */
    'default_robots' => 'index,follow',

    /*
    |--------------------------------------------------------------------------
    | Default Twitter Card Type
    |--------------------------------------------------------------------------
    |
    | The default Twitter card type used as a global fallback when no
    | higher-priority source provides a twitter:card value.
    |
    | Common values: 'summary' | 'summary_large_image' | 'app' | 'player'
    |
    | Default: 'summary_large_image'
    |
    */
    'default_twitter_card' => 'summary_large_image',

    /*
    |--------------------------------------------------------------------------
    | Filament Configuration
    |--------------------------------------------------------------------------
    |
    | Settings specific to the Filament admin panel integration.
    |
    */
    'filament' => [

        /*
        |----------------------------------------------------------------------
        | Delete Empty SEO Records
        |----------------------------------------------------------------------
        |
        | When set to true, the SeoManagerGroup Filament component will
        | automatically delete the seo_metadata database record when all
        | SEO fields are saved as null/empty.
        |
        | When false (default), the empty record is retained in the database.
        |
        | Default: false
        |
        */
        'delete_empty_records' => false,

        /*
        |----------------------------------------------------------------------
        | Navigation Group
        |----------------------------------------------------------------------
        |
        | The Filament navigation group label under which SEO resources and
        | pages will be grouped in the admin panel sidebar.
        |
        | Default: 'SEO'
        |
        */
        'navigation_group' => 'SEO',

    ],

];
