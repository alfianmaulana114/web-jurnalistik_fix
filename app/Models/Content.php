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
        'desain_id',
        'berita_id',
        'brief_id',
        'created_by',
        'published_at',
        'platform_upload',
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
    const TYPE_CAPTION_DESAIN = 'caption_desain';

    

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
            self::TYPE_CAPTION_DESAIN => 'Caption Desain',
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

    public function translations(): HasMany
    {
        return $this->hasMany(ContentTranslation::class);
    }

    /**
     * Get the design related to this content (for caption desain)
     */
    public function desain(): BelongsTo
    {
        return $this->belongsTo(Design::class, 'desain_id');
    }

    /**
     * Get the news related to this content (for caption berita)
     */
    public function berita(): BelongsTo
    {
        return $this->belongsTo(News::class, 'berita_id');
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
     * Check if this is a design caption
     */
    public function isCaptionDesain(): bool
    {
        return $this->jenis_konten === self::TYPE_CAPTION_DESAIN;
    }

    /**
     * Check if this is a media caption
     */
    public function isCaptionMediaKreatif(): bool
    {
        return $this->jenis_konten === self::TYPE_CAPTION_MEDIA_KREATIF;
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

}