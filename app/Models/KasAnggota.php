<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * Model KasAnggota untuk mengelola data kas anggota UKM Jurnalistik
 * 
 * @property int $id
 * @property int $user_id
 * @property float $jumlah_terbayar
 * @property string $periode
 * @property int $tahun
 * @property string $status_pembayaran
 * @property Carbon|null $tanggal_pembayaran
 * @property string|null $keterangan
 * @property int $created_by
 * @property int|null $updated_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class KasAnggota extends Model
{
    use HasFactory;

    /**
     * Nama tabel database
     */
    protected $table = 'kas_anggota';

    /**
     * Kolom yang dapat diisi secara mass assignment
     */
    protected $fillable = [
        'user_id',
        'jumlah_terbayar',
        'periode',
        'tahun',
        'status_pembayaran',
        'tanggal_pembayaran',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    /**
     * Kolom yang harus di-cast ke tipe data tertentu
     */
    protected $casts = [
        'jumlah_terbayar' => 'decimal:2',
        'tahun' => 'integer',
        'tanggal_pembayaran' => 'date',
    ];

    /**
     * Konstanta untuk status pembayaran
     */
    const STATUS_BELUM_BAYAR = 'belum_bayar';
    const STATUS_SEBAGIAN = 'sebagian';
    const STATUS_LUNAS = 'lunas';
    const STATUS_TERLAMBAT = 'terlambat';

    /**
     * Konstanta untuk periode kas
     */
    const PERIODE_JANUARI = 'januari';
    const PERIODE_FEBRUARI = 'februari';
    const PERIODE_MARET = 'maret';
    const PERIODE_APRIL = 'april';
    const PERIODE_MEI = 'mei';
    const PERIODE_JUNI = 'juni';
    const PERIODE_JULI = 'juli';
    const PERIODE_AGUSTUS = 'agustus';
    const PERIODE_SEPTEMBER = 'september';
    const PERIODE_OKTOBER = 'oktober';
    const PERIODE_NOVEMBER = 'november';
    const PERIODE_DESEMBER = 'desember';

    /**
     * Relasi ke model User (anggota)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model User (pembuat record)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke model User (yang terakhir update)
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relasi ke model Pemasukan (kas yang sudah dibayar)
     */
    public function pemasukan(): HasMany
    {
        return $this->hasMany(Pemasukan::class, 'kas_anggota_id');
    }

    /**
     * Mendapatkan semua periode kas yang tersedia
     */
    public static function getAllPeriode(): array
    {
        return [
            self::PERIODE_JANUARI => 'Januari',
            self::PERIODE_FEBRUARI => 'Februari',
            self::PERIODE_MARET => 'Maret',
            self::PERIODE_APRIL => 'April',
            self::PERIODE_MEI => 'Mei',
            self::PERIODE_JUNI => 'Juni',
            self::PERIODE_JULI => 'Juli',
            self::PERIODE_AGUSTUS => 'Agustus',
            self::PERIODE_SEPTEMBER => 'September',
            self::PERIODE_OKTOBER => 'Oktober',
            self::PERIODE_NOVEMBER => 'November',
            self::PERIODE_DESEMBER => 'Desember',
        ];
    }

    /**
     * Mendapatkan semua status pembayaran yang tersedia
     */
    public static function getAllStatusPembayaran(): array
    {
        return [
            self::STATUS_BELUM_BAYAR => 'Belum Bayar',
            self::STATUS_SEBAGIAN => 'Sebagian',
            self::STATUS_LUNAS => 'Lunas',
            self::STATUS_TERLAMBAT => 'Terlambat',
        ];
    }

    /**
     * Mengecek apakah kas sudah lunas
     */
    public function isLunas(): bool
    {
        return $this->status_pembayaran === self::STATUS_LUNAS;
    }

    /**
     * Mengecek apakah kas terlambat
     */
    public function isTerlambat(): bool
    {
        return $this->status_pembayaran === self::STATUS_TERLAMBAT;
    }

    /**
     * Mendapatkan jumlah kas standar dari pengaturan
     */
    public static function getStandardAmount(): float
    {
        return (float) KasSetting::getValue('jumlah_kas_anggota', 15000);
    }

    /**
     * Menghitung persentase pembayaran (berdasarkan pengaturan kas)
     */
    public function getPersentasePembayaran(): float
    {
        $standardAmount = self::getStandardAmount();
        
        if ($standardAmount <= 0) {
            return 0;
        }
        
        return ($this->jumlah_terbayar / $standardAmount) * 100;
    }

    /**
     * Update status pembayaran berdasarkan jumlah terbayar
     */
    public function updateStatusPembayaran(): void
    {
        $standardAmount = self::getStandardAmount();
        
        if ($this->jumlah_terbayar >= $standardAmount) {
            $this->status_pembayaran = self::STATUS_LUNAS;
            $this->tanggal_pembayaran = now();
        } elseif ($this->jumlah_terbayar > 0) {
            $this->status_pembayaran = self::STATUS_SEBAGIAN;
        } else {
            $this->status_pembayaran = self::STATUS_BELUM_BAYAR;
        }
        
        $this->save();
    }

    /**
     * Scope untuk filter berdasarkan periode dan tahun
     */
    public function scopeByPeriode($query, string $periode, int $tahun)
    {
        return $query->where('periode', $periode)->where('tahun', $tahun);
    }

    /**
     * Scope untuk filter berdasarkan status pembayaran
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status_pembayaran', $status);
    }

    /**
     * Scope untuk kas yang terlambat
     */
    public function scopeTerlambat($query)
    {
        return $query->where('status_pembayaran', self::STATUS_TERLAMBAT);
    }

    /**
     * Scope untuk kas yang belum lunas
     */
    public function scopeBelumLunas($query)
    {
        return $query->whereNotIn('status_pembayaran', [self::STATUS_LUNAS]);
    }
}