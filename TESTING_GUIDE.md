# 🧪 Panduan Testing - Koordinator Jurnalistik Features

## Cara Test Fitur yang Telah Ditingkatkan

### 1. **Test Dashboard**

#### Langkah-langkah:
1. Login sebagai user dengan role `koordinator_jurnalistik`
2. Buka halaman Dashboard
3. Verifikasi yang ditampilkan:
   - ✅ Welcome banner dengan nama user
   - ✅ 4 kartu statistik (Berita, Anggota, Konten, Brief)
   - ✅ Panel "Saldo Keuangan" dengan:
     - Total saldo
     - Pemasukan
     - Pengeluaran
     - Pending transactions (jika ada)
   - ✅ Panel "Status Kas Anggota" dengan 4 status
   - ✅ Tombol "Lihat Detail" pada panel kas
   - ✅ 2 charts (Berita & Keuangan)
   - ✅ Statistik per divisi (6 card)
   - ✅ Status Program Kerja
   - ✅ Brief Mendesak
   - ✅ Berita Terbaru (grid 3 kolom)

#### Test Interaktif:
1. **Klik tombol "Lihat Detail"** pada panel kas:
   - Modal harus muncul
   - Tabel menampilkan anggota yang belum lunas
   - Test search di modal
   - Test filter by status
   - Klik "Tutup" atau backdrop untuk close

2. **Hover pada cards**:
   - Card harus scale up sedikit
   - Shadow harus bertambah

3. **Check charts**:
   - Hover pada data points
   - Tooltip harus muncul dengan data

### 2. **Test Halaman News Index**

#### Langkah-langkah:
1. Klik menu "Berita" di sidebar
2. Verifikasi yang ditampilkan:
   - ✅ Header dengan tombol "Tambah Berita Baru"
   - ✅ 3 kartu statistik
   - ✅ Search box
   - ✅ Tombol "Filter"
   - ✅ Tabel dengan thumbnail berita
   - ✅ Badge kategori dan tipe
   - ✅ Status approval
   - ✅ Action buttons (view, edit, delete)

#### Test Interaktif:
1. **Test Search**:
   - Ketik di search box
   - Hasil harus ter-filter real-time
   - Test dengan judul, kategori, nama penulis

2. **Test Filter**:
   - Klik tombol "Filter"
   - Advanced filters harus muncul
   - Pilih kategori/status
   - Hasil harus ter-filter

3. **Test Approve** (jika ada berita pending):
   - Klik tombol "Approve"
   - Status harus berubah menjadi "Disetujui"

### 3. **Test Halaman Funfacts Index**

#### Langkah-langkah:
1. Klik menu "Funfact" di sidebar
2. Verifikasi yang ditampilkan:
   - ✅ Header dengan tombol "Tambah Funfact Baru"
   - ✅ 2 kartu statistik
   - ✅ Search box
   - ✅ Grid cards (3 kolom di desktop)
   - ✅ Setiap card menampilkan:
     - Icon purple
     - Badge tanggal
     - Judul
     - Isi (preview)
     - Link referensi
     - Creator info
     - Action buttons

#### Test Interaktif:
1. **Test Search**:
   - Ketik di search box
   - Cards harus ter-filter dengan animation
   - Yang tidak match harus hidden

2. **Hover pada card**:
   - Card harus scale up
   - Shadow harus bertambah

3. **Test Action Buttons**:
   - Klik view icon → harus redirect ke detail
   - Klik edit icon → harus redirect ke form edit
   - Klik delete icon → harus muncul konfirmasi

### 4. **Test Responsive Design**

#### Test di berbagai ukuran layar:
1. **Desktop (> 1024px)**:
   - Grid harus 3-4 kolom
   - Sidebar terlihat permanent
   - Charts full width

2. **Tablet (768px - 1024px)**:
   - Grid harus 2 kolom
   - Sidebar masih terlihat
   - Charts masih readable

3. **Mobile (< 768px)**:
   - Grid harus 1 kolom
   - Sidebar collapsible
   - Hamburger menu terlihat
   - Cards stack vertical

### 5. **Test Performance**

#### Cek hal-hal berikut:
1. **Loading Time**:
   - Dashboard harus load < 2 detik
   - Search harus instant (< 100ms)
   - Animations smooth (60fps)

2. **Data Accuracy**:
   - Angka statistik sesuai database
   - Chart data benar
   - Status kas akurat

3. **Memory Usage**:
   - Tidak ada memory leak
   - Browser tidak lag
   - Smooth scrolling

### 6. **Test Compatibility**

#### Browser Testing:
- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Edge (latest)
- ✅ Safari (latest)

#### Device Testing:
- ✅ Desktop (1920x1080, 1366x768)
- ✅ Tablet (iPad, Android Tablet)
- ✅ Mobile (iPhone, Android Phone)

---

## 🐛 Common Issues & Solutions

### Issue 1: Modal tidak muncul
**Solusi**: 
- Check console untuk errors
- Pastikan JavaScript loaded
- Clear browser cache

### Issue 2: Search tidak bekerja
**Solusi**:
- Check element IDs di HTML
- Pastikan JavaScript event listener attached
- Inspect Network tab untuk API calls

### Issue 3: Charts tidak muncul
**Solusi**:
- Pastikan Chart.js loaded dari CDN
- Check console untuk errors
- Verify data format dari backend

### Issue 4: Styles tidak apply
**Solusi**:
- Run `npm run build` untuk compile assets
- Clear browser cache
- Check Tailwind config

### Issue 5: Data tidak akurat
**Solusi**:
- Check DashboardService logic
- Verify database data
- Check model relationships

---

## 📊 Test Data Requirements

Untuk test yang comprehensive, pastikan database memiliki:

1. **Users**:
   - Minimal 10 users dengan role berbeda
   - Minimal 1 user dengan role `koordinator_jurnalistik`

2. **Berita**:
   - Minimal 10 berita
   - Beberapa dengan status approved
   - Beberapa dengan status pending

3. **Kas Anggota**:
   - Data kas untuk beberapa user
   - Mix status: belum bayar, sebagian, lunas, terlambat

4. **Keuangan**:
   - Beberapa record Pemasukan (verified & pending)
   - Beberapa record Pengeluaran (paid & pending)

5. **Funfacts**:
   - Minimal 6 funfacts untuk test grid

6. **Prokers**:
   - Beberapa proker dengan status berbeda

7. **Briefs**:
   - Beberapa briefs, termasuk yang urgent

---

## ✅ Checklist Testing

### Dashboard:
- [ ] All statistics cards showing correct data
- [ ] Financial panel showing saldo, pemasukan, pengeluaran
- [ ] Kas status showing 4 different statuses
- [ ] "Lihat Detail" button opens modal
- [ ] Modal search works
- [ ] Modal filter works
- [ ] Modal close works (button & backdrop)
- [ ] Both charts render correctly
- [ ] Charts tooltips work
- [ ] Division statistics showing all divisions
- [ ] Proker status showing correctly
- [ ] Urgent briefs displaying
- [ ] Recent news displaying with images

### News Index:
- [ ] Statistics cards showing correct counts
- [ ] Search box filtering real-time
- [ ] Advanced filters toggle works
- [ ] Category filter works
- [ ] Approval filter works
- [ ] Reset filter works
- [ ] Thumbnails displaying correctly
- [ ] Badges showing for category & type
- [ ] Approval status showing correctly
- [ ] Approve button works (if applicable)
- [ ] Action buttons (view, edit, delete) work
- [ ] Pagination works

### Funfacts Index:
- [ ] Statistics cards showing correct counts
- [ ] Search box filtering with animation
- [ ] Grid layout responsive (1-2-3 cols)
- [ ] Cards showing all info correctly
- [ ] Link references clickable
- [ ] Creator avatar & name showing
- [ ] Action buttons work
- [ ] Hover effects working
- [ ] Empty state showing (if no data)

### General:
- [ ] Sidebar navigation works
- [ ] Mobile menu works
- [ ] All links clickable
- [ ] No console errors
- [ ] No 404 errors
- [ ] Colors consistent (#1b334e)
- [ ] Fonts loading correctly
- [ ] Icons (Font Awesome) displaying
- [ ] Responsive on all devices
- [ ] Animations smooth
- [ ] Performance good (no lag)

---

## 🚀 Automated Testing (Optional)

Jika ingin automated testing, bisa gunakan:

```bash
# Install testing dependencies
npm install --save-dev @testing-library/jest-dom
npm install --save-dev cypress

# Run tests
npm test
```

Create test files:
- `tests/Feature/KoordinatorJurnalistikDashboardTest.php` - Backend tests
- `cypress/integration/dashboard.spec.js` - Frontend E2E tests

---

## 📝 Test Report Template

```
Test Date: [DATE]
Tested By: [NAME]
Browser: [BROWSER + VERSION]
Device: [DEVICE/SCREEN SIZE]

Dashboard:
✅ Statistics cards: PASS
✅ Financial data: PASS
✅ Kas status: PASS
✅ Modal functionality: PASS
✅ Charts: PASS
... (continue for all features)

Issues Found:
1. [Issue description]
2. [Issue description]

Overall Status: PASS / FAIL
Notes: [Any additional notes]
```

---

## 💡 Tips untuk Testing

1. **Clear Cache** sebelum testing
2. **Use Incognito Mode** untuk clean environment
3. **Test dengan data real** (production-like)
4. **Test edge cases** (empty data, very long text, etc)
5. **Test concurrent users** jika memungkinkan
6. **Check Network tab** untuk API performance
7. **Use Lighthouse** untuk performance audit
8. **Test accessibility** dengan screen readers

---

**Happy Testing!** 🎉

Jika menemukan bug atau masalah, catat:
- Browser & Version
- Steps to reproduce
- Expected vs Actual result
- Screenshots/Videos jika memungkinkan

