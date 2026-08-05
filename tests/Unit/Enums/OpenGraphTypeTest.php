<?php

declare(strict_types=1);

use Mahdijd\SeoManagement\Enums\OpenGraphType;

describe('OpenGraphType Enum', function (): void {
    it('has the correct string values for all cases', function (): void {
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

    it('can be created from a valid string value using from()', function (): void {
        expect(OpenGraphType::from('article'))->toBe(OpenGraphType::ARTICLE)
            ->and(OpenGraphType::from('website'))->toBe(OpenGraphType::WEBSITE);
    });

    it('throws ValueError for an invalid string value using from()', function (): void {
        OpenGraphType::from('invalid-og-type');
    })->throws(ValueError::class);

    it('returns null for unknown value using tryFrom()', function (): void {
        expect(OpenGraphType::tryFrom('unknown'))->toBeNull();
    });

    it('returns options array with string keys and human-readable labels', function (): void {
        $options = OpenGraphType::options();

        expect($options)->toBeArray()
            ->toHaveCount(7)
            ->toHaveKey('website')
            ->toHaveKey('article');
    });

    it('provides human-readable labels via label() method', function (): void {
        $label = OpenGraphType::WEBSITE->label();

        expect($label)->toBeString()->not->toBeEmpty();
    });
});
