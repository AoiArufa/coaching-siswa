# Sistem Jurnal Coaching Sekolah

## Deskripsi
Aplikasi jurnal coaching berbasis Laravel dengan role Guru dan Orang Tua.
Guru membuat jurnal, Orang Tua hanya membaca jurnal anaknya.

## Role & Akses
- Guru: CRUD coaching & jurnal
- Orang Tua: Melihat jurnal anak

## Alur Sistem
1. Guru login
2. Guru membuat coaching
3. Guru menambahkan jurnal
4. Orang tua login
5. Orang tua melihat jurnal anak

## Keamanan
- Middleware role
- Validasi kepemilikan data di controller
