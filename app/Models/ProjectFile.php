<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProjectFileFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $project_id
 * @property string $name
 * @property string $original_name
 * @property string $path
 * @property string $disk
 * @property string $mime_type
 * @property int $size
 * @property string $type
 * @property string|null $description
 * @property int $order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Project $project
 */
final class ProjectFile extends Model
{
    /** @use HasFactory<ProjectFileFactory> */
    use HasFactory;

    public const TYPE_IMAGE = 'image';

    public const TYPE_STL = 'stl';

    public const TYPE_PDF = 'pdf';

    public const TYPE_ATTACHMENT = 'attachment';

    protected $fillable = [
        'project_id',
        'name',
        'original_name',
        'path',
        'disk',
        'mime_type',
        'size',
        'type',
        'description',
        'order',
    ];

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @param  Builder<ProjectFile>  $query
     * @return Builder<ProjectFile>
     */
    public function scopeImages(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_IMAGE);
    }

    /**
     * @param  Builder<ProjectFile>  $query
     * @return Builder<ProjectFile>
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function getUrlAttribute(): string
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk($this->disk);

        return $disk->url($this->path);
    }

    public function getSizeFormattedAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public static function detectType(string $mimeType, string $extension): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return self::TYPE_IMAGE;
        }

        if ($extension === 'stl' || $mimeType === 'model/stl') {
            return self::TYPE_STL;
        }

        if ($mimeType === 'application/pdf') {
            return self::TYPE_PDF;
        }

        return self::TYPE_ATTACHMENT;
    }
}
