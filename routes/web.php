<?php

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

Route::get('/program', ProgramIndex::class)->name('program.index');
Route::get('/program/{slug}', ProgramShow::class)->name('program.show');

Route::get('/kontak', Contact::class)->name('kontak');

Route::get('/spmb', SpmbLanding::class)->name('spmb.index');
Route::get('/spmb/daftar', SpmbRegister::class)->middleware('throttle:5,1')->name('spmb.register');
Route::get('/spmb/status', SpmbStatus::class)->name('spmb.status');
