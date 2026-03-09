<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    /**
     * @return MorphToMany<Tag, $this>
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    /**
     * @param  array<int>  $tagIds
     */
    public function syncTags(array $tagIds): void
    {
        $this->tags()->sync($tagIds);
    }

    public function attachTag(int|Tag $tag): void
    {
        $tagId = $tag instanceof Tag ? $tag->id : $tag;
        $this->tags()->syncWithoutDetaching([$tagId]);
    }

    public function detachTag(int|Tag $tag): void
    {
        $tagId = $tag instanceof Tag ? $tag->id : $tag;
        $this->tags()->detach($tagId);
    }

    public function hasTag(int|Tag $tag): bool
    {
        $tagId = $tag instanceof Tag ? $tag->id : $tag;

        return $this->tags()->where('tags.id', $tagId)->exists();
    }

    /**
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @param  array<int>  $tagIds
     * @return Builder<\Illuminate\Database\Eloquent\Model>
     */
    public function scopeWithAnyTags(Builder $query, array $tagIds): Builder
    {
        return $query->whereHas('tags', fn ($q) => $q->whereIn('tags.id', $tagIds));
    }

    /**
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @param  array<int>  $tagIds
     * @return Builder<\Illuminate\Database\Eloquent\Model>
     */
    public function scopeWithAllTags(Builder $query, array $tagIds): Builder
    {
        foreach ($tagIds as $tagId) {
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tagId));
        }

        return $query;
    }
}
