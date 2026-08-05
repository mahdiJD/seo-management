<?php

declare(strict_types=1);

use Mahdijd\SeoManagement\Enums\TwitterCardType;

describe('TwitterCardType Enum', function (): void {
    it('has the correct string values for all cases', function (): void {
        expect(TwitterCardType::SUMMARY->value)->toBe('summary')
            ->and(TwitterCardType::SUMMARY_LARGE_IMAGE->value)->toBe('summary_large_image')
            ->and(TwitterCardType::APP->value)->toBe('app')
            ->and(TwitterCardType::PLAYER->value)->toBe('player');
    });

    it('has exactly 4 cases', function (): void {
        expect(TwitterCardType::cases())->toHaveCount(4);
    });

    it('can be created from a valid string value using from()', function (): void {
        expect(TwitterCardType::from('summary_large_image'))->toBe(TwitterCardType::SUMMARY_LARGE_IMAGE)
            ->and(TwitterCardType::from('summary'))->toBe(TwitterCardType::SUMMARY);
    });

    it('throws ValueError for an invalid string value using from()', function (): void {
        TwitterCardType::from('invalid-card');
    })->throws(ValueError::class);

    it('returns null for unknown value using tryFrom()', function (): void {
        expect(TwitterCardType::tryFrom('unknown'))->toBeNull();
    });

    it('returns options array with string keys and human-readable labels', function (): void {
        $options = TwitterCardType::options();

        expect($options)->toBeArray()
            ->toHaveCount(4)
            ->toHaveKey('summary_large_image');
    });

    it('provides human-readable labels via label() method', function (): void {
        $label = TwitterCardType::SUMMARY->label();

        expect($label)->toBeString()->not->toBeEmpty();
    });
});
