<?php

use App\Http\Controllers\AboutPageController;
use App\Http\Controllers\AdminSellOrderController;
use App\Http\Controllers\BonusCodeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ParcelSettingController;
use App\Http\Controllers\SupportPageController;
use App\Http\Controllers\TransitLineController;
use App\Http\Controllers\TransitStationController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\GradeMasterController;
use App\Http\Controllers\HomeBannerController;
use App\Http\Controllers\MobileBrandController;
use App\Http\Controllers\MobileModelController;
use App\Http\Controllers\MobileProductCategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductGradeQuestionController;
use App\Http\Controllers\ProductQuestionController;
use App\Http\Controllers\SaleDetailController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ShowSettingController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return 'Laravel รันได้ปกติ';
});
Route::get('/', [PageController::class, 'home'])->name('home');
/*
|--------------------------------------------------------------------------
| User Auth
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [UserAuthController::class, 'login'])
        ->name('login.store');
    Route::get('/register', [UserAuthController::class, 'showRegisterForm'])
        ->name('register');
    Route::post('/register', [UserAuthController::class, 'register'])
        ->name('register.store');
    Route::view('/forgot-password', 'auth.forgot-password')
        ->name('forgot.password');
});
Route::post('/logout', [UserAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
/*
|--------------------------------------------------------------------------
| Sell Product
|--------------------------------------------------------------------------
*/
Route::get('/sell-product', [PageController::class, 'sellProduct'])
    ->name('sell.product');
Route::get('/sell-product/estimate', [PageController::class, 'sellProductEstimate'])
    ->name('sell.product.estimate');
Route::match(['get', 'post'], '/sell-product/checkout', [PageController::class, 'sellProductCheckout'])
    ->name('sell.product.checkout');
Route::post('/sell-product/orders/store', [PageController::class, 'storeSellOrder'])
    ->name('sell.product.orders.store');
Route::get('/sell-product/orders/{orderNo}/success', [PageController::class, 'sellOrderSuccess'])
    ->name('sell.product.orders.success');
/*
|--------------------------------------------------------------------------
| Pages
|--------------------------------------------------------------------------
*/
Route::get('/articles', [PageController::class, 'articles'])
    ->name('articles');
Route::get('/articles/{slug}', [PageController::class, 'articleDetail'])
    ->name('articles.detail');
Route::get('/faq', [PageController::class, 'faq'])
    ->name('faq');
Route::get('/about', [PageController::class, 'about'])
    ->name('about');
Route::get('/sell-at-Cashkub', [PageController::class, 'sellAtCashkub'])
    ->name('sell.at.Cashkub');

Route::get('/cancel-selling', [PageController::class, 'cancelSelling'])
    ->name('cancel.selling');
Route::get('/how-to-sell', [PageController::class, 'howToSell'])
    ->name('how.to.sell');
Route::get('/how-to-get-paid', [PageController::class, 'howToGetPaid'])
    ->name('how.to.get.paid');
/*
|--------------------------------------------------------------------------
| User Profile
|--------------------------------------------------------------------------
*/
Route::get('/profile', [PageController::class, 'profile'])
    ->middleware(['auth'])
    ->name('profile');

Route::post('/profile/reviews/store', [PageController::class, 'storeSellOrderReview'])
    ->middleware(['auth'])
    ->name('profile.reviews.store');
// admin login/logout
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// admin protected
Route::prefix('admin')->middleware('admin.auth')->group(function () {

    Route::get('/', [ShowSettingController::class, 'home']);

    Route::get('/home-banner', [HomeBannerController::class, 'index'])->name('admin.home-banner.index');
    Route::get('/home-banner/create', [HomeBannerController::class, 'form'])->name('admin.home-banner.create');
    Route::get('/home-banner/{id}/edit', [HomeBannerController::class, 'form'])->name('admin.home-banner.edit');
    Route::post('/home-banner/store', [HomeBannerController::class, 'store'])->name('admin.home-banner.store');
    Route::post('/home-banner/{id}/update', [HomeBannerController::class, 'update'])->name('admin.home-banner.update');
    Route::post('/home-banner/delete', [HomeBannerController::class, 'destroy'])->name('admin.home-banner.delete');

    Route::get('/support-pages/{slug?}', [SupportPageController::class, 'edit'])
        ->name('admin.support-pages.index');
    Route::get('/support-pages/{slug}/edit', [SupportPageController::class, 'edit'])
        ->name('admin.support-pages.edit');
    Route::post('/support-pages/{slug}/update', [SupportPageController::class, 'update'])
        ->name('admin.support-pages.update');

    Route::get('news', [NewsController::class, 'index']);
    Route::get('form-news', [NewsController::class, 'formNews']);
    Route::get('news/{id}', [NewsController::class, 'formNews']);
    Route::post('save-news', [NewsController::class, 'saveNews']);
    Route::post('delete-news', [NewsController::class, 'deleteNews']);
    Route::post('/news/upload-editor-image', [NewsController::class, 'uploadEditorImage'])
        ->name('admin.news.upload-editor-image');

    Route::get('sale-detail', [SaleDetailController::class, 'edit']);
    Route::post('save-sale-detail', [SaleDetailController::class, 'save']);

    Route::get('/branches', [BranchController::class, 'index'])->name('admin.branches.index');
    Route::get('/branches/create', [BranchController::class, 'create'])->name('admin.branches.create');
    Route::post('/branches/store', [BranchController::class, 'store'])->name('admin.branches.store');
    Route::get('/branches/{id}/edit', [BranchController::class, 'edit'])->name('admin.branches.edit');
    Route::post('/branches/{id}/update', [BranchController::class, 'update'])->name('admin.branches.update');
    Route::post('/branches/delete', [BranchController::class, 'delete'])->name('admin.branches.delete');
    Route::post('/branches/service-time-slots/store', [BranchController::class, 'storeTimeSlot'])
        ->name('admin.branches.time-slots.store');
    Route::post('/branches/service-time-slots/{id}/update', [BranchController::class, 'updateTimeSlot'])
        ->name('admin.branches.time-slots.update');
    Route::post('/branches/service-time-slots/delete', [BranchController::class, 'deleteTimeSlot'])
        ->name('admin.branches.time-slots.delete');

    Route::get('/parcel-setting', [ParcelSettingController::class, 'edit'])->name('admin.parcel-setting.edit');
    Route::post('/parcel-setting/update', [ParcelSettingController::class, 'update'])->name('admin.parcel-setting.update');
    Route::post('/parcel-setting/documents/store', [ParcelSettingController::class, 'storeDocument'])->name('admin.parcel-setting.documents.store');
    Route::post('/parcel-setting/documents/{id}/update', [ParcelSettingController::class, 'updateDocument'])->name('admin.parcel-setting.documents.update');
    Route::post('/parcel-setting/documents/delete', [ParcelSettingController::class, 'deleteDocument'])->name('admin.parcel-setting.documents.delete');


    Route::get('/sell-orders', [AdminSellOrderController::class, 'index']);
    Route::get('/sell-orders/{id}', [AdminSellOrderController::class, 'show']);
    Route::post('/sell-orders/{id}/update-status', [AdminSellOrderController::class, 'updateStatus']);
    Route::post('/sell-orders/{id}/update-price', [AdminSellOrderController::class, 'updatePrice']);
    Route::post('sell-orders/{id}/review', [AdminSellOrderController::class, 'storeReview'])
        ->name('admin.sell-orders.review.store');
    Route::post('admin/sell-orders/{id}/review-display', [AdminSellOrderController::class, 'updateReviewDisplay'])
        ->name('admin.sell-orders.review-display.update');

    Route::get('/mobile-brands', [MobileBrandController::class, 'index']);
    Route::get('/mobile-brands/create', [MobileBrandController::class, 'create']);
    Route::post('/mobile-brands/store', [MobileBrandController::class, 'store']);
    Route::get('/mobile-brands/{id}/edit', [MobileBrandController::class, 'edit']);
    Route::post('/mobile-brands/{id}/update', [MobileBrandController::class, 'update']);
    Route::post('/mobile-brands/delete', [MobileBrandController::class, 'destroy']);

    Route::get('/mobile-product-categories', [MobileProductCategoryController::class, 'index']);
    Route::get('/mobile-product-categories/create', [MobileProductCategoryController::class, 'create']);
    Route::post('/mobile-product-categories/store', [MobileProductCategoryController::class, 'store']);
    Route::get('/mobile-product-categories/{id}/edit', [MobileProductCategoryController::class, 'edit']);
    Route::post('/mobile-product-categories/{id}/update', [MobileProductCategoryController::class, 'update']);
    Route::post('/mobile-product-categories/delete', [MobileProductCategoryController::class, 'destroy']);

    Route::get('/mobile-product-categories/{category}/product-questions', [ProductQuestionController::class, 'indexByCategory']);
    Route::get('/mobile-product-categories/{category}/product-grade-questions', [ProductGradeQuestionController::class, 'indexByCategory']);

    Route::get('/mobile-models/brand/{brand}', [MobileModelController::class, 'indexByBrand']);
    Route::get('/mobile-models/category/{category}', [MobileModelController::class, 'index']);
    Route::get('/mobile-models/category/{category}/create', [MobileModelController::class, 'create']);
    Route::get('/mobile-models/brand/{brand}/create', [MobileModelController::class, 'createByBrand']);
    Route::post('/mobile-models/store', [MobileModelController::class, 'store']);
    Route::get('/mobile-models/{id}/edit', [MobileModelController::class, 'edit']);
    Route::post('/mobile-models/{id}/update', [MobileModelController::class, 'update']);
    Route::post('/mobile-models/delete', [MobileModelController::class, 'destroy']);
    Route::get('/mobile-models/category/{category}/export', [MobileModelController::class, 'export']);
    Route::get('/mobile-models/category/{category}/export-template', [MobileModelController::class, 'exportTemplate']);
    Route::get('/mobile-models/brand/{brand}/export', [MobileModelController::class, 'exportByBrand']);
    Route::get('/mobile-models/brand/{brand}/export-template', [MobileModelController::class, 'exportTemplateByBrand']);
    Route::post('/mobile-models/import', [MobileModelController::class, 'import']);


    Route::get('/product-questions', [ProductQuestionController::class, 'index']);
    Route::get('/product-questions/create', [ProductQuestionController::class, 'create']);
    Route::post('/product-questions/store', [ProductQuestionController::class, 'store']);
    Route::get('/product-questions/{id}/edit', [ProductQuestionController::class, 'edit']);
    Route::post('/product-questions/{id}/update', [ProductQuestionController::class, 'update']);
    Route::post('/product-questions/delete', [ProductQuestionController::class, 'destroy']);

    Route::get('product-grade-questions/by-category', [ProductGradeQuestionController::class, 'categoryLayer'])
        ->name('product-grade-questions.category-layer');
    Route::get('product-grade-questions/by-category/{category}/brands', [ProductGradeQuestionController::class, 'brandLayer'])
        ->name('product-grade-questions.brand-layer');
    Route::get('product-grade-questions/by-category/{category}/brands/{brand}', [ProductGradeQuestionController::class, 'questionLayer'])
        ->name('product-grade-questions.question-layer');
    Route::get('/product-grade-questions', [ProductGradeQuestionController::class, 'index']);
    Route::get('/product-grade-questions/create', [ProductGradeQuestionController::class, 'create']);
    Route::post('/product-grade-questions/store', [ProductGradeQuestionController::class, 'store']);
    Route::get('/product-grade-questions/{id}/edit', [ProductGradeQuestionController::class, 'edit']);
    Route::post('/product-grade-questions/{id}/update', [ProductGradeQuestionController::class, 'update']);
    Route::post('/product-grade-questions/delete', [ProductGradeQuestionController::class, 'destroy']);
    Route::get('product-grade-questions/by-brand/{brand}', [ProductGradeQuestionController::class, 'indexByBrand'])
        ->name('product-grade-questions.by-brand');

    Route::get('/transit-lines', [TransitLineController::class, 'index'])->name('admin.transit-lines.index');
    Route::get('/transit-lines/create', [TransitLineController::class, 'create'])->name('admin.transit-lines.create');
    Route::post('/transit-lines/store', [TransitLineController::class, 'store'])->name('admin.transit-lines.store');
    Route::get('/transit-lines/{id}/edit', [TransitLineController::class, 'edit'])->name('admin.transit-lines.edit');
    Route::post('/transit-lines/{id}/update', [TransitLineController::class, 'update'])->name('admin.transit-lines.update');
    Route::post('/transit-lines/delete', [TransitLineController::class, 'delete'])->name('admin.transit-lines.delete');

    Route::get('/transit-stations', [TransitStationController::class, 'index'])->name('admin.transit-stations.index');
    Route::get('/transit-stations/create', [TransitStationController::class, 'create'])->name('admin.transit-stations.create');
    Route::post('/transit-stations/store', [TransitStationController::class, 'store'])->name('admin.transit-stations.store');
    Route::get('/transit-stations/{id}/edit', [TransitStationController::class, 'edit'])->name('admin.transit-stations.edit');
    Route::post('/transit-stations/{id}/update', [TransitStationController::class, 'update'])->name('admin.transit-stations.update');
    Route::post('/transit-stations/delete', [TransitStationController::class, 'delete'])->name('admin.transit-stations.delete');



    Route::get('/about-page/edit', [AboutPageController::class, 'edit'])->name('admin.about-page.edit');
    Route::post('/about-page/update', [AboutPageController::class, 'update'])->name('admin.about-page.update');

    Route::get('/grade-masters', [GradeMasterController::class, 'index']);
    Route::get('/grade-masters/create', [GradeMasterController::class, 'create']);
    Route::post('/grade-masters/store', [GradeMasterController::class, 'store']);
    Route::get('/grade-masters/{id}/edit', [GradeMasterController::class, 'edit']);
    Route::post('/grade-masters/{id}/update', [GradeMasterController::class, 'update']);
    Route::post('/grade-masters/delete', [GradeMasterController::class, 'destroy']);


    Route::get('/bonus-codes', [BonusCodeController::class, 'index']);
    Route::get('/bonus-codes/create', [BonusCodeController::class, 'create']);
    Route::post('/bonus-codes/store', [BonusCodeController::class, 'store']);
    Route::get('/bonus-codes/{id}/edit', [BonusCodeController::class, 'edit']);
    Route::post('/bonus-codes/{id}/update', [BonusCodeController::class, 'update']);
    Route::post('/bonus-codes/delete', [BonusCodeController::class, 'destroy']);

    Route::get('/user', [AdminUserController::class, 'index']);
    Route::get('/user-add', [AdminUserController::class, 'create']);
    Route::post('/user-store', [AdminUserController::class, 'store']);
    Route::get('/user/{id}/edit', [AdminUserController::class, 'edit']);
    Route::post('/user/{id}/update', [AdminUserController::class, 'update']);
    Route::post('/user/delete', [AdminUserController::class, 'destroy']);
});
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return 'All cache cleared';
});
