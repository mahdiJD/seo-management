<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Enums;

/**
 * OpenGraphType
 *
 * Represents the supported Open Graph object types for the og:type meta tag.
 * Use these enum cases instead of raw strings to avoid typos and enable IDE support.
 *
 * @see https://ogp.me/#types
 */
enum OpenGraphType: string
{
    /**
     * A website or web page (generic fallback).
     */
    case WEBSITE = 'website';

    /**
     * An article, blog post, or news item.
     */
    case ARTICLE = 'article';

    /**
     * A product (e.g. e-commerce listing).
     */
    case PRODUCT = 'product';

    /**
     * A personal profile or author page.
     */
    case PROFILE = 'profile';

    /**
     * A book.
     */
    case BOOK = 'book';

    /**
     * A movie.
     */
    case VIDEO_MOVIE = 'video.movie';

    /**
     * A TV show episode.
     */
    case VIDEO_EPISODE = 'video.episode';

    /**
     * Return a human-readable label for the type.
     */
    public function label(): string
    {
        return match ($this) {
            self::WEBSITE       => 'Website',
            self::ARTICLE       => 'Article',
            self::PRODUCT       => 'Product',
            self::PROFILE       => 'Profile',
            self::BOOK          => 'Book',
            self::VIDEO_MOVIE   => 'Movie',
            self::VIDEO_EPISODE => 'TV Episode',
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
