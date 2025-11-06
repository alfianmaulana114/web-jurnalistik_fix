<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absen extends Model
{
    use HasFactory;

    protected $table = 'absen';

    protected $fillable = [
        'user_id',
        'tanggal',
        'status',
        'keterangan',
        'notulensi_id',
        'bulan',
        'tahun',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    const STATUS_HADIR = 'hadir';
    const STATUS_IZIN = 'izin';
    const STATUS_SAKIT = 'sakit';
    const STATUS_TIDAK_HADIR = 'tidak_hadir';

    const BULAN_JANUARI = 'januari';
    const BULAN_FEBRUARI = 'februari';
    const BULAN_MARET = 'maret';
    const BULAN_APRIL = 'april';
    const BULAN_MEI = 'mei';
    const BULAN_JUNI = 'juni';
    const BULAN_JULI = 'juli';
    const BULAN_AGUSTUS = 'agustus';
    const BULAN_SEPTEMBER = 'september';
    const BULAN_OKTOBER = 'oktober';
    const BULAN_NOVEMBER = 'november';
    const BULAN_DESEMBER = 'desember';

    public static function getAllStatus(): array
    {
        return [
            self::STATUS_HADIR => 'Hadir',
            self::STATUS_IZIN => 'Izin',
            self::STATUS_SAKIT => 'Sakit',
            self::STATUS_TIDAK_HADIR => 'Tidak Hadir',
        ];
    }

    public static function getAllBulan(): array
    {
        return [
            self::BULAN_JANUARI => 'Januari',
            self::BULAN_FEBRUARI => 'Februari',
            self::BULAN_MARET => 'Maret',
            self::BULAN_APRIL => 'April',
            self::BULAN_MEI => 'Mei',
            self::BULAN_JUNI => 'Juni',
            self::BULAN_JULI => 'Juli',
            self::BULAN_AGUSTUS => 'Agustus',
            self::BULAN_SEPTEMBER => 'September',
            self::BULAN_OKTOBER => 'Oktober',
            self::BULAN_NOVEMBER => 'November',
            self::BULAN_DESEMBER => 'Desember',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notulensi(): BelongsTo
    {
        return $this->belongsTo(Notulensi::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getStatusLabel(): string
    {
        return self::getAllStatus()[$this->status] ?? $this->status;
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_HADIR => 'bg-green-100 text-green-800',
            self::STATUS_IZIN => 'bg-yellow-100 text-yellow-800',
            self::STATUS_SAKIT => 'bg-blue-100 text-blue-800',
            self::STATUS_TIDAK_HADIR => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}

