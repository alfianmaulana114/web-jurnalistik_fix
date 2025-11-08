# Migration Final per Table

Folder ini berisi migration final yang sudah dikonsolidasi per table untuk fresh install.

**CATATAN PENTING:**
- Migration ini hanya untuk referensi fresh install
- JANGAN menjalankan migration ini di database yang sudah ada
- Migration lama tetap digunakan untuk project yang sudah running
- Jika ingin fresh install, gunakan migration di folder `database/migrations` (yang sudah ada)

## Struktur Migration Final

Migration final ini mengkonsolidasi semua perubahan menjadi satu file per table:

1. `2025_01_20_000001_final_create_users_table.php` - Tabel users dengan semua kolom final
2. `2025_01_20_000002_final_create_news_table.php` - Tabel news dengan semua kolom final
3. `2025_01_20_000003_final_create_comments_table.php` - Tabel comments
4. `2025_01_20_000004_final_create_news_categories_and_types.php` - Tabel news_categories, news_types, news_genres
5. `2025_01_20_000005_final_create_prokers_table.php` - Tabel prokers
6. `2025_01_20_000006_final_create_proker_panitias_table.php` - Tabel proker_panitias
7. `2025_01_20_000007_final_create_briefs_table.php` - Tabel briefs dengan struktur final
8. `2025_01_20_000008_final_create_contents_table.php` - Tabel contents dengan struktur final
9. `2025_01_20_000009_final_create_designs_table.php` - Tabel designs dengan struktur final (termasuk funfact)
10. `2025_01_20_000010_final_create_funfacts_table.php` - Tabel funfacts
11. `2025_01_20_000011_final_create_kas_anggota_table.php` - Tabel kas_anggota
12. `2025_01_20_000012_final_create_pemasukan_table.php` - Tabel pemasukan
13. `2025_01_20_000013_final_create_pengeluaran_table.php` - Tabel pengeluaran
14. `2025_01_20_000014_final_create_kas_settings_table.php` - Tabel kas_settings
15. `2025_01_20_000015_final_create_notulensi_and_absen_tables.php` - Tabel notulensi dan absen
16. `2025_01_20_000016_final_create_penjadwalan_table.php` - Tabel penjadwalan

## Cara Menggunakan (Fresh Install)

Jika ingin melakukan fresh install:

1. Backup database yang ada (jika ada)
2. Drop semua tabel
3. Copy migration final dari folder ini ke `database/migrations/`
4. Jalankan `php artisan migrate`
5. Jalankan `php artisan db:seed`

**ATAU** gunakan migration yang sudah ada di `database/migrations/` yang sudah teruji dan berjalan dengan baik.

