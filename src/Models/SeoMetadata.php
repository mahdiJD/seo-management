<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * SeoMetadata
 *
 * Eloquent model representing SEO metadata associated with any Eloquent model
 * via a polymorphic morphOne relationship.
 *
 * @property int $id
 * @property string $seoable_type
 * @property int $seoable_id
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
class SeoMetadata extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seo_metadata';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seoable_type',
        'seoable_id',
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

    /**
     * Get the owning seoable model.
     *
     * @return MorphTo<Model, $this>
     */
    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
