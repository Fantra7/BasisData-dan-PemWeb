<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD - STT Terpadu Nurul Fikri</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50/50 text-gray-800 antialiased">

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center space-x-4">
                    <div class="w-11 h-11 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-md shadow-blue-500/20">
                        NF
                    </div>
                    <div>
                        <span class="font-extrabold text-xl tracking-tight text-gray-900 block">SIAKAD</span>
                        <span class="text-xs text-gray-500 font-medium tracking-wide block uppercase">STT Terpadu Nurul Fikri</span>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="hidden md:flex flex-col text-right">
                        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100">
                            <i class="fa-solid var(--fa-circle-dot) text-[8px] animate-pulse mr-1.5 align-middle text-emerald-500"></i>
                            Tahun Akademik: 2025/2026 Genap
                        </span>
                    </div>
                    <a href="/admin/login" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-all rounded-xl shadow-md shadow-blue-500/10 hover:shadow-lg hover:-translate-y-0.5">
                        Login <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:20px_20px]"></div>
        <div class="absolute -right-10 -top-10 w-96 h-96 bg-blue-500 rounded-full filter blur-3xl opacity-30"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <div class="lg:col-span-2 space-y-4">
                    <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-lg text-xs font-semibold tracking-wide border border-white/10">
                        <i class="fa-solid fa-circle-check text-emerald-400"></i>
                        <span>Sistem Berjalan Normal Terintegrasi PDDikti</span>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight">
                        Dashboard Performa & Informasi Akademik Kampus
                    </h1>
                    <p class="text-blue-100 text-sm md:text-base max-w-2xl font-light leading-relaxed">
                        Selamat datang di pintu gerbang satu akses manajemen akademik digital STT-NF. Pantau data real-time, grafik performa perkuliahan, kalender agenda, serta pusat pengumuman resmi di bawah ini.
                    </p>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/15 p-6 rounded-2xl space-y-4">
                    <h3 class="font-bold text-lg flex items-center"><i class="fa-solid fa-bullhorn mr-2 text-yellow-400"></i> Status Gerbang Portal</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center bg-white/5 p-2.5 rounded-xl border border-white/5">
                            <span class="text-blue-100">Pengisian KRS Online</span>
                            <span class="bg-emerald-500/20 text-emerald-300 font-bold text-xs px-2 py-0.5 rounded border border-emerald-500/30">Terbuka</span>
                        </div>
                        <div class="flex justify-between items-center bg-white/5 p-2.5 rounded-xl border border-white/5">
                            <span class="text-blue-100">Input Nilai Dosen</span>
                            <span class="bg-amber-500/20 text-amber-300 font-bold text-xs px-2 py-0.5 rounded border border-amber-500/30">Masa Tenang</span>
                        </div>
                        <div class="flex justify-between items-center bg-white/5 p-2.5 rounded-xl border border-white/5">
                            <span class="text-blue-100">Pendaftaran Kelulusan</span>
                            <span class="bg-rose-500/20 text-rose-300 font-bold text-xs px-2 py-0.5 rounded border border-rose-500/30">Ditutup</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 -mt-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 flex items-center space-x-4">
                <div class="p-4 bg-blue-50 text-blue-600 rounded-xl">
                    <i class="fa-solid fa-user-graduate text-2xl"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Mahasiswa Aktif</span>
                    <span class="text-2xl font-extrabold text-gray-900 block mt-0.5">5.261</span>
                    <span class="text-xs text-emerald-600 font-medium mt-1 inline-block"><i class="fa-solid fa-caret-up mr-0.5"></i> +4.2% Angkatan Baru</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 flex items-center space-x-4">
                <div class="p-4 bg-emerald-50 text-emerald-600 rounded-xl">
                    <i class="fa-solid fa-chalkboard-user text-2xl"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Dosen & Staff</span>
                    <span class="text-2xl font-extrabold text-gray-900 block mt-0.5">148</span>
                    <span class="text-xs text-gray-500 font-medium mt-1 inline-block">92% Tersertifikasi</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 flex items-center space-x-4">
                <div class="p-4 bg-purple-50 text-purple-600 rounded-xl">
                    <i class="fa-solid fa-chart-line text-2xl"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Kehadiran Kuliah</span>
                    <span class="text-2xl font-extrabold text-gray-900 block mt-0.5">89%</span>
                    <span class="text-xs text-emerald-600 font-medium mt-1 inline-block"><i class="fa-solid fa-check mr-0.5"></i> Performa Sangat Baik</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 flex items-center space-x-4">
                <div class="p-4 bg-amber-50 text-amber-600 rounded-xl">
                    <i class="fa-solid fa-award text-2xl"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Akreditasi STT-NF</span>
                    <span class="text-2xl font-extrabold text-gray-900 block mt-0.5">Baik Sekali</span>
                    <span class="text-xs text-blue-600 font-medium mt-1 inline-block">Proses Menuju Unggul</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 lg:col-span-2 space-y-6">
                <div class="flex justify-between items-center border-b border-gray-50 pb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Statistik Kehadiran & Partisipasi Per-Prodi</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Rata-rata persentase kehadiran tatap muka & e-learning pekan ke-12.</p>
                    </div>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">Live Data</span>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-sm font-medium">
                            <span class="text-gray-700">Teknik Informatika (S1)</span>
                            <span class="text-gray-900 font-bold">92%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full" style="width: 92%"></div>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-sm font-medium">
                            <span class="text-gray-700">Sistem Informasi (S1)</span>
                            <span class="text-gray-900 font-bold">86%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-3 rounded-full" style="width: 86%"></div>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-sm font-medium">
                            <span class="text-gray-700">Bisnis Digital (S1)</span>
                            <span class="text-gray-900 font-bold">81%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-3 rounded-full" style="width: 81%"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 pt-4 border-t border-gray-50 text-center text-xs text-gray-500">
                    <div>
                        <span class="block text-base font-bold text-gray-900">1.276</span>
                        Total Lulusan Alumni
                    </div>
                    <div>
                        <span class="block text-base font-bold text-gray-900">82</span>
                        Mahasiswa Cuti/Cuti Sakit
                    </div>
                    <div>
                        <span class="block text-base font-bold text-gray-900">3.86</span>
                        Rata-rata IPK Kampus
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 flex flex-col justify-between">
                <div class="space-y-4">
                    <h2 class="text-lg font-bold text-gray-900">Rasio Demografi Mahasiswa</h2>
                    <p class="text-xs text-gray-500 -mt-2">Distribusi mahasiswa aktif terdaftar per-jenjang studi aktif.</p>

                    <div class="flex justify-center py-4">
                        <div class="relative w-36 h-36 rounded-full border-[14px] border-blue-600 flex items-center justify-center after:content-[''] after:absolute after:inset-[-14px] after:rounded-full after:border-[14px] after:border-indigo-400 after:clip-path-inset-[0_0_50%_0]">
                            <div class="text-center">
                                <span class="block text-2xl font-black text-gray-900">80%</span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Reguler</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                        <span class="flex items-center text-gray-600"><span class="w-2.5 h-2.5 bg-blue-600 rounded-full mr-2"></span> Kelas Reguler Pagi</span>
                        <span class="font-bold text-gray-950">4.120 Mhs</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                        <span class="flex items-center text-gray-600"><span class="w-2.5 h-2.5 bg-indigo-400 rounded-full mr-2"></span> Kelas Karyawan/Malam</span>
                        <span class="font-bold text-gray-950">1.141 Mhs</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 lg:col-span-2 space-y-6">
                <div class="flex justify-between items-center border-b border-gray-50 pb-4">
                    <h2 class="text-lg font-bold text-gray-900"><i class="fa-solid fa-newspaper mr-2 text-blue-600"></i> Pengumuman Resmi Akademik</h2>
                    <a href="#" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua Berita</a>
                </div>

                <div class="space-y-4">
                    <div class="group p-4 bg-gray-50/50 hover:bg-blue-50/30 transition-all rounded-xl border border-gray-100 hover:border-blue-100 flex gap-4">
                        <div class="bg-blue-100 text-blue-700 w-12 h-12 rounded-xl flex flex-col items-center justify-center font-bold shrink-0">
                            <span class="text-sm leading-none">18</span>
                            <span class="text-[10px] uppercase font-bold tracking-wider mt-0.5">Juni</span>
                        </div>
                        <div class="space-y-1">
                            <span class="bg-blue-100 text-blue-700 font-bold text-[10px] px-2 py-0.5 rounded uppercase tracking-wide">Registrasi</span>
                            <h3 class="font-bold text-sm text-gray-900 group-hover:text-blue-700 transition-colors">Batas Akhir Penutupan & Finalisasi KRS Semester Genap 2025/2026</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">Diberitahukan kepada seluruh mahasiswa aktif agar segera melakukan persetujuan pembimbing akademik d.h. Dosen Wali sebelum sistem dikunci.</p>
                        </div>
                    </div>
                    <div class="group p-4 bg-gray-50/50 hover:bg-blue-50/30 transition-all rounded-xl border border-gray-100 hover:border-blue-100 flex gap-4">
                        <div class="bg-purple-100 text-purple-700 w-12 h-12 rounded-xl flex flex-col items-center justify-center font-bold shrink-0">
                            <span class="text-sm leading-none">25</span>
                            <span class="text-[10px] uppercase font-bold tracking-wider mt-0.5">Juni</span>
                        </div>
                        <div class="space-y-1">
                            <span class="bg-purple-100 text-purple-700 font-bold text-[10px] px-2 py-0.5 rounded uppercase tracking-wide">Ujian</span>
                            <h3 class="font-bold text-sm text-gray-900 group-hover:text-purple-700 transition-colors">Rilis Jadwal Pelaksanaan Ujian Akhir Semester (UAS)</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">Kartu Ujian UTS dapat diunduh mandiri melalui panel akun mahasiswa masing-masing apabila administrasi keuangan telah tervalidasi.</p>
                        </div>
                    </div>
                    <div class="group p-4 bg-gray-50/50 hover:bg-blue-50/30 transition-all rounded-xl border border-gray-100 hover:border-blue-100 flex gap-4">
                        <div class="bg-amber-100 text-amber-700 w-12 h-12 rounded-xl flex flex-col items-center justify-center font-bold shrink-0">
                            <span class="text-sm leading-none">02</span>
                            <span class="text-[10px] uppercase font-bold tracking-wider mt-0.5">Juli</span>
                        </div>
                        <div class="space-y-1">
                            <span class="bg-amber-100 text-amber-700 font-bold text-[10px] px-2 py-0.5 rounded uppercase tracking-wide">Beasiswa</span>
                            <h3 class="font-bold text-sm text-gray-900 group-hover:text-amber-700 transition-colors">Pembukaan Kuota Beasiswa Prestasi & Yayasan Nurul Fikri Gelombang II</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">Peluang beasiswa potongan UKT semester bagi mahasiswa berprestasi dengan IPK minimum 3.65 dan aktif organisasi internal.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 space-y-6">
                <div class="border-b border-gray-50 pb-4">
                    <h2 class="text-lg font-bold text-gray-900"><i class="fa-solid fa-calendar-days mr-2 text-indigo-600"></i> Agenda Kampus Terdekat</h2>
                </div>

                <div class="relative pl-6 space-y-6 before:content-[''] before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-100">
                    <div class="relative">
                        <span class="absolute -left-6 top-1.5 w-3.5 h-3.5 rounded-full border-2 border-white bg-blue-600 shadow-xs"></span>
                        <div class="space-y-0.5">
                            <span class="text-[11px] text-gray-400 font-bold block">15 - 20 Juni 2026</span>
                            <h4 class="text-sm font-bold text-gray-900">Minggu Tenang Kuliah</h4>
                        </div>
                    </div>
                    <div class="relative">
                        <span class="absolute -left-6 top-1.5 w-3.5 h-3.5 rounded-full border-2 border-white bg-purple-500 shadow-xs"></span>
                        <div class="space-y-0.5">
                            <span class="text-[11px] text-gray-400 font-bold block">22 Juni - 04 Juli 2026</span>
                            <h4 class="text-sm font-bold text-gray-900">Pelaksanaan Ujian Tengah Semester</h4>
                        </div>
                    </div>
                    <div class="relative">
                        <span class="absolute -left-6 top-1.5 w-3.5 h-3.5 rounded-full border-2 border-white bg-amber-500 shadow-xs"></span>
                        <div class="space-y-0.5">
                            <span class="text-[11px] text-gray-400 font-bold block">10 Juli 2026</span>
                            <h4 class="text-sm font-bold text-gray-900">Batas Akhir Sidang Tugas Akhir/Skripsi</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 space-y-6">
            <div>
                <h2 class="text-lg font-bold text-gray-900"><i class="fa-solid fa-cubes mr-2 text-emerald-600"></i> Tautan Layanan & Ekosistem Digital Kampus</h2>
                <p class="text-xs text-gray-500 mt-0.5">Akses instan layanan pendukung operasional akademik dan kemahasiswaan STT-NF.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="#" class="p-4 bg-gray-50/50 border border-gray-100 hover:border-blue-200 hover:bg-blue-50/20 rounded-xl transition-all flex items-center space-x-3 group">
                    <div class="p-2.5 bg-blue-100 text-blue-700 rounded-lg group-hover:scale-105 transition-transform"><i class="fa-solid fa-graduation-cap"></i></div>
                    <span class="text-xs font-bold text-gray-800 group-hover:text-blue-700">E-Learning NF-LMS</span>
                </a>
                <a href="#" class="p-4 bg-gray-50/50 border border-gray-100 hover:border-emerald-200 hover:bg-emerald-50/20 rounded-xl transition-all flex items-center space-x-3 group">
                    <div class="p-2.5 bg-emerald-100 text-emerald-700 rounded-lg group-hover:scale-105 transition-transform"><i class="fa-solid fa-book-bookmark"></i></div>
                    <span class="text-xs font-bold text-gray-800 group-hover:text-emerald-700">Digital Library (Digilib)</span>
                </a>
                <a href="#" class="p-4 bg-gray-50/50 border border-gray-100 hover:border-purple-200 hover:bg-purple-50/20 rounded-xl transition-all flex items-center space-x-3 group">
                    <div class="p-2.5 bg-purple-100 text-purple-700 rounded-lg group-hover:scale-105 transition-transform"><i class="fa-solid fa-flask-vial"></i></div>
                    <span class="text-xs font-bold text-gray-800 group-hover:text-purple-700">OJS Jurnal Ilmiah JTIK</span>
                </a>
                <a href="#" class="p-4 bg-gray-50/50 border border-gray-100 hover:border-amber-200 hover:bg-amber-50/20 rounded-xl transition-all flex items-center space-x-3 group">
                    <div class="p-2.5 bg-amber-100 text-amber-700 rounded-lg group-hover:scale-105 transition-transform"><i class="fa-solid fa-users-gear"></i></div>
                    <span class="text-xs font-bold text-gray-800 group-hover:text-amber-700">Layanan BEM & Ormawa</span>
                </a>
            </div>
        </div>

    </main>

    <footer class="bg-gray-900 text-gray-400 text-xs mt-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="space-y-3">
                <div class="flex items-center space-x-2 text-white">
                    <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center font-bold text-sm">NF</div>
                    <span class="font-bold text-sm tracking-tight">STT Terpadu Nurul Fikri</span>
                </div>
                <p class="leading-relaxed text-gray-500">
                    Kampus Unggul, Berkarakter, dan Berorientasi Teknologi Informasi Profesional Masa Depan.
                </p>
            </div>
            <div class="space-y-2">
                <h4 class="text-white font-bold text-sm">Kontak Portal</h4>
                <p class="leading-relaxed"><i class="fa-solid fa-map-location-dot mr-1.5 text-gray-600"></i> Jl. Raya Lenteng Agung No.20, Jakarta Selatan</p>
                <p><i class="fa-solid fa-envelope mr-1.5 text-gray-600"></i> info@nurulfikri.ac.id</p>
            </div>
            <div class="space-y-2">
                <h4 class="text-white font-bold text-sm">Validasi Penjamin Mutu</h4>
                <p class="leading-relaxed text-gray-500">
                    Seluruh data entitas mahasiswa, transkrip nilai, kualifikasi kurikulum, dan data pelaporan dosen disinkronisasi berkala ke PDDikti Kemendikbudristek RI.
                </p>
            </div>
        </div>
        <div class="border-t border-gray-800 text-center py-6 text-gray-600 max-w-7xl mx-auto px-4">
            &copy; 2026 STT Terpadu Nurul Fikri. All Rights Reserved. Powered by Kelompok 3.
        </div>
    </footer>

</body>
</html>
