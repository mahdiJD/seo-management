<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Enums;

/**
 * TwitterCardType
 *
 * Represents the supported Twitter (X) card types for the twitter:card meta tag.
 * Use these enum cases instead of raw strings to avoid typos and enable IDE support.
 *
 * @see https://developer.twitter.com/en/docs/twitter-for-websites/cards/overview/abouts-cards
 */
enum TwitterCardType: string
{
    /**
     * A small summary card with a thumbnail image.
     */
    case SUMMARY = 'summary';

    /**
     * A large summary card with a prominent image.
     * This is the most commonly used type for articles and landing pages.
     */
    case SUMMARY_LARGE_IMAGE = 'summary_large_image';

    /**
     * A card representing a mobile application.
     */
    case APP = 'app';

    /**
     * A card that displays a video player.
     */
    case PLAYER = 'player';

    /**
     * Return a human-readable label for the card type.
     */
    public function label(): string
    {
        return match ($this) {
            self::SUMMARY => 'Summary',
            self::SUMMARY_LARGE_IMAGE => 'Summary with Large Image',
            self::APP => 'App',
            self::PLAYER => 'Player',
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
