<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nim',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Role constants untuk UKM Jurnalistik
     */
    const ROLE_KOORDINATOR_JURNALISTIK = 'koordinator_jurnalistik';
    const ROLE_SEKRETARIS = 'sekretaris';
    const ROLE_BENDAHARA = 'bendahara';
    const ROLE_KOORDINATOR_REDAKSI = 'koordinator_redaksi';
    const ROLE_KOORDINATOR_LITBANG = 'koordinator_litbang';
    const ROLE_KOORDINATOR_HUMAS = 'koordinator_humas';
    const ROLE_KOORDINATOR_MEDIA_KREATIF = 'koordinator_media_kreatif';
    const ROLE_ANGGOTA_REDAKSI = 'anggota_redaksi';
    const ROLE_ANGGOTA_LITBANG = 'anggota_litbang';
    const ROLE_ANGGOTA_HUMAS = 'anggota_humas';
    const ROLE_ANGGOTA_MEDIA_KREATIF = 'anggota_media_kreatif';

    /**
     * Get all available roles
     */
    public static function getAllRoles(): array
    {
        return [
            self::ROLE_KOORDINATOR_JURNALISTIK => 'Koordinator Jurnalistik',
            self::ROLE_SEKRETARIS => 'Sekretaris',
            self::ROLE_BENDAHARA => 'Bendahara',
            self::ROLE_KOORDINATOR_REDAKSI => 'Koordinator Divisi Redaksi',
            self::ROLE_KOORDINATOR_LITBANG => 'Koordinator Divisi Litbang',
            self::ROLE_KOORDINATOR_HUMAS => 'Koordinator Divisi Humas',
            self::ROLE_KOORDINATOR_MEDIA_KREATIF => 'Koordinator Divisi Media Kreatif',
            self::ROLE_ANGGOTA_REDAKSI => 'Anggota Divisi Redaksi',
            self::ROLE_ANGGOTA_LITBANG => 'Anggota Divisi Litbang',
            self::ROLE_ANGGOTA_HUMAS => 'Anggota Divisi Humas',
            self::ROLE_ANGGOTA_MEDIA_KREATIF => 'Anggota Divisi Media Kreatif',
        ];
    }

    /**
     * Get dashboard route based on user role
     */
    public function getDashboardRoute(): string
    {
        return match($this->role) {
            self::ROLE_KOORDINATOR_JURNALISTIK => route('koordinator-jurnalistik.dashboard'),
            self::ROLE_BENDAHARA => route('bendahara.dashboard'),
            self::ROLE_SEKRETARIS => route('sekretaris.dashboard'),
            self::ROLE_KOORDINATOR_REDAKSI => route('koordinator-redaksi.dashboard'),
            self::ROLE_KOORDINATOR_LITBANG => route('koordinator-litbang.dashboard'),
            self::ROLE_ANGGOTA_LITBANG => route('anggota-litbang.dashboard'),
            default => route('home'),
        };
    }

    /**
     * Check if user is koordinator jurnalistik
     */
    public function isKoordinatorJurnalistik(): bool
    {
        return $this->role === self::ROLE_KOORDINATOR_JURNALISTIK;
    }

    /**
     * Check if user is koordinator of any division
     */
    public function isKoordinator(): bool
    {
        return in_array($this->role, [
            self::ROLE_KOORDINATOR_JURNALISTIK,
            self::ROLE_KOORDINATOR_REDAKSI,
            self::ROLE_KOORDINATOR_LITBANG,
            self::ROLE_KOORDINATOR_HUMAS,
            self::ROLE_KOORDINATOR_MEDIA_KREATIF,
        ]);
    }

    /**
     * Get user's division
     */
    public function getDivision(): string
    {
        switch ($this->role) {
            case self::ROLE_KOORDINATOR_REDAKSI:
            case self::ROLE_ANGGOTA_REDAKSI:
                return 'redaksi';
            case self::ROLE_KOORDINATOR_LITBANG:
            case self::ROLE_ANGGOTA_LITBANG:
                return 'litbang';
            case self::ROLE_KOORDINATOR_HUMAS:
            case self::ROLE_ANGGOTA_HUMAS:
                return 'humas';
            case self::ROLE_KOORDINATOR_MEDIA_KREATIF:
            case self::ROLE_ANGGOTA_MEDIA_KREATIF:
                return 'media_kreatif';
            default:
                return 'pengurus';
        }
    }

    /**
     * Get all news created by this user
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    /**
     * Get all prokers created by this user
     */
    public function createdProkers(): HasMany
    {
        return $this->hasMany(Proker::class, 'created_by');
    }

    /**
     * Get all prokers where this user is a committee member
     */
    public function prokers(): BelongsToMany
    {
        return $this->belongsToMany(Proker::class, 'proker_panitias')
                    ->withPivot('jabatan_panitia', 'tugas_khusus')
                    ->withTimestamps();
    }

    /**
     * Get all briefs created by this user
     */
    public function createdBriefs(): HasMany
    {
        return $this->hasMany(Brief::class, 'created_by');
    }

    /**
     * Get all briefs assigned to this user
     */
    public function assignedBriefs(): HasMany
    {
        return $this->hasMany(Brief::class, 'assigned_to');
    }

    /**
     * Get all contents created by this user
     */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class, 'created_by');
    }

    /**
     * Get all contents reviewed by this user
     */
    public function reviewedContents(): HasMany
    {
        return $this->hasMany(Content::class, 'reviewed_by');
    }

    /**
     * Get all designs created by this user
     */
    public function designs(): HasMany
    {
        return $this->hasMany(Design::class, 'created_by');
    }

    /**
     * Get all designs reviewed by this user
     */
    public function reviewedDesigns(): HasMany
    {
        return $this->hasMany(Design::class, 'reviewed_by');
    }

    /**
     * Check if user is bendahara
     */
    public function isBendahara(): bool
    {
        return $this->role === self::ROLE_BENDAHARA;
    }

    /**
     * Check if user is sekretaris
     */
    public function isSekretaris(): bool
    {
        return $this->role === self::ROLE_SEKRETARIS;
    }

    /**
     * Check if user is koordinator redaksi
     */
    public function isKoordinatorRedaksi(): bool
    {
        return $this->role === self::ROLE_KOORDINATOR_REDAKSI;
    }

    /**
     * Get all kas anggota records for this user
     */
    public function kasAnggota(): HasMany
    {
        return $this->hasMany(KasAnggota::class, 'user_id');
    }

    /**
     * Get all notulensi records created by this user
     */
    public function createdNotulensi(): HasMany
    {
        return $this->hasMany(Notulensi::class, 'created_by');
    }

    /**
     * Get all absen records for this user
     */
    public function absens(): HasMany
    {
        return $this->hasMany(Absen::class, 'user_id');
    }

    /**
     * Get all kas anggota records created by this user (bendahara)
     */
    public function createdKasAnggota(): HasMany
    {
        return $this->hasMany(KasAnggota::class, 'created_by');
    }

    /**
     * Get all pemasukan records created by this user
     */
    public function createdPemasukan(): HasMany
    {
        return $this->hasMany(Pemasukan::class, 'created_by');
    }

    /**
     * Get all pemasukan records verified by this user
     */
    public function verifiedPemasukan(): HasMany
    {
        return $this->hasMany(Pemasukan::class, 'verified_by');
    }

    /**
     * Get all pemasukan records from this user (kas anggota)
     */
    public function pemasukan(): HasMany
    {
        return $this->hasMany(Pemasukan::class, 'user_id');
    }

    /**
     * Get all pengeluaran records created by this user
     */
    public function createdPengeluaran(): HasMany
    {
        return $this->hasMany(Pengeluaran::class, 'created_by');
    }

    /**
     * Get all pengeluaran records approved by this user
     */
    public function approvedPengeluaran(): HasMany
    {
        return $this->hasMany(Pengeluaran::class, 'approved_by');
    }

    /**
     * Get all pengeluaran records paid by this user
     */
    public function paidPengeluaran(): HasMany
    {
        return $this->hasMany(Pengeluaran::class, 'paid_by');
    }
}
