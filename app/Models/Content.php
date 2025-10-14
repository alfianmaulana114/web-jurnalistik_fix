<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'caption',
        'jenis_konten',
        'media_type',
        'media_path',
        'media_description',
        'berita_referensi',
        'sumber',
        'catatan_editor',
        'status',
        'brief_id',
        'created_by',
        'reviewed_by',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Caption type constants
     */
    const TYPE_CAPTION_BERITA = 'caption_berita';
    const TYPE_CAPTION_MEDIA_KREATIF = 'caption_media_kreatif';

    /**
     * Media type constants
     */
    const MEDIA_TYPE_FOTO = 'foto';
    const MEDIA_TYPE_VIDEO = 'video';

    /**
     * Status constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_REVIEW = 'review';
    const STATUS_APPROVED = 'approved';
    const STATUS_PUBLISHED = 'published';
    const STATUS_REJECTED = 'rejected';

    /**
     * Get all available caption types
     */
    public static function getCaptionTypes(): array
    {
        return [
            self::TYPE_CAPTION_BERITA => 'Caption Berita Redaksi',
            self::TYPE_CAPTION_MEDIA_KREATIF => 'Caption Media Kreatif',
        ];
    }

    /**
     * Get all available media types
     */
    public static function getMediaTypes(): array
    {
        return [
            self::MEDIA_TYPE_FOTO => 'Foto',
            self::MEDIA_TYPE_VIDEO => 'Video',
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
     * Get the brief related to this content
     */
    public function brief(): BelongsTo
    {
        return $this->belongsTo(Brief::class);
    }

    /**
     * Get the user who created this content
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who reviewed this content
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get designs related to this content
     */
    public function designs(): HasMany
    {
        return $this->hasMany(Design::class);
    }

    /**
     * Check if content is published
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Check if content needs review
     */
    public function needsReview(): bool
    {
        return $this->status === self::STATUS_REVIEW;
    }

    /**
     * Get caption type label
     */
    public function getTypeLabel(): string
    {
        return self::getCaptionTypes()[$this->jenis_konten] ?? $this->jenis_konten;
    }

    /**
     * Get media type label
     */
    public function getMediaTypeLabel(): string
    {
        return self::getMediaTypes()[$this->media_type] ?? $this->media_type;
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return self::getAllStatuses()[$this->status] ?? $this->status;
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
     * Get excerpt of caption
     */
    public function getExcerpt(int $length = 150): string
    {
        return strlen($this->caption) > $length 
            ? substr(strip_tags($this->caption), 0, $length) . '...'
            : strip_tags($this->caption);
    }

    /**
     * Check if this is a news caption
     */
    public function isCaptionBerita(): bool
    {
        return $this->jenis_konten === self::TYPE_CAPTION_BERITA;
    }

    /**
     * Check if this is a media caption
     */
    public function isCaptionMediaKreatif(): bool
    {
        return $this->jenis_konten === self::TYPE_CAPTION_MEDIA_KREATIF;
    }

    /**
     * Check if media is photo
     */
    public function isPhoto(): bool
    {
        return $this->media_type === self::MEDIA_TYPE_FOTO;
    }

    /**
     * Check if media is video
     */
    public function isVideo(): bool
    {
        return $this->media_type === self::MEDIA_TYPE_VIDEO;
    }

    /**
     * Scope for published contents
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Scope for contents needing review
     */
    public function scopeNeedsReview($query)
    {
        return $query->where('status', self::STATUS_REVIEW);
    }

    /**
     * Scope for approved contents
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for news captions
     */
    public function scopeCaptionBerita($query)
    {
        return $query->where('jenis_konten', self::TYPE_CAPTION_BERITA);
    }

    /**
     * Scope for media captions
     */
    public function scopeCaptionMediaKreatif($query)
    {
        return $query->where('jenis_konten', self::TYPE_CAPTION_MEDIA_KREATIF);
    }

    /**
     * Scope for photo captions
     */
    public function scopePhotoCaption($query)
    {
        return $query->where('media_type', self::MEDIA_TYPE_FOTO);
    }

    /**
     * Scope for video captions
     */
    public function scopeVideoCaption($query)
    {
        return $query->where('media_type', self::MEDIA_TYPE_VIDEO);
    }
}