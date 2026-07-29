<?php

declare(strict_types=1);

use Mahdijd\SeoManagement\DTOs\SeoData;
use Mahdijd\SeoManagement\Services\SeoRenderer;

describe('SeoRenderer', function (): void {
    beforeEach(function (): void {
        $this->renderer = new SeoRenderer();
    });

    it('returns empty string when SeoData has all null properties', function (): void {
        $data = new SeoData();

        $html = $this->renderer->render($data);

        expect($html)->toBe('');
    });

    it('renders title tag when title property is provided', function (): void {
        $data = new SeoData(title: 'My Custom Title');

        $html = $this->renderer->render($data);

        expect($html)->toBe('<title>My Custom Title</title>');
    });

    it('renders all standard meta tags in deterministic order', function (): void {
        $data = new SeoData(
            title: 'Title',
            description: 'Description',
            keywords: 'kw1, kw2',
            canonical: 'https://example.com/page',
            robots: 'index,follow',
        );

        $html = $this->renderer->render($data);

        expect($html)->toContain('<title>Title</title>')
            ->and($html)->toContain('<meta name="description" content="Description">')
            ->and($html)->toContain('<meta name="keywords" content="kw1, kw2">')
            ->and($html)->toContain('<link rel="canonical" href="https://example.com/page">')
            ->and($html)->toContain('<meta name="robots" content="index,follow">');
    });

    it('renders Open Graph and Twitter meta tags', function (): void {
        $data = new SeoData(
            ogTitle: 'OG Title',
            ogDescription: 'OG Description',
            ogImage: 'https://example.com/image.jpg',
            twitterCard: 'summary_large_image',
            twitterTitle: 'Twitter Title',
        );

        $html = $this->renderer->render($data);

        expect($html)->toContain('<meta property="og:title" content="OG Title">')
            ->and($html)->toContain('<meta property="og:description" content="OG Description">')
            ->and($html)->toContain('<meta property="og:image" content="https://example.com/image.jpg">')
            ->and($html)->toContain('<meta name="twitter:card" content="summary_large_image">')
            ->and($html)->toContain('<meta name="twitter:title" content="Twitter Title">');
    });

    it('renders JSON-LD structured data script block', function (): void {
        $data = new SeoData(
            jsonLd: [
                '@context' => 'https://schema.org',
                '@type'    => 'Article',
                'headline' => 'JSON-LD Article',
            ]
        );

        $html = $this->renderer->render($data);

        expect($html)->toContain('<script type="application/ld+json">')
            ->and($html)->toContain('"@context": "https://schema.org"')
            ->and($html)->toContain('"headline": "JSON-LD Article"')
            ->and($html)->toContain('</script>');
    });

    it('html-escapes string property values to prevent XSS attacks', function (): void {
        $data = new SeoData(
            title: 'Title <script>alert("xss")</script>',
            description: 'Description "with quotes" & <tags>',
        );

        $html = $this->renderer->render($data);

        expect($html)->not->toContain('<script>alert')
            ->and($html)->toContain('<title>Title &lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;</title>')
            ->and($html)->toContain('content="Description &quot;with quotes&quot; &amp; &lt;tags&gt;"');
    });

    it('suppresses empty or null tags without leaving blank meta lines', function (): void {
        $data = new SeoData(
            title: 'Title',
            description: null, // should be omitted completely
            canonical: '',     // empty string should be omitted
        );

        $html = $this->renderer->render($data);

        expect($html)->toBe('<title>Title</title>')
            ->and($html)->not->toContain('name="description"')
            ->and($html)->not->toContain('rel="canonical"');
    });
});
