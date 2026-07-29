<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Repositories;

use Illuminate\Database\Eloquent\Model;
use Mahdijd\SeoManagement\Contracts\SeoMetadataRepositoryInterface;
use Mahdijd\SeoManagement\Models\SeoMetadata;

/**
 * SeoMetadataRepository
 *
 * Eloquent-based repository managing database access for model-attached SeoMetadata records.
 * Encapsulates all Eloquent queries for seo_metadata. No caching or business logic.
 */
class SeoMetadataRepository implements SeoMetadataRepositoryInterface
{
    /**
     * Find the SeoMetadata record for a given Eloquent model.
     *
     * @param  Model  $model
     * @return SeoMetadata|null
     */
    public function findByModel(Model $model): ?SeoMetadata
    {
        return SeoMetadata::query()
            ->where('seoable_type', $model->getMorphClass())
            ->where('seoable_id', $model->getKey())
            ->first();
    }

    /**
     * Save or update SEO metadata for the given Eloquent model.
     *
     * @param  Model  $model
     * @param  array<string, mixed>  $data
     * @return SeoMetadata
     */
    public function save(Model $model, array $data): SeoMetadata
    {
        return SeoMetadata::query()->updateOrCreate(
            [
                'seoable_type' => $model->getMorphClass(),
                'seoable_id'   => $model->getKey(),
            ],
            $data
        );
    }

    /**
     * Delete the SeoMetadata record for the given Eloquent model.
     *
     * @param  Model  $model
     * @return bool
     */
    public function delete(Model $model): bool
    {
        $record = $this->findByModel($model);

        if (! $record) {
            return false;
        }

        return (bool) $record->delete();
    }
}
