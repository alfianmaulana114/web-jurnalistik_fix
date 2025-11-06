<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Model Pemasukan untuk mengelola data pemasukan keuangan UKM Jurnalistik
 * 
 * @property int $id
 * @property string $kode_transaksi
 * @property string $sumber_pemasukan
 * @property string $deskripsi
 * @property float $jumlah
 * @property Carbon $tanggal_pemasukan
 * @property string $kategori
 * @property string $metode_pembayaran
 * @property string|null $nomor_referensi
 * @property int|null $kas_anggota_id
 * @property int|null $user_id
 * @property string|null $bukti_pemasukan
 * @property string|null $keterangan
 * @property string $status
 * @property int $created_by
 * @property int|null $verified_by
 * @property Carbon|null $verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Pemasukan extends Model
{
    use HasFactory;

    /**
     * Nama tabel database
     */
    protected $table = 'pemasukan';

    /**
     * Kolom yang dapat diisi secara mass assignment
     */
    protected $fillable = [
        'kode_transaksi',
        'sumber_pemasukan',
        'deskripsi',
        'jumlah',
        'tanggal_pemasukan',
        'kategori',
        'metode_pembayaran',
        'nomor_referensi',
        'kas_anggota_id',
        'user_id',
        'bukti_pemasukan',
        'keterangan',
        'status',
        'created_by',
        'verified_by',
        'verified_at',
    ];

    /**
     * Kolom yang harus di-cast ke tipe data tertentu
     */
    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_pemasukan' => 'date',
        'verified_at' => 'datetime',
    ];

    /**
     * Konstanta untuk kategori pemasukan
     */
    const KATEGORI_KAS_ANGGOTA = 'kas_anggota';
    const KATEGORI_DONASI = 'donasi';
    const KATEGORI_SPONSOR = 'sponsor';
    const KATEGORI_PENJUALAN = 'penjualan';
    const KATEGORI_HIBAH = 'hibah';
    const KATEGORI_LAINNYA = 'lainnya';

    /**
     * Konstanta untuk metode pembayaran
     */
    const METODE_TUNAI = 'tunai';
    const METODE_TRANSFER_BANK = 'transfer_bank';
    const METODE_E_WALLET = 'e_wallet';
    const METODE_CEK = 'cek';
    const METODE_LAINNYA = 'lainnya';

    /**
     * Konstanta untuk status
     */
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';

    /**
     * Relasi ke model KasAnggota
     */
    public function kasAnggota(): BelongsTo
    {
        return $this->belongsTo(KasAnggota::class, 'kas_anggota_id');
    }

    /**
     * Relasi ke model User (anggota yang membayar kas)
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
     * Relasi ke model User (yang memverifikasi)
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Mendapatkan semua kategori pemasukan yang tersedia
     */
    public static function getAllKategori(): array
    {
        return [
            self::KATEGORI_KAS_ANGGOTA => 'Kas Anggota',
            self::KATEGORI_DONASI => 'Donasi',
            self::KATEGORI_SPONSOR => 'Sponsor',
            self::KATEGORI_PENJUALAN => 'Penjualan',
            self::KATEGORI_HIBAH => 'Hibah',
            self::KATEGORI_LAINNYA => 'Lainnya',
        ];
    }

    /**
     * Mendapatkan semua metode pembayaran yang tersedia
     */
    public static function getAllMetodePembayaran(): array
    {
        return [
            self::METODE_TUNAI => 'Tunai',
            self::METODE_TRANSFER_BANK => 'Transfer Bank',
            self::METODE_E_WALLET => 'E-Wallet',
            self::METODE_CEK => 'Cek',
            self::METODE_LAINNYA => 'Lainnya',
        ];
    }

    /**
     * Mendapatkan opsi metode pembayaran untuk dropdown
     */
    public static function getMetodePembayaranOptions(): array
    {
        return self::getAllMetodePembayaran();
    }

    /**
     * Mendapatkan semua status yang tersedia
     */
    public static function getAllStatus(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_VERIFIED => 'Verified',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    /**
     * Generate kode transaksi unik
     */
    public static function generateKodeTransaksi(): string
    {
        $prefix = 'IN';
        $date = now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', now())
                              ->where('kode_transaksi', 'like', $prefix . $date . '%')
                              ->orderBy('kode_transaksi', 'desc')
                              ->first();
        
        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->kode_transaksi, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Mengecek apakah pemasukan sudah diverifikasi
     */
    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    /**
     * Mengecek apakah pemasukan ditolak
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Mengecek apakah pemasukan masih pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Verifikasi pemasukan
     */
    public function verify(int $verifiedBy): bool
    {
        $this->status = self::STATUS_VERIFIED;
        $this->verified_by = $verifiedBy;
        $this->verified_at = now();
        
        $result = $this->save();
        
        // Jika pemasukan dari kas anggota, update status pembayaran kas anggota
        if ($result && $this->kas_anggota_id && $this->kasAnggota) {
            $this->kasAnggota->jumlah_terbayar += $this->jumlah;
            $this->kasAnggota->updateStatusPembayaran();
        }
        
        return $result;
    }

    /**
     * Tolak pemasukan
     */
    public function reject(int $verifiedBy): bool
    {
        $this->status = self::STATUS_REJECTED;
        $this->verified_by = $verifiedBy;
        $this->verified_at = now();
        
        return $this->save();
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByKategori($query, string $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeByTanggal($query, Carbon $startDate, Carbon $endDate = null)
    {
        if ($endDate) {
            return $query->whereBetween('tanggal_pemasukan', [$startDate, $endDate]);
        }
        
        return $query->whereDate('tanggal_pemasukan', $startDate);
    }

    /**
     * Scope untuk pemasukan yang sudah diverifikasi
     */
    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED);
    }

    /**
     * Scope untuk pemasukan yang masih pending
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Boot method untuk auto-generate kode transaksi
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->kode_transaksi)) {
                $model->kode_transaksi = self::generateKodeTransaksi();
            }
        });
    }

    /**
     * Mendapatkan opsi kategori untuk dropdown
     */
    public static function getKategoriOptions(): array
    {
        return self::getAllKategori();
    }

    /**
     * Mendapatkan opsi status untuk dropdown
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu Verifikasi',
            self::STATUS_VERIFIED => 'Terverifikasi',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    /**
     * Mendapatkan label status yang lebih user-friendly
     */
    public function getStatusLabel(): string
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Mendapatkan label kategori yang lebih user-friendly
     */
    public function getKategoriLabel(): string
    {
        return self::getAllKategori()[$this->kategori] ?? $this->kategori;
    }

    /**
     * Accessor untuk mendapatkan tanggal dari tanggal_pemasukan
     */
    public function getTanggalAttribute()
    {
        return $this->tanggal_pemasukan;
    }
}