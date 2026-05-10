<?php

use App\Livewire\CalendarPage;
use App\Livewire\Pages\AcademicIndex;
use App\Livewire\Pages\AcademicShow;
use App\Livewire\Pages\AchievementIndex;
use App\Livewire\Pages\AchievementShow;
use App\Livewire\Pages\Contact;
use App\Livewire\Pages\DownloadIndex;
use App\Livewire\Pages\FacilityIndex;
use App\Livewire\Pages\GalleryIndex;
use App\Livewire\Pages\GalleryShow;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\NewsIndex;
use App\Livewire\Pages\NewsShow;
use App\Livewire\Pages\ProgramIndex;
use App\Livewire\Pages\ProgramShow;
use App\Livewire\Pages\Profile;
use App\Livewire\Pages\SpmbLanding;
use App\Livewire\Pages\SpmbRegister;
use App\Livewire\Pages\SpmbStatus;
use App\Livewire\Pages\MaterialIndex;
use App\Livewire\Pages\MaterialShow;
use App\Livewire\Pages\StaffIndex;
use App\Livewire\Pages\StaffScheduleIndex;
use App\Livewire\Pages\StaffShow;
use App\Http\Controllers\MaterialDownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/profil', Profile::class)->name('profil');

Route::get('/akademik', AcademicIndex::class)->name('akademik.index');
Route::get('/akademik/{slug}', AcademicShow::class)->name('akademik.show');

Route::get('/fasilitas', FacilityIndex::class)->name('fasilitas.index');

Route::get('/prestasi', AchievementIndex::class)->name('prestasi.index');
Route::get('/prestasi/{slug}', AchievementShow::class)->name('prestasi.show');

Route::get('/galeri', GalleryIndex::class)->name('galeri.index');
Route::get('/galeri/{slug}', GalleryShow::class)->name('galeri.show');

Route::get('/berita', NewsIndex::class)->name('berita.index');
Route::get('/berita/{slug}', NewsShow::class)->name('berita.show');

Route::get('/download', DownloadIndex::class)->name('download.index');

Route::get('/kalender', CalendarPage::class)->name('kalender');

Route::get('/program', ProgramIndex::class)->name('program.index');
Route::get('/program/{slug}', ProgramShow::class)->name('program.show');

Route::get('/guru', StaffIndex::class)->name('staff.index');
Route::get('/guru/{slug}', StaffShow::class)->name('staff.show');

Route::get('/materi', MaterialIndex::class)->name('materials.index');
Route::get('/materi/{slug}', MaterialShow::class)->name('materials.show');
Route::get('/materi/{slug}/download', MaterialDownloadController::class)->name('materials.download');

Route::get('/jadwal', StaffScheduleIndex::class)->name('jadwal.index');

Route::get('/kontak', Contact::class)->name('kontak');

Route::get('/spmb', SpmbLanding::class)->name('spmb.index');
Route::get('/spmb/daftar', SpmbRegister::class)->middleware('throttle:5,1')->name('spmb.register');
Route::get('/spmb/status', SpmbStatus::class)->name('spmb.status');

// Konseling BK (publik)
Route::get('/bk', \App\Livewire\Pages\CounselingForm::class)->middleware('throttle:10,1')->name('bk.form');
Route::get('/bk/status', \App\Livewire\Pages\CounselingStatus::class)->middleware('throttle:30,1')->name('bk.status');

// Surat Izin Online (publik)
Route::get('/izin', \App\Livewire\Pages\PublicLeaveForm::class)->middleware('throttle:5,1')->name('izin.form');
Route::get('/izin/status', \App\Livewire\Pages\PublicLeaveStatus::class)->middleware('throttle:30,1')->name('izin.status');

// Portal Siswa
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/login', \App\Livewire\Portal\Login::class)->name('login');
    Route::post('/logout', function () {
        \Illuminate\Support\Facades\Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('portal.login');
    })->name('logout');

    Route::middleware(['auth'])->group(function () {
        Route::get('/', \App\Livewire\Portal\Dashboard::class)->name('dashboard');
        Route::get('/tugas', \App\Livewire\Portal\AssignmentIndex::class)->name('assignments.index');
        Route::get('/tugas/{slug}', \App\Livewire\Portal\AssignmentShow::class)->name('assignments.show');
        Route::get('/pengumuman', \App\Livewire\Portal\AnnouncementIndex::class)->name('announcements.index');
        Route::get('/pengumuman/{slug}', \App\Livewire\Portal\AnnouncementShow::class)->name('announcements.show');
        Route::get('/materi', \App\Livewire\Portal\MaterialIndex::class)->name('materials.index');
        Route::get('/latihan', \App\Livewire\Portal\QuizIndex::class)->name('quizzes.index');
        Route::get('/latihan/{slug}', \App\Livewire\Portal\QuizShow::class)->name('quizzes.show');
        Route::get('/latihan/{slug}/kerjakan/{attempt}', \App\Livewire\Portal\QuizPlay::class)->name('quizzes.play');
        Route::get('/latihan/{slug}/hasil/{attempt}', \App\Livewire\Portal\QuizResult::class)->name('quizzes.result');
        Route::get('/latihan/{slug}/leaderboard', \App\Livewire\Portal\QuizLeaderboard::class)->name('quizzes.leaderboard');
        Route::get('/bk', \App\Livewire\Portal\CounselingIndex::class)->name('counseling.index');
        Route::get('/bk/buat', \App\Livewire\Portal\CounselingCreate::class)->name('counseling.create');
        Route::get('/bk/{ticket}', \App\Livewire\Portal\CounselingShow::class)->name('counseling.show');
        Route::get('/profil', \App\Livewire\Portal\Profile::class)->name('profile');
    });
});

// Portal Orang Tua
Route::prefix('portal/ortu')->name('portal.parent.')->group(function () {
    Route::get('/login', \App\Livewire\Portal\ParentPortal\Login::class)->name('login');
    Route::post('/logout', function () {
        \Illuminate\Support\Facades\Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('portal.parent.login');
    })->name('logout');

    Route::middleware(['auth'])->group(function () {
        Route::get('/', \App\Livewire\Portal\ParentPortal\Dashboard::class)->name('dashboard');
        Route::get('/nilai/{student}', \App\Livewire\Portal\ParentPortal\Grades::class)->name('grades');
        Route::get('/absensi/{student}', \App\Livewire\Portal\ParentPortal\Attendance::class)->name('attendance');
        Route::get('/pelanggaran/{student}', \App\Livewire\Portal\ParentPortal\Violations::class)->name('violations');
        Route::get('/pembayaran/{student}', \App\Livewire\Portal\ParentPortal\Payments::class)->name('payments');
        Route::get('/izin/{student}', \App\Livewire\Portal\ParentPortal\LeaveRequestIndex::class)->name('leave.index');
        Route::get('/izin/{student}/buat', \App\Livewire\Portal\ParentPortal\LeaveRequestCreate::class)->name('leave.create');
    });
});
