<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Model Pengeluaran untuk mengelola data pengeluaran keuangan UKM Jurnalistik
 * 
 * @property int $id
 * @property string $kode_transaksi
 * @property string $keperluan
 * @property string $deskripsi
 * @property float $jumlah
 * @property Carbon $tanggal_pengeluaran
 * @property string $kategori
 * @property string $metode_pembayaran
 * @property string|null $nomor_referensi
 * @property string $penerima
 * @property string|null $bukti_pengeluaran
 * @property string|null $keterangan
 * @property string $status
 * @property int $created_by
 * @property int|null $approved_by
 * @property Carbon|null $approved_at
 * @property int|null $paid_by
 * @property Carbon|null $paid_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Pengeluaran extends Model
{
    use HasFactory;

    /**
     * Nama tabel database
     */
    protected $table = 'pengeluaran';

    /**
     * Kolom yang dapat diisi secara mass assignment
     */
    protected $fillable = [
        'kode_transaksi',
        'keperluan',
        'deskripsi',
        'jumlah',
        'tanggal_pengeluaran',
        'kategori',
        'metode_pembayaran',
        'nomor_referensi',
        'penerima',
        'bukti_pengeluaran',
        'keterangan',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
        'paid_by',
        'paid_at',
    ];

    /**
     * Kolom yang harus di-cast ke tipe data tertentu
     */
    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_pengeluaran' => 'date',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Konstanta untuk kategori pengeluaran
     */
    const KATEGORI_OPERASIONAL = 'operasional';
    const KATEGORI_ACARA = 'acara';
    const KATEGORI_PERALATAN = 'peralatan';
    const KATEGORI_KONSUMSI = 'konsumsi';
    const KATEGORI_TRANSPORT = 'transport';
    const KATEGORI_ADMINISTRASI = 'administrasi';
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
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PAID = 'paid';

    /**
     * Relasi ke model User (pembuat record)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke model User (yang menyetujui)
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Relasi ke model User (yang melakukan pembayaran)
     */
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    /**
     * Mendapatkan semua kategori pengeluaran yang tersedia
     */
    public static function getAllKategori(): array
    {
        return [
            self::KATEGORI_OPERASIONAL => 'Operasional',
            self::KATEGORI_ACARA => 'Acara',
            self::KATEGORI_PERALATAN => 'Peralatan',
            self::KATEGORI_KONSUMSI => 'Konsumsi',
            self::KATEGORI_TRANSPORT => 'Transport',
            self::KATEGORI_ADMINISTRASI => 'Administrasi',
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
     * Mendapatkan semua status yang tersedia
     */
    public static function getAllStatus(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_PAID => 'Paid',
        ];
    }

    /**
     * Generate kode transaksi unik
     */
    public static function generateKodeTransaksi(): string
    {
        $prefix = 'OUT';
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
     * Mengecek apakah pengeluaran masih pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Mengecek apakah pengeluaran sudah disetujui
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Mengecek apakah pengeluaran ditolak
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Mengecek apakah pengeluaran sudah dibayar
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Setujui pengeluaran
     */
    public function approve(int $approvedBy): bool
    {
        $this->status = self::STATUS_APPROVED;
        $this->approved_by = $approvedBy;
        $this->approved_at = now();
        
        return $this->save();
    }

    /**
     * Tolak pengeluaran
     */
    public function reject(int $approvedBy): bool
    {
        $this->status = self::STATUS_REJECTED;
        $this->approved_by = $approvedBy;
        $this->approved_at = now();
        
        return $this->save();
    }

    /**
     * Tandai sebagai sudah dibayar
     */
    public function markAsPaid(int $paidBy): bool
    {
        if (!$this->isApproved()) {
            return false;
        }
        
        $this->status = self::STATUS_PAID;
        $this->paid_by = $paidBy;
        $this->paid_at = now();
        
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
            return $query->whereBetween('tanggal_pengeluaran', [$startDate, $endDate]);
        }
        
        return $query->whereDate('tanggal_pengeluaran', $startDate);
    }

    /**
     * Scope untuk pengeluaran yang masih pending
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope untuk pengeluaran yang sudah disetujui
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope untuk pengeluaran yang sudah dibayar
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Scope untuk pengeluaran yang perlu approval
     */
    public function scopeNeedApproval($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope untuk pengeluaran yang siap dibayar
     */
    public function scopeReadyToPay($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
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
}