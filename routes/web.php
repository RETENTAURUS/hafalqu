<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardGuruController;
use App\Http\Controllers\DashboardSiswaController;
use App\Http\Controllers\AdminGuruController;
use App\Http\Controllers\AdminKelasController;
use App\Http\Controllers\AdminSiswaController;
use App\Http\Controllers\BankSoalController;
use App\Http\Controllers\GuruSiswaController;
use App\Http\Controllers\AdminBankSoalController;
use App\Http\Controllers\GuruQuizController;
use App\Http\Controllers\SiswaQuizController;
use App\Http\Controllers\GuruLeaderboardController;
use App\Http\Controllers\GuruLencanaController;
use App\Http\Controllers\AdminLencanaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SistemPoinController;
use App\Http\Controllers\SiswaLencanaController;
use App\Http\Controllers\GuruQuizMonitorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Awal & Autentikasi
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| GURU ROUTES (Prefix: guru) — middleware auth
|--------------------------------------------------------------------------
*/
Route::prefix('guru')->middleware(['auth'])->group(function () {
    // Dashboard Guru
    Route::get('/dashboard', [DashboardGuruController::class, 'index'])->name('guru.dashboard');

    // ================== BANK SOAL HAFALAN (GURU) ==================
    Route::get('/bank-soal', [BankSoalController::class, 'index'])->name('guru.soal.index');
    Route::post('/bank-soal/juz', [BankSoalController::class, 'storeJuz'])->name('guru.soal.storeJuz');
    Route::post('/bank-soal/juz/{juz_id}/surat', [BankSoalController::class, 'storeSurat'])->name('guru.soal.storeSurat');
    Route::get('/bank-soal/juz/{juz_id}', [BankSoalController::class, 'showJuz'])->name('guru.soal.showJuz');
    Route::post('/bank-soal/soal', [BankSoalController::class, 'storeSoal'])->name('guru.soal.storeSoal');
    Route::put('/bank-soal/soal/{id}', [BankSoalController::class, 'updateSoal'])->name('guru.soal.updateSoal');
    Route::delete('/bank-soal/soal/{id}', [BankSoalController::class, 'destroySoal'])->name('guru.soal.destroySoal');
// Tambahkan setelah baris destroySoal
Route::get('/bank-soal/soal/{id}',      [BankSoalController::class, 'showSoal'])->name('guru.soal.show');
Route::get('/bank-soal/soal/{id}/edit', [BankSoalController::class, 'editSoal'])->name('guru.soal.edit');
Route::put('/bank-soal/soal/{id}',     [BankSoalController::class, 'updateSoal'])->name('guru.soal.updateSoal');
Route::put('/soal/juz/{juz}', [BankSoalController::class, 'updateJuz'])->name('guru.soal.updateJuz');
Route::delete('/soal/juz/{juz}', [BankSoalController::class, 'destroyJuz'])->name('guru.soal.destroyJuz');

    // ================== MANAJEMEN SISWA (GURU) ==================
    Route::get('/siswa', [GuruSiswaController::class, 'index'])->name('guru.siswa.index');
    Route::post('/siswa', [GuruSiswaController::class, 'store'])->name('guru.siswa.store');
    Route::put('/siswa/{id}', [GuruSiswaController::class, 'update'])->name('guru.siswa.update');
    Route::delete('/siswa/{id}', [GuruSiswaController::class, 'destroy'])->name('guru.siswa.destroy');

    // ================== KELOLA QUIZ (GURU) ==================
    Route::get('/quiz', [GuruQuizController::class, 'index'])->name('guru.quiz.index');
    Route::get('/quiz/pilih-juz', [GuruQuizController::class, 'pilihJuz'])->name('guru.quiz.pilihJuz');
    Route::get('/quiz/surat/{juz_id}', [GuruQuizController::class, 'pilihSurat'])->name('guru.quiz.pilihSurat');
    Route::get('/quiz/konfigurasi', [GuruQuizController::class, 'konfigurasi'])->name('guru.quiz.konfigurasi');
    Route::post('/quiz/konfigurasi', [GuruQuizController::class, 'simpanKonfigurasi'])->name('guru.quiz.simpanKonfigurasi');
    Route::get('/quiz/preview', [GuruQuizController::class, 'preview'])->name('guru.quiz.preview');
    Route::post('/quiz', [GuruQuizController::class, 'store'])->name('guru.quiz.store');
    Route::delete('/quiz/{id}', [GuruQuizController::class, 'destroy'])->name('guru.quiz.destroy');
    Route::patch('/quiz/{id}/toggle', [GuruQuizController::class, 'toggleActive'])->name('guru.quiz.toggle');
    Route::get('/quiz/{id}/nilai', [GuruQuizController::class, 'lihatNilai'])->name('guru.quiz.nilai');


    // Route dummy lainnya
    Route::get('/lencana', function() { return "Kelola Lencana"; })->name('guru.lencana.index');
    Route::get('/leaderboard', function() { return "Papan Peringkat"; })->name('guru.leaderboard.index');
    Route::get('/laporan', function() { return "Kelola Laporan"; })->name('guru.laporan.index');

    Route::get('/leaderboard', [GuruLeaderboardController::class, 'index'])->name('guru.leaderboard.index');
    Route::get('/leaderboard/kelas/{kelas_id}', [GuruLeaderboardController::class, 'show'])->name('guru.leaderboard.show');

    Route::get('/lencana', [GuruLencanaController::class, 'index'])->name('guru.lencana.index');
    Route::get('/lencana/create', [GuruLencanaController::class, 'create'])->name('guru.lencana.create');
    Route::post('/lencana', [GuruLencanaController::class, 'store'])->name('guru.lencana.store');
    Route::get('/lencana/{id}/edit', [GuruLencanaController::class, 'edit'])->name('guru.lencana.edit');
    Route::put('/lencana/{id}', [GuruLencanaController::class, 'update'])->name('guru.lencana.update');
    Route::delete('/lencana/{id}', [GuruLencanaController::class, 'destroy'])->name('guru.lencana.destroy');
    Route::patch('/lencana/{id}/toggle', [GuruLencanaController::class, 'toggleActive'])->name('guru.lencana.toggle');


     
    Route::get('/laporan/',       [LaporanController::class, 'index'])       ->name('guru.laporan.index');
    Route::get('/laporan/export-pdf',     [LaporanController::class, 'exportPdf'])  ->name('guru.laporan.export-pdf');
    Route::get('/laporan/export-excel',   [LaporanController::class, 'exportExcel'])->name('guru.laporan.export-excel');

    Route::get('poin/',            [SistemPoinController::class, 'index'])        ->name('guru.poin.index');
    Route::post('poin/update-bobot',[SistemPoinController::class, 'updateBobot']) ->name('guru.poin.update-bobot');
    Route::post('poin/rekalkulasi', [SistemPoinController::class, 'rekalkulasi']) ->name('guru.poin.rekalkulasi');

    Route::get('/guru/quiz/{quiz}/live', [GuruQuizMonitorController::class, 'live'])
    ->name('guru.quiz.live');
 
Route::get('/guru/quiz/{quiz}/live-data', [GuruQuizMonitorController::class, 'liveData'])
    ->name('guru.quiz.live-data');




});

/*
|--------------------------------------------------------------------------
| SISWA ROUTES (Prefix: siswa) — middleware auth
|--------------------------------------------------------------------------
*/
Route::prefix('siswa')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardSiswaController::class, 'index'])->name('siswa.dashboard');

    // Quiz Siswa
    Route::get('/quiz', [SiswaQuizController::class, 'index'])->name('siswa.quiz.index');
    Route::get('/quiz/confirm/{quizId}', [SiswaQuizController::class, 'confirm'])->name('siswa.quiz.confirm');
    Route::get('/quiz/start/{quizId}', [SiswaQuizController::class, 'start'])->name('siswa.quiz.start');
    Route::get('/quiz/do/{attemptId}', [SiswaQuizController::class, 'doQuiz'])->name('siswa.quiz.do');
    Route::post('/quiz/save-answer/{attemptId}', [SiswaQuizController::class, 'saveAnswer'])->name('siswa.quiz.save-answer');
    Route::get('/quiz/answers/{attemptId}', [SiswaQuizController::class, 'getAnswers'])->name('siswa.quiz.getAnswers');
    Route::post('/quiz/finish/{attemptId}', [SiswaQuizController::class, 'finish'])->name('siswa.quiz.finish');
    Route::get('/quiz/result/{attemptId}', [SiswaQuizController::class, 'result'])->name('siswa.quiz.result');
    Route::get('/quiz/continue/{attemptId}', [SiswaQuizController::class, 'continueQuiz'])->name('siswa.quiz.continue');
    Route::get('/leaderboard', [DashboardSiswaController::class, 'leaderboard'])->name('siswa.leaderboard');
    Route::get('/lencana', [SiswaLencanaController::class, 'index'])->name('siswa.lencana.index');
    Route::get('/riwayat', [SiswaQuizController::class, 'riwayat'])->name('siswa.riwayat');
});
/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Prefix: admin) — middleware auth
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

    // Kelola Data Kelas
    Route::get('/kelas', [AdminKelasController::class, 'index'])->name('admin.kelas.index');
    Route::post('/kelas', [AdminKelasController::class, 'store'])->name('admin.kelas.store');
    Route::put('/kelas/{id}', [AdminKelasController::class, 'update'])->name('admin.kelas.update');
    Route::delete('/kelas/{id}', [AdminKelasController::class, 'destroy'])->name('admin.kelas.destroy');

    // Kelola Akun Guru
    Route::get('/guru', [AdminGuruController::class, 'index'])->name('admin.guru.index');
    Route::post('/guru', [AdminGuruController::class, 'store'])->name('admin.guru.store');
    Route::put('/guru/{id}', [AdminGuruController::class, 'update'])->name('admin.guru.update');
    Route::delete('/guru/{id}', [AdminGuruController::class, 'destroy'])->name('admin.guru.destroy');

    // Kelola Akun Siswa
    Route::get('/siswa', [AdminSiswaController::class, 'kelasList'])->name('admin.siswa.kelasList');
    Route::get('/siswa/kelas/{id}', [AdminSiswaController::class, 'showByKelas'])->name('admin.siswa.showByKelas');
    Route::get('/siswa/create/{kelas}', [AdminSiswaController::class, 'create'])->name('admin.siswa.create');
    Route::post('/siswa/store/{kelas}', [AdminSiswaController::class, 'store'])->name('admin.siswa.store');
    Route::get('/siswa/edit/{id}', [AdminSiswaController::class, 'edit'])->name('admin.siswa.edit');
    Route::put('/siswa/update/{id}', [AdminSiswaController::class, 'update'])->name('admin.siswa.update');
    Route::delete('/siswa/delete/{id}', [AdminSiswaController::class, 'destroy'])->name('admin.siswa.destroy');

    // ================== BANK SOAL HAFALAN (ADMIN) ==================
    Route::get('/bank-soal', [AdminBankSoalController::class, 'index'])->name('admin.soal.index');
    Route::post('/bank-soal/juz', [AdminBankSoalController::class, 'storeJuz'])->name('admin.soal.storeJuz');
    Route::post('/bank-soal/juz/{juz_id}/surat', [AdminBankSoalController::class, 'storeSurat'])->name('admin.soal.storeSurat');
    Route::get('/bank-soal/juz/{juz_id}', [AdminBankSoalController::class, 'showJuz'])->name('admin.soal.showJuz');
    Route::post('/bank-soal/soal', [AdminBankSoalController::class, 'storeSoal'])->name('admin.soal.storeSoal');
    Route::put('/bank-soal/soal/{id}', [AdminBankSoalController::class, 'updateSoal'])->name('admin.soal.updateSoal');
    Route::delete('/bank-soal/soal/{id}', [AdminBankSoalController::class, 'destroySoal'])->name('admin.soal.destroySoal');

    Route::get('/lencana', [AdminLencanaController::class, 'index'])->name('admin.lencana.index');
    Route::get('/lencana/create', [AdminLencanaController::class, 'create'])->name('admin.lencana.create');
    Route::post('/lencana', [AdminLencanaController::class, 'store'])->name('admin.lencana.store');
    Route::get('/lencana/{id}/edit', [AdminLencanaController::class, 'edit'])->name('admin.lencana.edit');
    Route::put('/lencana/{id}', [AdminLencanaController::class, 'update'])->name('admin.lencana.update');
    Route::delete('/lencana/{id}', [AdminLencanaController::class, 'destroy'])->name('admin.lencana.destroy');
    Route::patch('/lencana/{id}/toggle', [AdminLencanaController::class, 'toggleActive'])->name('admin.lencana.toggle');

});