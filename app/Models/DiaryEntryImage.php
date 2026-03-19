<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $diary_entry_id
 * @property string $path
 * @property string $disk
 * @property string $original_name
 * @property string|null $caption
 * @property int $order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read DiaryEntry $diaryEntry
 */
final class DiaryEntryImage extends Model
{
    protected $fillable = [
        'diary_entry_id',
        'path',
        'disk',
        'original_name',
        'caption',
        'order',
    ];

    /**
     * @return BelongsTo<DiaryEntry, $this>
     */
    public function diaryEntry(): BelongsTo
    {
        return $this->belongsTo(DiaryEntry::class);
    }

    public function getUrlAttribute(): string
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk($this->disk);

        return $disk->url($this->path);
    }
}
