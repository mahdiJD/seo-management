<?php

declare(strict_types=1);

use Mahdijd\SeoManagement\Enums\RobotsDirective;

describe('RobotsDirective Enum', function (): void {
    it('has the correct string values for all cases', function (): void {
        expect(RobotsDirective::INDEX_FOLLOW->value)->toBe('index,follow')
            ->and(RobotsDirective::INDEX_NOFOLLOW->value)->toBe('index,nofollow')
            ->and(RobotsDirective::NOINDEX_FOLLOW->value)->toBe('noindex,follow')
            ->and(RobotsDirective::NOINDEX_NOFOLLOW->value)->toBe('noindex,nofollow');
    });

    it('has exactly 4 cases', function (): void {
        expect(RobotsDirective::cases())->toHaveCount(4);
    });

    it('can be created from a valid string value using from()', function (): void {
        expect(RobotsDirective::from('index,follow'))->toBe(RobotsDirective::INDEX_FOLLOW)
            ->and(RobotsDirective::from('noindex,nofollow'))->toBe(RobotsDirective::NOINDEX_NOFOLLOW);
    });

    it('throws ValueError for an invalid string value using from()', function (): void {
        RobotsDirective::from('invalid-value');
    })->throws(ValueError::class);

    it('returns null for unknown value using tryFrom()', function (): void {
        expect(RobotsDirective::tryFrom('unknown'))->toBeNull();
    });

    it('returns options array with string keys and human-readable labels', function (): void {
        $options = RobotsDirective::options();

        expect($options)->toBeArray()
            ->toHaveCount(4)
            ->toHaveKey('index,follow')
            ->toHaveKey('noindex,nofollow');
    });

    it('provides human-readable labels via label() method', function (): void {
        $label = RobotsDirective::INDEX_FOLLOW->label();

        expect($label)->toBeString()->not->toBeEmpty();
    });
});
