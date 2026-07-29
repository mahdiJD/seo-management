<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * SeoSettings
 *
 * Eloquent model representing global fallback SEO settings.
 * Exactly one record is expected in the database during MVP.
 *
 * @property int $id
 * @property string|null $site_name
 * @property string|null $default_title
 * @property string|null $default_description
 * @property string|null $default_canonical
 * @property string|null $default_robots
 * @property string|null $default_og_image
 * @property string|null $default_og_type
 * @property string|null $default_og_site_name
 * @property string|null $default_twitter_card
 * @property string|null $default_twitter_image
 * @property array|null $default_json_ld
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SeoSettings extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seo_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_name',
        'default_title',
        'default_description',
        'default_canonical',
        'default_robots',
        'default_og_image',
        'default_og_type',
        'default_og_site_name',
        'default_twitter_card',
        'default_twitter_image',
        'default_json_ld',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'default_json_ld' => 'array',
    ];
}
