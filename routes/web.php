<?php

use App\Http\Controllers\Admin\ActionLogController;
use App\Http\Controllers\Admin\AdminIndexController;
use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Admin\ChangelogsController;
use App\Http\Controllers\Admin\ConfigurationsController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\InformationController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\Admin\MessagesController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\NewsesController;
use App\Http\Controllers\Admin\SeosController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SlidersController;
use App\Http\Controllers\Admin\TextpagesController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\DoctorsController;
use App\Http\Controllers\Front\HistoryController as ClientHistory;
use App\Http\Controllers\Front\IndexController;
use App\Http\Controllers\Front\NewsController as ClientNews;
use App\Http\Controllers\Front\ServicesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');

    return 'Done - cache are cleared';
});

Route::prefix(LaravelLocalization::setLocale())->middleware('localeSessionRedirect', 'localizationRedirect', 'localeViewPath')->group(function () {

    Route::get('/', [IndexController::class, 'index'])->name('index');
    Route::get('/services', [ServicesController::class, 'index'])->name('services');
    Route::get('/service/{id}', [ServicesController::class, 'in'])->name('service');
    Route::get('/services/search', [ServicesController::class, 'search'])->name('serviceSearch');
    Route::get('/doctors', [DoctorsController::class, 'index'])->name('doctors');
    Route::get('/doctor/{id}', [DoctorsController::class, 'in'])->name('doctor');
    Route::get('/doctors/search', [DoctorsController::class, 'search'])->name('doctorSearch');
    Route::get('/news', [ClientNews::class, 'index'])->name('newses');
    Route::get('/news/{id}', [ClientNews::class, 'in'])->name('news');
    Route::get('/history', [ClientHistory::class, 'index'])->name('history');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'send'])->name('sendMessage');

});

Route::get('/admin/login', [LoginController::class, 'index'])->middleware('AdminLogin')->name('LoginPageAdmin');
Route::post('/admin/singin', [LoginController::class, 'singin'])->middleware('AdminLogin')->name('LoginAdmin');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('LogoutAdmin');

Route::middleware(['admin', 'check_permission'])->group(function () {

    Route::prefix('admin')->group(function () {

        // ადმინისტრატორის პანელის მთავარი გვერდი
        Route::get('/', [AdminIndexController::class, 'index'])->name('AdminMainPage');

        /*
         * ყველა მოდულისათვის საერთო მეთოდები
         */

        // integer ტიპის ისეთი ველების განახლება, რომელთა შესაძლო მნიშვნელობებიცაა 0 და 1
        Route::post('status', [BaseController::class, 'status'])->name('Status');
        // წაშლა
        Route::post('/remove', [BaseController::class, 'remove'])->name('Remove');
        // სტატუსის შეცვლა რამოდენიმე ელემენტზე ერთდროულად ან მათი წაშლა
        Route::post('/multi', [BaseController::class, 'multi'])->name('Multi');
        // თანმიმდევრობის შეცვლა ჩამონათვალის გვერდზე
        Route::post('/ordering', [BaseController::class, 'ordering'])->name('Ordering');
        // მიმაგრებული ფაილის წაშლა და შესაბამისი ველის მნიშვნელობად null
        Route::post('/remove_file', [BaseController::class, 'remove_file'])->name('RemoveFile');
        // ფოტოს წაშლა გალერიიდან
        Route::post('/remove_image_from_gallery', [BaseController::class, 'remove_image_from_gallery'])->name('RemoveImageFromGallery');
        // ვიდეოს წაშლა გალერიიდან
        Route::post('/remove_video_from_gallery', [BaseController::class, 'remove_video_from_gallery'])->name('RemoveVideoFromGallery');

        // სლაიდერი
        Route::prefix('sliders')->group(function () {
            Route::get('/', [SlidersController::class, 'index'])->name('Sliders');
            Route::get('/add', [SlidersController::class, 'create'])->name('AddSliders');
            Route::post('create', [SlidersController::class, 'store'])->name('StoreSliders');
            Route::get('/edit/{id}', [SlidersController::class, 'edit'])->name('EditSliders');
            Route::post('update/{id}', [SlidersController::class, 'update'])->name('UpdateSliders');
        });

        // სერვისი
        Route::prefix('services')->group(function () {
            Route::get('/', [ServiceController::class, 'index'])->name('Services');
            Route::get('/add', [ServiceController::class, 'create'])->name('AddServices');
            Route::post('create', [ServiceController::class, 'store'])->name('StoreServices');
            Route::get('/edit/{id}', [ServiceController::class, 'edit'])->name('EditServices');
            Route::post('update/{id}', [ServiceController::class, 'update'])->name('UpdateServices');
        });

        // ექიმები
        Route::prefix('doctors')->group(function () {
            Route::get('/', [DoctorController::class, 'index'])->name('Doctors');
            Route::get('/add', [DoctorController::class, 'create'])->name('AddDoctors');
            Route::post('create', [DoctorController::class, 'store'])->name('StoreDoctors');
            Route::get('/edit/{id}', [DoctorController::class, 'edit'])->name('EditDoctors');
            Route::post('update/{id}', [DoctorController::class, 'update'])->name('UpdateDoctors');
        });

        // ისტორია
        Route::prefix('history')->group(function () {
            Route::get('/edit', [HistoryController::class, 'edit'])->name('EditHistories');
            Route::post('/update/{id}', [HistoryController::class, 'update'])->name('UpdateHistories');
        });

        Route::prefix('newses')->group(function () {

            Route::get('/', [NewsesController::class, 'index'])->name('Newses');

            // სიხლეები
            Route::prefix('/news')->group(function () {
                Route::get('/', [NewsController::class, 'index'])->name('News');
                Route::get('/add', [NewsController::class, 'create'])->name('AddNews');
                Route::post('create', [NewsController::class, 'store'])->name('StoreNews');
                Route::get('/edit/{id}', [NewsController::class, 'edit'])->name('EditNews');
                Route::post('update/{id}', [NewsController::class, 'update'])->name('UpdateNews');
            });

        });

        // ტექსტური გვერდები
        Route::prefix('textpages')->group(function () {
            Route::get('/', [TextpagesController::class, 'index'])->name('Textpages');
            Route::get('/add', [TextpagesController::class, 'create'])->name('AddTextpages');
            Route::post('create', [TextpagesController::class, 'store'])->name('StoreTextpages');
            Route::get('/edit/{id}', [TextpagesController::class, 'edit'])->name('EditTextpages');
            Route::post('update/{id}', [TextpagesController::class, 'update'])->name('UpdateTextpages');
        });

        // ამ გვერდებზე შესვლის უფლება აქვს მხოლოდ სუპერადმინს
        Route::middleware('check_if_super')->group(function () {

            Route::prefix('messages')->group(function () {
                Route::get('/', [MessagesController::class, 'index'])->name('Messages');
                Route::get('/remove/{id}', [MessagesController::class, 'remove'])->name('RemoveMessages');
                Route::post('/seen', [MessagesController::class, 'seen'])->name('SeenMessages');
            });

            // საკონტაქტო ინფორმაციის გვერდი
            Route::prefix('informations')->group(function () {
                Route::get('/', [InformationController::class, 'edit'])->name('EditInformations');
                Route::post('/update/{id}', [InformationController::class, 'update'])->name('UpdateInformations');
            });

            // SEO
            Route::prefix('seos')->group(function () {
                Route::get('/', [SeosController::class, 'index'])->name('Seos');
                Route::get('/add', [SeosController::class, 'create'])->name('AddSeos');
                Route::post('create', [SeosController::class, 'store'])->name('StoreSeos');
                Route::get('/edit/{route}', [SeosController::class, 'edit'])->name('EditSeos');
                Route::post('update/{id}', [SeosController::class, 'update'])->name('UpdateSeos');
            });

            // ადმინისტრატორები
            Route::prefix('admins')->group(function () {
                Route::get('/', [AdminsController::class, 'index'])->name('Admins');
                Route::get('/add', [AdminsController::class, 'create'])->name('AddAdmins');
                Route::post('create', [AdminsController::class, 'store'])->name('StoreAdmins');
                Route::get('/edit/{id}', [AdminsController::class, 'edit'])->name('EditAdmins');
                Route::post('update/{id}', [AdminsController::class, 'update'])->name('UpdateAdmins');
                Route::post('remove', [AdminsController::class, 'remove'])->name('RemoveAdmins');
            });

            // ჟურნალი
            Route::prefix('logs')->group(function () {

                Route::get('/', [LogsController::class, 'index'])->name('Logs');

                Route::prefix('changelog')->group(function () {
                    Route::get('/', [ChangelogsController::class, 'index'])->name('Changelogs');
                });

                Route::prefix('operationlog')->group(function () {
                    Route::get('/', [ActionLogController::class, 'index'])->name('Operationlogs');
                });

            });

            // საიტის კონფიგურაციული პარამეტრები
            Route::prefix('configuration')->group(function () {
                Route::get('/', [ConfigurationsController::class, 'edit'])->name('EditConfigurations');
                Route::post('/update/{id}', [ConfigurationsController::class, 'update'])->name('UpdateConfigurations');
                Route::get('/remove_cache_key/{key}', [ConfigurationsController::class, 'remove_cache_key'])->name('RemoveCacheKeyConfigurations');
            });

        });

    });
});
