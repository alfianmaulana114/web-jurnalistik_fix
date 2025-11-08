<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Design extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        // Simplified design structure
        'media_url',
        'jenis',
        'catatan',
        'berita_id',
        // Legacy for compatibility (kept but not used in new flow)
        'created_by',
    ];

    /**
     * Design type constants
     */
    const TYPE_POSTER = 'poster';
    const TYPE_BANNER = 'banner';
    const TYPE_INFOGRAFIS = 'infografis';
    const TYPE_LOGO = 'logo';
    const TYPE_THUMBNAIL = 'thumbnail';
    const TYPE_COVER = 'cover';
    const TYPE_ILUSTRASI = 'ilustrasi';
    const TYPE_VIDEO = 'video';

    /**
     * Status constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_REVIEW = 'review';
    const STATUS_APPROVED = 'approved';
    const STATUS_PUBLISHED = 'published';
    const STATUS_REJECTED = 'rejected';

    /**
     * Simplified jenis options
     */
    const JENIS_DESAIN = 'desain';
    const JENIS_VIDEO = 'video';
    const JENIS_FUNFACT = 'funfact';

    public static function getJenisOptions(): array
    {
        return [
            self::JENIS_DESAIN => 'Desain',
            self::JENIS_VIDEO => 'Video',
            self::JENIS_FUNFACT => 'Funfact',
        ];
    }

    /**
     * Get all available design types
     */
    public static function getAllTypes(): array
    {
        return [
            self::TYPE_POSTER => 'Poster',
            self::TYPE_BANNER => 'Banner',
            self::TYPE_INFOGRAFIS => 'Infografis',
            self::TYPE_LOGO => 'Logo',
            self::TYPE_THUMBNAIL => 'Thumbnail',
            self::TYPE_COVER => 'Cover',
            self::TYPE_ILUSTRASI => 'Ilustrasi',
            self::TYPE_VIDEO => 'Video',
        ];
    }

    /**
     * Get all available statuses
     */
    public static function getAllStatuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_REVIEW => 'Sedang Direview',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_PUBLISHED => 'Dipublikasi',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    /**
     * Get the content related to this design
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    /**
     * Get the proker related to this design
     */
    public function proker(): BelongsTo
    {
        return $this->belongsTo(Proker::class);
    }

    /**
     * Get the news related to this design
     */
    public function berita(): BelongsTo
    {
        return $this->belongsTo(News::class, 'berita_id');
    }

    /**
     * Get the user who created this design
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who reviewed this design
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Check if design is published
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Check if design needs review
     */
    public function needsReview(): bool
    {
        return $this->status === self::STATUS_REVIEW;
    }

    /**
     * Get design type label
     */
    public function getTypeLabel(): string
    {
        return self::getAllTypes()[$this->jenis_desain] ?? $this->jenis_desain;
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return self::getAllStatuses()[$this->status] ?? ($this->status ?? '');
    }

    /**
     * Get status color class for UI
     */
    public function getStatusColorClass(): string
    {
        switch ($this->status) {
            case self::STATUS_PUBLISHED:
                return 'text-green-600 bg-green-100';
            case self::STATUS_APPROVED:
                return 'text-blue-600 bg-blue-100';
            case self::STATUS_REVIEW:
                return 'text-yellow-600 bg-yellow-100';
            case self::STATUS_REJECTED:
                return 'text-red-600 bg-red-100';
            case self::STATUS_DRAFT:
                return 'text-gray-600 bg-gray-100';
            default:
                return 'text-gray-600 bg-gray-100';
        }
    }

    /**
     * Get file URL
     */
    public function getFileUrl(): string
    {
        return $this->media_url ?? '';
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSize(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if design is an image
     */
    public function isImage(): bool
    {
        return $this->jenis === self::JENIS_DESAIN;
    }

    /**
     * Check if design is a video
     */
    public function isVideo(): bool
    {
        return $this->jenis === self::JENIS_VIDEO;
    }

    /**
     * Check if design is a funfact
     */
    public function isFunfact(): bool
    {
        return $this->jenis === self::JENIS_FUNFACT;
    }

    /**
     * Scope for published designs
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Scope for designs needing review
     */
    public function scopeNeedsReview($query)
    {
        return $query->where('status', self::STATUS_REVIEW);
    }

    /**
     * Scope for approved designs
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for image designs
     */
    public function scopeImages($query)
    {
        return $query->whereIn('jenis_desain', [
            self::TYPE_POSTER,
            self::TYPE_BANNER,
            self::TYPE_INFOGRAFIS,
            self::TYPE_LOGO,
            self::TYPE_THUMBNAIL,
            self::TYPE_COVER,
            self::TYPE_ILUSTRASI,
        ]);
    }

    /**
     * Scope for video designs
     */
    public function scopeVideos($query)
    {
        return $query->where('jenis_desain', self::TYPE_VIDEO);
    }
}