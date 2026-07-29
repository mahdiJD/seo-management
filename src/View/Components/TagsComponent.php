<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\View\Component;
use Mahdijd\SeoManagement\Contracts\SeoCacheManagerInterface;
use Mahdijd\SeoManagement\Services\SeoManager;

/**
 * TagsComponent
 *
 * Anonymous/Class-based Blade component <x-seo::tags /> for rendering SEO meta tags.
 */
class TagsComponent extends Component
{
    /**
     * Create a new TagsComponent instance.
     *
     * @param  Model|null  $model
     * @param  string|null  $title
     * @param  string|null  $description
     * @param  string|null  $keywords
     * @param  string|null  $canonical
     * @param  string|null  $robots
     * @param  string|null  $ogTitle
     * @param  string|null  $ogDescription
     * @param  string|null  $ogImage
     * @param  string|null  $ogType
     * @param  string|null  $ogUrl
     * @param  string|null  $ogSiteName
     * @param  string|null  $twitterCard
     * @param  string|null  $twitterTitle
     * @param  string|null  $twitterDescription
     * @param  string|null  $twitterImage
     * @param  array<string, mixed>|string|null  $jsonLd
     * @param  bool|null  $cache
     * @param  string|null  $cacheKey
     */
    public function __construct(
        public ?Model $model = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $keywords = null,
        public ?string $canonical = null,
        public ?string $robots = null,
        public ?string $ogTitle = null,
        public ?string $ogDescription = null,
        public ?string $ogImage = null,
        public ?string $ogType = null,
        public ?string $ogUrl = null,
        public ?string $ogSiteName = null,
        public ?string $twitterCard = null,
        public ?string $twitterTitle = null,
        public ?string $twitterDescription = null,
        public ?string $twitterImage = null,
        public array|string|null $jsonLd = null,
        public ?bool $cache = null,
        public ?string $cacheKey = null,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        /** @var SeoManager $seoManager */
        $seoManager = app(SeoManager::class);

        $runtimeOverrides = $this->extractRuntimeOverrides();

        $html = '';

        if ($this->model !== null) {
            $html = $seoManager->renderForModel($this->model, $runtimeOverrides);
        } else {
            /** @var \Illuminate\Routing\Route|null $route */
            $route = Request::route();
            $routeName = $route?->getName();

            if (is_string($routeName) && $routeName !== '') {
                $html = $seoManager->renderForRoute($routeName, $runtimeOverrides);
            } elseif ($this->cacheKey !== null && $this->cacheKey !== '') {
                /** @var SeoCacheManagerInterface $cacheManager */
                $cacheManager = app(SeoCacheManagerInterface::class);
                $key = $cacheManager->runtimeKey($this->cacheKey);

                if ($this->cache === false) {
                    $seoData = $seoManager->resolve($runtimeOverrides);
                    $html    = $seoManager->render($seoData);
                } else {
                    $html = $cacheManager->remember($key, function () use ($seoManager, $runtimeOverrides): string {
                        $seoData = $seoManager->resolve($runtimeOverrides);

                        return $seoManager->render($seoData);
                    });
                }
            } else {
                $seoData = $seoManager->resolve($runtimeOverrides);
                $html    = $seoManager->render($seoData);
            }
        }

        /** @var View */
        return view('seo::components.tags', [
            'html' => $html,
        ]);
    }

    /**
     * Extract non-null runtime attribute overrides as an associative array.
     *
     * @return array<string, mixed>
     */
    protected function extractRuntimeOverrides(): array
    {
        $overrides = [];

        $attributes = [
            'title'              => $this->title,
            'description'        => $this->description,
            'keywords'           => $this->keywords,
            'canonical'          => $this->canonical,
            'robots'             => $this->robots,
            'ogTitle'            => $this->ogTitle,
            'ogDescription'      => $this->ogDescription,
            'ogImage'            => $this->ogImage,
            'ogType'             => $this->ogType,
            'ogUrl'              => $this->ogUrl,
            'ogSiteName'         => $this->ogSiteName,
            'twitterCard'        => $this->twitterCard,
            'twitterTitle'       => $this->twitterTitle,
            'twitterDescription' => $this->twitterDescription,
            'twitterImage'       => $this->twitterImage,
        ];

        foreach ($attributes as $key => $val) {
            if ($val !== null && $val !== '') {
                $overrides[$key] = $val;
            }
        }

        if ($this->jsonLd !== null) {
            if (is_string($this->jsonLd)) {
                $decoded = json_decode($this->jsonLd, true);
                if (is_array($decoded)) {
                    $overrides['jsonLd'] = $decoded;
                }
            } elseif (is_array($this->jsonLd) && ! empty($this->jsonLd)) {
                $overrides['jsonLd'] = $this->jsonLd;
            }
        }

        return $overrides;
    }
}
