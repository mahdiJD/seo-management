<?php

declare(strict_types=1);

use Mahdijd\SeoManagement\Enums\OpenGraphType;
use Mahdijd\SeoManagement\Enums\RobotsDirective;
use Mahdijd\SeoManagement\Enums\TwitterCardType;
use Mahdijd\SeoManagement\Exceptions\InvalidSeoConfigurationException;
use Mahdijd\SeoManagement\Exceptions\SeoException;

// ──────────────────────────────────────────────────────────────
// T002 · Configuration
// ──────────────────────────────────────────────────────────────

it('loads the seo configuration with correct defaults', function (): void {
    expect(config('seo.cache'))->toBeTrue()
        ->and(config('seo.cache_store'))->toBeNull()
        ->and(config('seo.cache_ttl'))->toBe(86400)
        ->and(config('seo.default_robots'))->toBe('index,follow')
        ->and(config('seo.default_twitter_card'))->toBe('summary_large_image')
        ->and(config('seo.filament.delete_empty_records'))->toBeFalse()
        ->and(config('seo.filament.navigation_group'))->toBe('SEO');
});

// ──────────────────────────────────────────────────────────────
// T003 · Enums — RobotsDirective
// ──────────────────────────────────────────────────────────────

describe('RobotsDirective', function (): void {
    it('has the correct string values', function (): void {
        expect(RobotsDirective::INDEX_FOLLOW->value)->toBe('index,follow')
            ->and(RobotsDirective::INDEX_NOFOLLOW->value)->toBe('index,nofollow')
            ->and(RobotsDirective::NOINDEX_FOLLOW->value)->toBe('noindex,follow')
            ->and(RobotsDirective::NOINDEX_NOFOLLOW->value)->toBe('noindex,nofollow');
    });

    it('can be created from a string value using from()', function (): void {
        expect(RobotsDirective::from('index,follow'))->toBe(RobotsDirective::INDEX_FOLLOW);
    });

    it('returns null for unknown value using tryFrom()', function (): void {
        expect(RobotsDirective::tryFrom('unknown'))->toBeNull();
    });

    it('returns options array with string keys and labels', function (): void {
        $options = RobotsDirective::options();

        expect($options)->toBeArray()
            ->toHaveCount(4)
            ->toHaveKey('index,follow');
    });
});

// ──────────────────────────────────────────────────────────────
// T003 · Enums — OpenGraphType
// ──────────────────────────────────────────────────────────────

describe('OpenGraphType', function (): void {
    it('has the correct string values', function (): void {
        expect(OpenGraphType::WEBSITE->value)->toBe('website')
            ->and(OpenGraphType::ARTICLE->value)->toBe('article')
            ->and(OpenGraphType::PRODUCT->value)->toBe('product')
            ->and(OpenGraphType::PROFILE->value)->toBe('profile')
            ->and(OpenGraphType::BOOK->value)->toBe('book')
            ->and(OpenGraphType::VIDEO_MOVIE->value)->toBe('video.movie')
            ->and(OpenGraphType::VIDEO_EPISODE->value)->toBe('video.episode');
    });

    it('has exactly 7 cases', function (): void {
        expect(OpenGraphType::cases())->toHaveCount(7);
    });

    it('can be created from a string value using from()', function (): void {
        expect(OpenGraphType::from('article'))->toBe(OpenGraphType::ARTICLE);
    });

    it('returns null for unknown value using tryFrom()', function (): void {
        expect(OpenGraphType::tryFrom('unknown'))->toBeNull();
    });

    it('returns options array with string keys and labels', function (): void {
        $options = OpenGraphType::options();

        expect($options)->toBeArray()
            ->toHaveCount(7)
            ->toHaveKey('website');
    });
});

// ──────────────────────────────────────────────────────────────
// T003 · Enums — TwitterCardType
// ──────────────────────────────────────────────────────────────

describe('TwitterCardType', function (): void {
    it('has the correct string values', function (): void {
        expect(TwitterCardType::SUMMARY->value)->toBe('summary')
            ->and(TwitterCardType::SUMMARY_LARGE_IMAGE->value)->toBe('summary_large_image')
            ->and(TwitterCardType::APP->value)->toBe('app')
            ->and(TwitterCardType::PLAYER->value)->toBe('player');
    });

    it('has exactly 4 cases', function (): void {
        expect(TwitterCardType::cases())->toHaveCount(4);
    });

    it('can be created from a string value using from()', function (): void {
        expect(TwitterCardType::from('summary_large_image'))->toBe(TwitterCardType::SUMMARY_LARGE_IMAGE);
    });

    it('returns null for unknown value using tryFrom()', function (): void {
        expect(TwitterCardType::tryFrom('unknown'))->toBeNull();
    });

    it('returns options array with string keys and labels', function (): void {
        $options = TwitterCardType::options();

        expect($options)->toBeArray()
            ->toHaveCount(4)
            ->toHaveKey('summary_large_image');
    });
});

// ──────────────────────────────────────────────────────────────
// T004 · Exceptions
// ──────────────────────────────────────────────────────────────

describe('Exceptions', function (): void {
    it('SeoException extends RuntimeException', function (): void {
        $exception = new SeoException('test');

        expect($exception)->toBeInstanceOf(RuntimeException::class);
    });

    it('InvalidSeoConfigurationException extends SeoException', function (): void {
        $exception = new InvalidSeoConfigurationException('bad config');

        expect($exception)
            ->toBeInstanceOf(SeoException::class)
            ->toBeInstanceOf(RuntimeException::class);
    });

    it('exceptions carry the correct message', function (): void {
        $seo = new SeoException('seo error');
        $invalid = new InvalidSeoConfigurationException('invalid config');

        expect($seo->getMessage())->toBe('seo error')
            ->and($invalid->getMessage())->toBe('invalid config');
    });
});
