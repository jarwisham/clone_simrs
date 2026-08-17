@extends('layouts.app')

@section('content')
<div x-data="laporanApp()" class="w-full">

    <!-- Top Header Bar with Back Button -->
    <div class="px-5 pt-6 pb-4 flex items-center justify-between bg-slate-50 sticky top-0 z-30 shadow-xs border-b border-slate-100">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200/80 flex items-center justify-center text-slate-700 hover:bg-slate-100 active:scale-95 transition shadow-xs">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <h1 class="text-base font-extrabold text-slate-800 text-center tracking-tight">
            Riwayat Presensi
        </h1>

        <!-- Empty spacer to balance header flex -->
        <div class="w-10 h-10"></div>
    </div>

    <!-- Main Body Container -->
    <div class="px-5 py-4 space-y-4">

        <!-- Filter Tanggal Card -->
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 space-y-3">
            <div>
                <h2 class="text-sm font-extrabold text-teal-600 tracking-tight">Filter Tanggal</h2>
                <p class="text-[11px] font-semibold text-slate-400 mt-0.5">Pilih rentang tanggal untuk menampilkan riwayat kehadiran</p>
            </div>

            <form action="{{ route('laporan.index') }}" method="GET" class="grid grid-cols-2 gap-3 pt-1">
                <div>
                    <label class="block text-[11px] font-extrabold text-slate-700 mb-1">Tanggal Mulai</label>
                    <div class="relative">
                        <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="w-full text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold text-slate-700 mb-1">Tanggal Selesai</label>
                    <div class="relative">
                        <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}" class="w-full text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                    </div>
                </div>

                <div class="col-span-2 pt-1 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-teal-500/20 active:scale-95 transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Terapkan Filter</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Presensi Terbaru Container Card -->
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 space-y-4">
            
            <!-- Section Header: Presensi Terbaru + Download -->
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-800 tracking-tight">Presensi Terbaru</h3>
                    <p class="text-[11px] font-semibold text-slate-400 mt-0.5">Daftar Riwayat Kehadiran</p>
                </div>

                <a href="{{ route('laporan.download') }}" class="flex items-center gap-1.5 text-xs font-extrabold text-teal-600 hover:text-teal-700 bg-teal-50 hover:bg-teal-100/80 px-3 py-1.5 rounded-xl border border-teal-100 transition">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Download</span>
                </a>
            </div>

            <!-- Attendance History Card List -->
            <div class="space-y-3">
                @forelse($presensis as $item)
                    @php
                        $carbonDate = \Carbon\Carbon::parse($item->tanggal);
                        $formattedDate = $carbonDate->isoFormat('dddd, D MMMM YYYY');
                        $isLibur = $item->status === 'Libur' || $carbonDate->isWeekend();
                    @endphp

                    <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs hover:shadow-md transition space-y-3">
                        
                        <!-- Card Header: Day Date + 3 dots menu -->
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-800 tracking-tight">
                                    {{ $formattedDate }}
                                </h4>
                                <div class="mt-1">
                                    @if($isLibur)
                                        <span class="inline-block px-3 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-600">
                                            Libur
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-600">
                                            {{ $item->status ?? 'Hadir' }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <button class="text-slate-400 hover:text-slate-600 p-1">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                </svg>
                            </button>
                        </div>

                        <!-- 3-Column Info Grid: Check In | Check Out | Durasi Kerja -->
                        <div class="grid grid-cols-3 gap-2 pt-1 text-left border-t border-slate-50">
                            <div>
                                <p class="text-[11px] font-semibold text-slate-400">Check In</p>
                                <p class="text-xs font-black {{ $item->jam_masuk && $item->jam_masuk !== '--:--' ? 'text-teal-600' : 'text-slate-400' }} mt-0.5">
                                    {{ $item->jam_masuk ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-[11px] font-semibold text-slate-400">Check Out</p>
                                <p class="text-xs font-black {{ $item->jam_pulang && $item->jam_pulang !== '--:--' ? 'text-amber-500' : 'text-slate-400' }} mt-0.5">
                                    {{ $item->jam_pulang ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-[11px] font-semibold text-slate-400">Durasi Kerja</p>
                                <p class="text-xs font-black text-slate-800 mt-0.5">
                                    {{ $item->durasi_kerja ?? '-' }}
                                </p>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="bg-slate-50 rounded-2xl p-6 text-center text-slate-400 text-xs font-semibold">
                        Tidak ada riwayat presensi ditemukan pada rentang tanggal ini.
                    </div>
                @endforelse
            </div>

        </div>

    </div>

    <!-- Floating Bottom Navigation Bar -->
    <div class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white/95 backdrop-blur-md border-t border-slate-200/80 px-4 py-2 z-40 shadow-2xl flex items-center justify-around">
        
        <!-- Tab 1: Home -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 text-center text-slate-400 font-medium hover:text-teal-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[10px]">Home</span>
        </a>

        <!-- Tab 2: Laporan (Active) -->
        <a href="{{ route('laporan.index') }}" class="flex flex-col items-center gap-0.5 text-center text-teal-600 font-bold transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="text-[10px]">Laporan</span>
        </a>

        <!-- Center Floating Action Button (Camera / Quick Absen) -->
        <a href="{{ route('dashboard') }}" class="-mt-6 w-14 h-14 rounded-full bg-gradient-to-r from-teal-500 to-cyan-500 text-white flex items-center justify-center shadow-lg shadow-teal-500/40 ring-4 ring-slate-50 hover:scale-105 active:scale-95 transition">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </a>

        <!-- Tab 3: Jam Kerja -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 text-center text-slate-400 font-medium hover:text-teal-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-[10px]">Jam Kerja</span>
        </a>

        <!-- Tab 4: Profile -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 text-center text-slate-400 font-medium hover:text-teal-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-[10px]">Profile</span>
        </a>

    </div>

</div>

<script>
    function laporanApp() {
        return {}
    }
</script>
@endsection
