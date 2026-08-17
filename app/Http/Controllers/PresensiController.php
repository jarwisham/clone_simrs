<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PresensiController extends Controller
{
    public function store(Request $request)
    {
        $this->ensureSchema();

        $request->validate([
            'type' => 'required|in:datang,pulang',
            'catatan' => 'nullable|string|max:255',
            'lat' => 'nullable|string',
            'lng' => 'nullable|string',
        ]);

        $today = Carbon::now('Asia/Makassar');
        $user = User::firstOrCreate(['email' => 'ilham@rsupmakassar.go.id']);

        $presensi = Presensi::firstOrNew([
            'user_id' => $user->id,
            'tanggal' => $today->format('Y-m-d'),
        ]);

        $currentTime = $today->format('H:i');

        if ($request->type === 'datang') {
            $presensi->jam_masuk = $currentTime;
            $presensi->lokasi_masuk = 'RSUP Dr. Wahidin Sudirohusodo Makassar';
            $presensi->tipe_jam_kerja = 'LIBUR (Reguler)';
            $presensi->status = 'Hadir';
            $presensi->save();

            return response()->json([
                'success' => true,
                'message' => "Presensi Datang berhasil dicatat pada jam {$currentTime} WITA!",
                'presensi' => $presensi
            ]);
        } else {
            $presensi->jam_pulang = $currentTime;
            $presensi->lokasi_pulang = 'RSUP Dr. Wahidin Sudirohusodo Makassar';
            if (!$presensi->exists) {
                $presensi->status = 'Hadir';
                $presensi->tipe_jam_kerja = 'LIBUR (Reguler)';
            }
            $presensi->save();

            return response()->json([
                'success' => true,
                'message' => "Presensi Pulang berhasil dicatat pada jam {$currentTime} WITA!",
                'presensi' => $presensi
            ]);
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
        } catch (\Exception $e) {
            // Silently ignore if exists
        }
    }
}
