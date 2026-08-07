<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Enums;

/**
 * RobotsDirective
 *
 * Represents the standard robots meta tag directives supported by this package.
 * Use these enum cases instead of raw strings to avoid typos and enable IDE support.
 *
 * @see https://developers.google.com/search/docs/crawling-indexing/robots-meta-tag
 */
enum RobotsDirective: string
{
    /**
     * Allow indexing and allow following links.
     * This is the default behaviour for most search engines.
     */
    case INDEX_FOLLOW = 'index,follow';

    /**
     * Allow indexing but do not follow links.
     */
    case INDEX_NOFOLLOW = 'index,nofollow';

    /**
     * Do not index this page but allow following links.
     */
    case NOINDEX_FOLLOW = 'noindex,follow';

    /**
     * Do not index this page and do not follow links.
     */
    case NOINDEX_NOFOLLOW = 'noindex,nofollow';

    /**
     * Return a human-readable label for the directive.
     */
    public function label(): string
    {
        return match ($this) {
            self::INDEX_FOLLOW => 'Index, Follow',
            self::INDEX_NOFOLLOW => 'Index, No Follow',
            self::NOINDEX_FOLLOW => 'No Index, Follow',
            self::NOINDEX_NOFOLLOW => 'No Index, No Follow',
        };
    }

    /**
     * Return all cases as a key-value array suitable for select inputs.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return array_column(
            array_map(
                fn (self $case) => ['value' => $case->value, 'label' => $case->label()],
                self::cases()
            ),
            'label',
            'value'
        );
    }
}
