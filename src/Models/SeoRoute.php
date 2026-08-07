<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * SeoRoute
 *
 * Eloquent model representing SEO metadata associated with named web routes.
 *
 * @property int $id
 * @property string $route_name
 * @property string|null $title
 * @property string|null $description
 * @property string|null $keywords
 * @property string|null $canonical
 * @property string|null $robots
 * @property string|null $og_title
 * @property string|null $og_description
 * @property string|null $og_image
 * @property string|null $og_type
 * @property string|null $og_url
 * @property string|null $og_site_name
 * @property string|null $twitter_card
 * @property string|null $twitter_title
 * @property string|null $twitter_description
 * @property string|null $twitter_image
 * @property array<string, mixed>|null $json_ld
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SeoRoute extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seo_routes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'route_name',
        'title',
        'description',
        'keywords',
        'canonical',
        'robots',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'og_url',
        'og_site_name',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'json_ld',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'json_ld' => 'array',
    ];
}
