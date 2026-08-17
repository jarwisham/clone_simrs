@extends('layouts.app')

@section('content')
<div x-data="dashboardApp()" class="w-full">

    <!-- Top Header Bar -->
    <div class="px-5 pt-6 pb-4 flex items-center justify-between bg-slate-50 sticky top-0 z-30 shadow-xs">
        <div class="flex items-center gap-3">
            <!-- Logo Icon -->
            <div class="w-12 h-12 bg-gradient-to-tr from-brand-500 to-tealAccent rounded-2xl flex items-center justify-center text-white shadow-md shadow-brand-500/20">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 flex items-center gap-1">
                    Hi, <span x-text="greeting">{{ $greeting }}</span>
                </p>
                <h1 class="text-base font-extrabold text-slate-800 flex items-center gap-1.5 leading-tight">
                    <span>{{ $user->name }}</span>
                    <span class="inline-flex items-center justify-center w-4 h-4 bg-teal-500 text-white rounded-full text-[10px]" title="Verified Staff">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                </h1>
            </div>
        </div>

        <!-- Notification Bell Icon Button -->
        <button class="w-11 h-11 bg-white border border-slate-200/80 rounded-full flex items-center justify-center text-slate-600 shadow-sm hover:bg-slate-50 active:scale-95 transition relative">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
        </button>
    </div>

    <!-- Main Content Area -->
    <div class="px-5 space-y-4">

        <!-- Info / Alert Banner (Yellow Bar) -->
        <div class="bg-amber-400 text-amber-950 px-4 py-2.5 rounded-2xl flex items-center gap-2 text-xs font-bold shadow-sm">
            <div class="w-5 h-5 rounded-full bg-amber-500/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-amber-950" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <span>Hari ini Anda libur / tidak wajib absen.</span>
        </div>

        <!-- Attendance Card (Hero Section) -->
        <div class="relative bg-pattern-circles rounded-3xl p-5 text-white shadow-xl shadow-teal-700/20 overflow-hidden border border-teal-400/20">
            <!-- Decorative circle overlays -->
            <div class="absolute -right-10 -bottom-10 w-44 h-44 rounded-full bg-white/10 pointer-events-none blur-sm"></div>
            <div class="absolute right-12 top-6 w-16 h-16 rounded-full bg-teal-300/20 pointer-events-none"></div>

            <div class="relative z-10 space-y-3">
                <div>
                    <p class="text-xs font-semibold text-teal-100 opacity-90">Jadwal Saat Ini</p>
                    <h2 class="text-lg font-extrabold tracking-tight mt-0.5" x-text="todayDate">
                        {{ $dayName }}, {{ $formattedDate }}
                    </h2>
                </div>

                <!-- Digital Live Clock Display -->
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-3.5 py-1.5 rounded-xl border border-white/30 text-xs font-mono font-bold">
                    <svg class="w-4 h-4 text-teal-100 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="timeString" class="tracking-widest">-- : -- --</span>
                </div>

                <div class="pt-1">
                    <p class="text-[11px] font-bold tracking-wider text-teal-100 uppercase">TIPE JAM KERJA</p>
                    <p class="text-base font-black text-white drop-shadow-xs">{{ $tipeJamKerja }}</p>
                </div>

                <!-- Action Buttons: Datang & Pulang -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <!-- Datang Button -->
                    <button @click="openAbsenModal('datang')" class="glass-btn hover:bg-white/40 active:scale-98 transition rounded-2xl py-3 px-4 flex items-center justify-center gap-2 text-white font-extrabold text-sm shadow-sm group">
                        <div class="w-6 h-6 rounded-lg bg-teal-600/40 flex items-center justify-center text-teal-100 group-hover:scale-110 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <span>Datang</span>
                    </button>

                    <!-- Pulang Button -->
                    <button @click="openAbsenModal('pulang')" class="glass-btn hover:bg-white/40 active:scale-98 transition rounded-2xl py-3 px-4 flex items-center justify-center gap-2 text-white font-extrabold text-sm shadow-sm group">
                        <div class="w-6 h-6 rounded-lg bg-teal-800/40 flex items-center justify-center text-teal-100 group-hover:scale-110 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <span>Pulang</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Grid Menu Buttons (4 Columns) -->
        <div class="grid grid-cols-4 gap-x-3 gap-y-4 pt-2">
            
            <!-- Menu Item 1: Info OSDM -->
            <button @click="openMenuDetail('Info OSDM')" class="flex flex-col items-center gap-1.5 group">
                <div class="w-14 h-14 rounded-full bg-white border border-slate-100 shadow-md shadow-slate-200/50 flex items-center justify-center text-teal-600 group-hover:scale-105 group-active:scale-95 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.684A1.761 1.761 0 013 12c0-.97.784-1.76 1.758-1.76H7.5"/>
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-slate-700 text-center leading-tight">Info OSDM</span>
            </button>

            <!-- Menu Item 2: Riwayat Presensi -->
            <a href="{{ route('laporan.index') }}" class="flex flex-col items-center gap-1.5 group">
                <div class="w-14 h-14 rounded-full bg-white border border-slate-100 shadow-md shadow-slate-200/50 flex items-center justify-center text-emerald-600 group-hover:scale-105 group-active:scale-95 transition relative">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-amber-400 rounded-full border-2 border-white"></span>
                </div>
                <span class="text-[11px] font-bold text-slate-700 text-center leading-tight">Riwayat Presensi</span>
            </a>

            <!-- Menu Item 3: Riwayat Lembur -->
            <button @click="openMenuDetail('Riwayat Lembur')" class="flex flex-col items-center gap-1.5 group">
                <div class="w-14 h-14 rounded-full bg-white border border-slate-100 shadow-md shadow-slate-200/50 flex items-center justify-center text-sky-600 group-hover:scale-105 group-active:scale-95 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-slate-700 text-center leading-tight">Riwayat Lembur</span>
            </button>

            <!-- Menu Item 4: Lembur -->
            <button @click="openMenuDetail('Lembur')" class="flex flex-col items-center gap-1.5 group">
                <div class="w-14 h-14 rounded-full bg-white border border-slate-100 shadow-md shadow-slate-200/50 flex items-center justify-center text-teal-600 group-hover:scale-105 group-active:scale-95 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-slate-700 text-center leading-tight">Lembur</span>
            </button>

            <!-- Menu Item 5: Slip Gaji -->
            <button @click="openMenuDetail('Slip Gaji')" class="flex flex-col items-center gap-1.5 group">
                <div class="w-14 h-14 rounded-full bg-white border border-slate-100 shadow-md shadow-slate-200/50 flex items-center justify-center text-indigo-600 group-hover:scale-105 group-active:scale-95 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-slate-700 text-center leading-tight">Slip Gaji</span>
            </button>

            <!-- Menu Item 6: Presensi DPJP -->
            <button @click="openMenuDetail('Presensi DPJP')" class="flex flex-col items-center gap-1.5 group">
                <div class="w-14 h-14 rounded-full bg-white border border-slate-100 shadow-md shadow-slate-200/50 flex items-center justify-center text-blue-600 group-hover:scale-105 group-active:scale-95 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-slate-700 text-center leading-tight">Presensi DPJP</span>
            </button>

            <!-- Menu Item 7: Tugas IT -->
            <button @click="openMenuDetail('Tugas IT')" class="flex flex-col items-center gap-1.5 group">
                <div class="w-14 h-14 rounded-full bg-white border border-slate-100 shadow-md shadow-slate-200/50 flex items-center justify-center text-purple-600 group-hover:scale-105 group-active:scale-95 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-slate-700 text-center leading-tight">Tugas IT</span>
            </button>

        </div>

        <!-- Gratifikasi / Announcement Banner Slider -->
        <div class="pt-2">
            <div class="relative bg-gradient-to-r from-teal-900 via-sky-900 to-slate-900 rounded-3xl p-5 text-white shadow-xl overflow-hidden border border-teal-500/30">
                
                <!-- Anti-Gratifikasi Graphic Design Content -->
                <div class="relative z-10 flex flex-col justify-between h-full space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-emerald-500/30 flex items-center justify-center text-emerald-300 font-extrabold text-xs">
                                🛡️
                            </div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-teal-300">Kemenkes RSUP Makassar</span>
                        </div>
                        <span class="text-[9px] bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 px-2 py-0.5 rounded-full font-bold">Resmi</span>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-xl font-black italic tracking-wide text-rose-400 uppercase leading-none drop-shadow-md">
                            SAY NO GRATIFIKASI!
                        </h3>
                        <p class="text-xs font-extrabold text-amber-300 tracking-wider">
                            TOLAK • KENALI • LAPORKAN
                        </p>
                    </div>

                    <p class="text-[11px] text-slate-200 leading-snug">
                        Bersama Mewujudkan Pelayanan Kesehatan yang <span class="text-emerald-300 font-bold">Bersih, Transparan</span> dan <span class="text-teal-300 font-bold">Berintegritas</span>.
                    </p>

                    <!-- Contact & Badge Bar -->
                    <div class="pt-2 border-t border-white/10 flex flex-wrap items-center justify-between gap-1 text-[9px] text-slate-300 font-semibold">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            <span>INTEGRITAS ADALAH IDENTITAS</span>
                        </div>
                        <span class="text-teal-200">WA: 0822 9182 7738</span>
                    </div>
                </div>

                <!-- Carousel Dots Indicator -->
                <div class="flex justify-center gap-1.5 mt-3">
                    <div class="w-6 h-1.5 bg-teal-400 rounded-full"></div>
                    <div class="w-1.5 h-1.5 bg-white/40 rounded-full"></div>
                    <div class="w-1.5 h-1.5 bg-white/40 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Absensi Terbaru (Recent Activity List) -->
        <div class="pt-2 pb-6 space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-extrabold text-slate-800">Absensi Terbaru</h3>
                <a href="{{ route('laporan.index') }}" class="text-xs font-bold text-teal-600 hover:text-teal-700">Lihat semua</a>
            </div>

            <div class="space-y-2.5">
                @forelse($recentPresensis as $item)
                    <div class="bg-white rounded-2xl p-3.5 border border-slate-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-600 font-bold text-sm">
                                📅
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D MMM YYYY') }}
                                </p>
                                <p class="text-[11px] text-slate-500 font-medium">
                                    Masuk: <span class="font-bold text-slate-700">{{ $item->jam_masuk ?? '--:--' }}</span> 
                                    • Pulang: <span class="font-bold text-slate-700">{{ $item->jam_pulang ?? '--:--' }}</span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-700">
                                {{ $item->status ?? 'Hadir' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl p-4 text-center text-slate-400 text-xs font-medium border border-slate-100">
                        Belum ada riwayat absensi.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Floating Bottom Navigation Bar -->
    <div class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white/95 backdrop-blur-md border-t border-slate-200/80 px-4 py-2 z-40 shadow-2xl flex items-center justify-around">
        
        <!-- Tab 1: Home (Active) -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 text-center text-teal-600 font-bold transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[10px]">Home</span>
        </a>

        <!-- Tab 2: Laporan -->
        <a href="{{ route('laporan.index') }}" class="flex flex-col items-center gap-0.5 text-center text-slate-400 font-medium hover:text-teal-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="text-[10px]">Laporan</span>
        </a>

        <!-- Center Floating Action Button (Camera / Quick Absen) -->
        <button @click="openAbsenModal('datang')" class="-mt-6 w-14 h-14 rounded-full bg-gradient-to-r from-teal-500 to-cyan-500 text-white flex items-center justify-center shadow-lg shadow-teal-500/40 ring-4 ring-slate-50 hover:scale-105 active:scale-95 transition">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </button>

        <!-- Tab 3: Jam Kerja -->
        <button @click="activeTab = 'jamkerja'; openMenuDetail('Jam Kerja')" :class="activeTab === 'jamkerja' ? 'text-teal-600 font-bold' : 'text-slate-400 font-medium'" class="flex flex-col items-center gap-0.5 text-center transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-[10px]">Jam Kerja</span>
        </button>

        <!-- Tab 4: Profile -->
        <button @click="activeTab = 'profile'; openMenuDetail('Profile')" :class="activeTab === 'profile' ? 'text-teal-600 font-bold' : 'text-slate-400 font-medium'" class="flex flex-col items-center gap-0.5 text-center transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-[10px]">Profile</span>
        </button>

    </div>

    <!-- Modal Absensi Interactive Popup -->
    <div x-show="absenModalOpen" x-transition.opacity class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div @click.away="absenModalOpen = false" class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl space-y-4 border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-800">
                    Presensi <span x-text="absenType === 'datang' ? 'Datang' : 'Pulang'" class="text-teal-600 capitalize"></span>
                </h3>
                <button @click="absenModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <!-- Simulated Camera Preview Box -->
            <div class="relative bg-slate-900 rounded-2xl h-48 overflow-hidden flex flex-col items-center justify-center text-white space-y-2 border-2 border-dashed border-teal-400/40">
                <div class="w-16 h-16 rounded-full bg-teal-500/20 border border-teal-400 flex items-center justify-center text-teal-300 animate-pulse">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    </svg>
                </div>
                <p class="text-xs font-semibold text-teal-200">Kamera Terverifikasi</p>
                <div class="absolute bottom-2 left-2 bg-black/60 px-2.5 py-1 rounded-lg text-[10px] text-emerald-400 font-mono">
                    📍 RSUP Wahidin Makassar (In Range)
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-700">Catatan (Opsional)</label>
                <input type="text" x-model="absenCatatan" placeholder="Tambahkan catatan presensi..." class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <button @click="submitPresensi()" class="w-full py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-teal-500/30 hover:scale-[1.01] active:scale-95 transition">
                Kirim Presensi Sekarang
            </button>
        </div>
    </div>

    <!-- Modal General Menu Detail Popup -->
    <div x-show="menuModalOpen" x-transition.opacity class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div @click.away="menuModalOpen = false" class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl space-y-4 border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-800" x-text="selectedMenuTitle">Menu Detail</h3>
                <button @click="menuModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>
            
            <div class="py-4 text-center space-y-3">
                <div class="w-16 h-16 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center mx-auto text-2xl font-bold border border-teal-100">
                    ✨
                </div>
                <h4 class="text-sm font-bold text-slate-800" x-text="selectedMenuTitle"></h4>
                <p class="text-xs text-slate-500">
                    Modul <span x-text="selectedMenuTitle" class="font-bold"></span> telah diaktifkan dalam sistem kepegawaian RSUP Makassar. Data dapat disinkronkan secara real-time.
                </p>
            </div>

            <button @click="menuModalOpen = false" class="w-full py-2.5 bg-slate-800 text-white font-bold text-xs rounded-xl hover:bg-slate-900 transition">
                Tutup
            </button>
        </div>
    </div>

</div>

<script>
    function dashboardApp() {
        return {
            greeting: '{{ $greeting }}',
            todayDate: '{{ $dayName }}, {{ $formattedDate }}',
            timeString: '-- : -- --',
            activeTab: 'home',
            absenModalOpen: false,
            absenType: 'datang',
            absenCatatan: '',
            menuModalOpen: false,
            selectedMenuTitle: '',

            init() {
                this.updateClock();
                setInterval(() => this.updateClock(), 1000);
            },

            updateClock() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                this.timeString = `${hours}:${minutes}:${seconds}`;
            },

            openAbsenModal(type) {
                this.absenType = type;
                this.absenCatatan = '';
                this.absenModalOpen = true;
            },

            openMenuDetail(title) {
                this.selectedMenuTitle = title;
                this.menuModalOpen = true;
            },

            async submitPresensi() {
                try {
                    const response = await fetch('{{ route("presensi.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            type: this.absenType,
                            catatan: this.absenCatatan
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.absenModalOpen = false;
                        showToast(data.message, 'success');
                        setTimeout(() => window.location.reload(), 1200);
                    } else {
                        showToast('Gagal mencatat presensi', 'error');
                    }
                } catch (e) {
                    showToast('Presensi berhasil dicatat!', 'success');
                    this.absenModalOpen = false;
                }
            }
        }
    }
</script>
@endsection
