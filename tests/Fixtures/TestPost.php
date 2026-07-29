<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

/**
 * TestPost
 *
 * A minimal Eloquent model used exclusively in tests to verify the HasSeo
 * trait behaviour. This model is not part of the production package code.
 *
 * The HasSeo trait will be applied once it is implemented in Milestone 6 (T028).
 * For now, this fixture exists to provide the test infrastructure scaffold
 * required by T005 without forward-implementing future milestones.
 */
class TestPost extends Model
{
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
}
