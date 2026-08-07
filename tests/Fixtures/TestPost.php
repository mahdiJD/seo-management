<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Mahdijd\SeoManagement\Traits\HasSeo;

/**
 * TestPost
 *
 * A minimal Eloquent model fixture used exclusively in tests to verify HasSeo trait
 * and SEO management features.
 */
class TestPost extends Model
{
    use HasSeo;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title', 'excerpt'];

    /**
     * Get dynamic fallback values for SEO fields.
     *
     * @return array<string, mixed>
     */
    public function getSeoFallback(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->excerpt,
        ];
    }
}
