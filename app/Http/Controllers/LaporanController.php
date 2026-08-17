<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureSchema();

        Carbon::setLocale('id');
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

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $query = Presensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc');

        if ($tanggalMulai) {
            $query->whereDate('tanggal', '>=', $tanggalMulai);
        }

        if ($tanggalSelesai) {
            $query->whereDate('tanggal', '<=', $tanggalSelesai);
        }

        $presensis = $query->get();

        // Seed demo attendance history matching the screenshot if database has few records
        if ($presensis->count() < 4) {
            $this->seedDemoRecords($user->id);
            $presensis = Presensi::where('user_id', $user->id)
                ->orderBy('tanggal', 'desc')
                ->get();
        }

        // Map duration calculation for each record
        $presensis->transform(function ($item) {
            $item->durasi_kerja = '-';
            if ($item->jam_masuk && $item->jam_pulang && $item->jam_masuk !== '--:--' && $item->jam_pulang !== '--:--') {
                try {
                    $masuk = Carbon::createFromFormat('H:i', substr($item->jam_masuk, 0, 5));
                    $pulang = Carbon::createFromFormat('H:i', substr($item->jam_pulang, 0, 5));
                    if ($pulang->greaterThan($masuk)) {
                        $diffMinutes = $pulang->diffInMinutes($masuk);
                        $hours = floor($diffMinutes / 60);
                        $mins = $diffMinutes % 60;
                        $item->durasi_kerja = "{$hours}J {$mins}M";
                    }
                } catch (\Exception $e) {
                    $item->durasi_kerja = '-';
                }
            }
            return $item;
        });

        return view('laporan', compact('presensis', 'tanggalMulai', 'tanggalSelesai'));
    }

    public function download(Request $request)
    {
        // Generate simple CSV download for presensi report
        $user = User::firstOrCreate(['email' => 'ilham@rsupmakassar.go.id']);
        $presensis = Presensi::where('user_id', $user->id)->orderBy('tanggal', 'desc')->get();

        $csvData = "Tanggal,Check In,Check Out,Status,Tipe Jam Kerja\n";
        foreach ($presensis as $p) {
            $csvData .= "{$p->tanggal},{$p->jam_masuk},{$p->jam_pulang},{$p->status},{$p->tipe_jam_kerja}\n";
        }

        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Riwayat_Presensi_RSUP_Makassar.csv"',
        ]);
    }

    private function seedDemoRecords($userId)
    {
        $records = [
            [
                'tanggal' => '2026-08-16',
                'jam_masuk' => null,
                'jam_pulang' => null,
                'status' => 'Libur',
                'tipe_jam_kerja' => 'LIBUR (Reguler)'
            ],
            [
                'tanggal' => '2026-08-15',
                'jam_masuk' => null,
                'jam_pulang' => null,
                'status' => 'Libur',
                'tipe_jam_kerja' => 'LIBUR (Reguler)'
            ],
            [
                'tanggal' => '2026-08-14',
                'jam_masuk' => '07.20.09',
                'jam_pulang' => '17.17.41',
                'status' => 'Hadir',
                'tipe_jam_kerja' => 'Presensi Reguler'
            ],
            [
                'tanggal' => '2026-08-13',
                'jam_masuk' => '07.25.12',
                'jam_pulang' => '16.45.00',
                'status' => 'Hadir',
                'tipe_jam_kerja' => 'Presensi Reguler'
            ],
            [
                'tanggal' => '2026-08-12',
                'jam_masuk' => '07.18.55',
                'jam_pulang' => '16.30.20',
                'status' => 'Hadir',
                'tipe_jam_kerja' => 'Presensi Reguler'
            ],
        ];

        foreach ($records as $rec) {
            Presensi::firstOrCreate(
                ['user_id' => $userId, 'tanggal' => $rec['tanggal']],
                [
                    'jam_masuk' => $rec['jam_masuk'],
                    'jam_pulang' => $rec['jam_pulang'],
                    'status' => $rec['status'],
                    'tipe_jam_kerja' => $rec['tipe_jam_kerja'],
                    'lokasi_masuk' => 'RSUP Dr. Wahidin Sudirohusodo Makassar',
                    'lokasi_pulang' => 'RSUP Dr. Wahidin Sudirohusodo Makassar',
                ]
            );
        }
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
        } catch (\Exception $e) {}
    }
}
