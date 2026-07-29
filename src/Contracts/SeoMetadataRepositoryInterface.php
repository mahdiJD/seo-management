<?php

declare(strict_types=1);

namespace Mahdijd\SeoManagement\Contracts;

use Illuminate\Database\Eloquent\Model;
use Mahdijd\SeoManagement\Models\SeoMetadata;

/**
 * SeoMetadataRepositoryInterface
 *
 * Contract for repositories managing database access for model-attached SeoMetadata records.
 */
interface SeoMetadataRepositoryInterface
{
    /**
     * Find the SeoMetadata record for a given Eloquent model.
     *
     * @param  Model  $model
     * @return SeoMetadata|null
     */
    public function findByModel(Model $model): ?SeoMetadata;

    /**
     * Save or update SEO metadata for the given Eloquent model.
     *
     * @param  Model  $model
     * @param  array<string, mixed>  $data
     * @return SeoMetadata
     */
    public function save(Model $model, array $data): SeoMetadata;

    /**
     * Delete the SeoMetadata record for the given Eloquent model.
     *
     * @param  Model  $model
     * @return bool
     */
    public function delete(Model $model): bool;
}
