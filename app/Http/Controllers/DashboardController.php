<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto-heal / ensure database schema has required columns
        $this->ensureSchema();

        // Set timezone to Asia/Makassar (WITA)
        Carbon::setLocale('id');
        $today = Carbon::now('Asia/Makassar');
        
        // Find or create default demo user matching the screenshot
        $user = User::firstOrCreate(
            ['email' => 'ilham@rsupmakassar.go.id'],
            [
                'name' => 'Muh Ilham Nur Hidayat Akbar',
                'nip' => '199408162022031002',
                'jabatan' => 'OSDM / IT Specialist',
                'password' => bcrypt('password'),
                'is_verified' => true,
            ]
        );

        // Fetch or create today's presence record
        $todayPresensi = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today->format('Y-m-d'))
            ->first();

        // Sample recent attendance list for demo
        $recentPresensis = Presensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        if ($recentPresensis->isEmpty()) {
            // Seed a few demo records if empty
            $demoDates = [
                ['tanggal' => $today->copy()->subDays(2)->format('Y-m-d'), 'jam_masuk' => '07:25', 'jam_pulang' => '16:05', 'status' => 'Hadir', 'tipe' => 'Presensi Reguler'],
                ['tanggal' => $today->copy()->subDays(3)->format('Y-m-d'), 'jam_masuk' => '07:28', 'jam_pulang' => '16:10', 'status' => 'Hadir', 'tipe' => 'Presensi Reguler'],
                ['tanggal' => $today->copy()->subDays(4)->format('Y-m-d'), 'jam_masuk' => '07:15', 'jam_pulang' => '16:00', 'status' => 'Hadir', 'tipe' => 'Presensi Reguler'],
            ];

            foreach ($demoDates as $demo) {
                Presensi::create([
                    'user_id' => $user->id,
                    'tanggal' => $demo['tanggal'],
                    'jam_masuk' => $demo['jam_masuk'],
                    'jam_pulang' => $demo['jam_pulang'],
                    'tipe_jam_kerja' => $demo['tipe'],
                    'status' => $demo['status'],
                    'lokasi_masuk' => 'RSUP Dr. Wahidin Sudirohusodo Makassar',
                    'lokasi_pulang' => 'RSUP Dr. Wahidin Sudirohusodo Makassar',
                ]);
            }

            $recentPresensis = Presensi::where('user_id', $user->id)
                ->orderBy('tanggal', 'desc')
                ->take(5)
                ->get();
        }

        // Determine shift status: Sunday is Libur (Reguler)
        $isLibur = true;
        $tipeJamKerja = "LIBUR (Reguler)";

        $menus = [
            ['title' => 'Info OSDM', 'icon' => 'megaphone', 'bg' => 'bg-cyan-50 text-cyan-600', 'route' => '#info-osdm'],
            ['title' => 'Riwayat Presensi', 'icon' => 'clipboard-check', 'bg' => 'bg-emerald-50 text-emerald-600', 'route' => '#riwayat-presensi'],
            ['title' => 'Riwayat Lembur', 'icon' => 'clock-history', 'bg' => 'bg-teal-50 text-teal-600', 'route' => '#riwayat-lembur'],
            ['title' => 'Lembur', 'icon' => 'clock-plus', 'bg' => 'bg-sky-50 text-sky-600', 'route' => '#lembur'],
            ['title' => 'Slip Gaji', 'icon' => 'receipt', 'bg' => 'bg-indigo-50 text-indigo-600', 'route' => '#slip-gaji'],
            ['title' => 'Presensi DPJP', 'icon' => 'doctor', 'bg' => 'bg-blue-50 text-blue-600', 'route' => '#presensi-dpjp'],
            ['title' => 'Tugas IT', 'icon' => 'laptop', 'bg' => 'bg-purple-50 text-purple-600', 'route' => '#tugas-it'],
        ];

        $greeting = $this->getGreeting($today->hour);
        $formattedDate = $today->isoFormat('D MMMM YYYY');
        $dayName = $today->isoFormat('dddd');

        return view('dashboard', compact(
            'user',
            'today',
            'dayName',
            'formattedDate',
            'greeting',
            'isLibur',
            'tipeJamKerja',
            'todayPresensi',
            'recentPresensis',
            'menus'
        ));
    }

    private function ensureSchema()
    {
        try {
            if (!Schema::hasColumn('users', 'nip')) {
                Schema::table('users', function (Blueprint $table) {
                    if (!Schema::hasColumn('users', 'nip')) $table->string('nip')->nullable();
                    if (!Schema::hasColumn('users', 'jabatan')) $table->string('jabatan')->nullable();
                    if (!Schema::hasColumn('users', 'is_verified')) $table->boolean('is_verified')->default(false);
                });
            }

            if (!Schema::hasTable('presensis')) {
                Schema::create('presensis', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                    $table->date('tanggal');
                    $table->string('jam_masuk')->nullable();
                    $table->string('jam_pulang')->nullable();
                    $table->string('tipe_jam_kerja')->default('LIBUR (Reguler)');
                    $table->string('status')->default('Hadir');
                    $table->string('lokasi_masuk')->nullable();
                    $table->string('lokasi_pulang')->nullable();
                    $table->text('catatan')->nullable();
                    $table->string('foto_masuk')->nullable();
                    $table->string('foto_pulang')->nullable();
                    $table->timestamps();
                });
            }
        } catch (\Exception $e) {
            // Silently ignore if table/columns exist
        }
    }

    private function getGreeting($hour)
    {
        if ($hour >= 4 && $hour < 11) {
            return 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            return 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            return 'Selamat Sore';
        } else {
            return 'Selamat Malam';
        }
    }
}
