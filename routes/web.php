<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StandingsController;
use App\Http\Controllers\MatchCenterController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AchievementsController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\TrainingUpdatesController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// About
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Achievements
Route::get('/achievements', [AchievementsController::class, 'index'])->name('achievements');

// Events
Route::get('/events', [EventsController::class, 'index'])->name('events');

// Training Updates
Route::get('/training-updates', [TrainingUpdatesController::class, 'index'])->name('training-updates');

// Announcements
Route::get('/announcements', [AnnouncementsController::class, 'index'])->name('announcements');

// Players
Route::get('/players', [PlayerController::class, 'index'])->name('players.index');
Route::get('/players/{id}/stats', [PlayerController::class, 'stats'])->name('players.stats')->where('id', '[0-9]+');
Route::get('/players/{id}/overview', [PlayerController::class, 'overview'])->name('players.overview')->where('id', '[0-9]+');
Route::get('/players/{id}/statistics', [PlayerController::class, 'statistics'])->name('players.statistics')->where('id', '[0-9]+');
Route::get('/players/{id}/ai-insights', [PlayerController::class, 'aiInsights'])->name('players.ai-insights')->where('id', '[0-9]+');
Route::get('/players/{id}/biography', [PlayerController::class, 'biography'])->name('players.biography')->where('id', '[0-9]+');
Route::get('/players/{id}/career', [PlayerController::class, 'career'])->name('players.career')->where('id', '[0-9]+');
Route::post('/players/{id}/record-stats', [PlayerController::class, 'recordGameStats'])->name('players.record-stats')->where('id', '[0-9]+');
Route::get('/players/search', [PlayerController::class, 'searchPlayers'])->name('players.search');
Route::get('/players/{id}', [PlayerController::class, 'show'])->name('home.player.show')->where('id', '[0-9]+');

// Admin route to sync players from gallery
Route::get('/admin/players/sync-gallery', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'syncFromGallery'])
    ->name('admin.players.sync-gallery')
    ->middleware(['auth', 'admin']);


// Programs
Route::get('/programs', [ProgramController::class, 'index'])->name('programs');
Route::get('/programs/{id}', [ProgramController::class, 'show'])->name('program_detail');
Route::get('/enroll', [ProgramController::class, 'showEnrollmentForm'])->name('enroll');
Route::post('/enroll', [ProgramController::class, 'enroll'])->name('programs.enroll');
Route::get('/register', [ProgramController::class, 'showRegistrationChoice'])->name('register');
Route::get('/register/player', [ProgramController::class, 'registerPlayer'])->name('register.player');
Route::post('/register/player', [ProgramController::class, 'storePlayer'])->name('register.player.store');
Route::get('/register/partner', [ProgramController::class, 'registerPartner'])->name('register.partner');
Route::post('/register/partner', [ProgramController::class, 'storePartner'])->name('register.partner.store');
Route::get('/registration/success/{type}', [ProgramController::class, 'showRegistrationSuccess'])->name('registration.success');
Route::get('/my-enrollments', [ProgramController::class, 'myEnrollments'])->middleware('auth')->name('enrollments');

// News
Route::get('/news', [NewsController::class, 'index'])->name('news');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');
Route::get('/news/search', [NewsController::class, 'search'])->name('news.search');
Route::post('/newsletter/subscribe', [NewsController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

// Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

// Staff
Route::get('/staff', [StaffController::class, 'index'])->name('staff');

// Standings
Route::get('/standings', [StandingsController::class, 'index'])->name('standings');
Route::get('/standings/league-table', [StandingsController::class, 'leagueTable'])->name('standings.league-table');
Route::get('/standings/top-scorers', [StandingsController::class, 'topScorers'])->name('standings.top-scorers');
Route::get('/standings/clean-sheets', [StandingsController::class, 'cleanSheets'])->name('standings.clean-sheets');
Route::get('/standings/goalkeepers', [StandingsController::class, 'goalkeepers'])->name('standings.goalkeepers');

// Match Center
Route::get('/match-center', [MatchCenterController::class, 'index'])->name('match-center');
Route::get('/match-center/{id}', [MatchCenterController::class, 'show'])->name('match-center.show');

// Transfer News
Route::get('/transfer-news', [NewsController::class, 'transfers'])->name('transfer-news');

// Player Rankings
Route::get('/player-rankings', [PlayerController::class, 'rankings'])->name('player-rankings');

// Statistics Hub
Route::get('/statistics-hub', [StandingsController::class, 'statisticsHub'])->name('statistics-hub');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Careers
Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{id}', [CareerController::class, 'show'])->name('careers.show');
Route::post('/careers/{id}/apply', [CareerController::class, 'store'])->name('careers.apply');
Route::get('/careers/application/success', [CareerController::class, 'applicationSuccess'])->name('careers.application.success');

// Search
Route::get('/search', function(Request $request) {
    // Basic search implementation - can be expanded
    $query = $request->input('q');
    if (!$query) {
        return redirect()->route('home');
    }
    // For now, redirect to home with search param or implement search controller
    return redirect()->route('home')->with('search', $query);
})->name('search');

// Products
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/suggestions', [ProductController::class, 'suggestions'])->name('suggestions');
    Route::get('/category/{category}', [ProductController::class, 'category'])->name('category');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
});

// Cart
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/summary', [CartController::class, 'getCartSummary'])->name('summary');
    Route::post('/add', [CartController::class, 'addToCart'])->name('add');
    Route::put('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clearCart'])->name('clear');
    Route::get('/status', [CartController::class, 'checkAuthStatus'])->name('status');
    Route::post('/validate/email', [CartController::class, 'validateEmail'])->name('validate.email');
    Route::post('/login/player', [CartController::class, 'playerLogin'])->name('login.player');
    Route::post('/login/partner', [CartController::class, 'partnerLogin'])->name('login.partner');
    Route::post('/register/visitor', [CartController::class, 'visitorRegister'])->name('register.visitor');
});

// Help Center
Route::get('/help', function() {
    return view('help.center');
})->name('help.center');

// Orders
Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index')->middleware('auth');
    Route::get('/track', function() {
        return view('orders.track');
    })->name('track');
});

// Dashboard
Route::get('/dashboard', function() {
    return view('website.dashboard');
})->middleware('auth')->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Addresses
Route::middleware('auth')->prefix('addresses')->name('addresses.')->group(function () {
    Route::get('/', [AddressController::class, 'index'])->name('index');
});

// Wishlist
Route::middleware('auth')->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
});

// Checkout
Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/coupon/apply', [CheckoutController::class, 'applyCoupon'])->name('coupon.apply');
    Route::delete('/coupon/remove', [CheckoutController::class, 'removeCoupon'])->name('coupon.remove');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::get('/payment/{order}', [CheckoutController::class, 'payment'])->name('payment');
    Route::post('/payment/{order}', [CheckoutController::class, 'processPayment'])->name('payment.process');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});

// Installment Info
Route::get('/installment/info', function() {
    return view('installment.info');
})->name('installment.info');

// Additional Auth Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', function () {
    $credentials = request()->only('email', 'password');

    if (auth()->attempt($credentials)) {
        request()->session()->regenerate();

        $user = auth()->user();

        // Redirect based on user type
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isPlayer()) {
            return redirect()->route('player.portal.dashboard');
        } elseif ($user->isPartner()) {
            return redirect()->route('partner.dashboard');
        } else {
            // Default redirect for visitors or other user types
            return redirect()->intended('/');
        }
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
})->name('login.post')->middleware('guest')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Players Management
    Route::get('/players', [App\Http\Controllers\Admin\AdminPlayerController::class, 'index'])->name('players.index');
    Route::get('/players/create', [App\Http\Controllers\Admin\AdminPlayerController::class, 'create'])->name('players.create');
    Route::post('/players', [App\Http\Controllers\Admin\AdminPlayerController::class, 'store'])->name('players.store');
    Route::get('/players/{player}', [App\Http\Controllers\Admin\AdminPlayerController::class, 'show'])->name('players.show');
    Route::get('/players/{player}/edit', [App\Http\Controllers\Admin\AdminPlayerController::class, 'edit'])->name('players.edit');
    Route::put('/players/{player}', [App\Http\Controllers\Admin\AdminPlayerController::class, 'update'])->name('players.update');
    Route::delete('/players/{player}', [App\Http\Controllers\Admin\AdminPlayerController::class, 'destroy'])->name('players.destroy');

    // Partners Management
    Route::get('/partners', [App\Http\Controllers\Admin\AdminPartnerController::class, 'index'])->name('partners.index');
    Route::get('/partners/{partner}', [App\Http\Controllers\Admin\AdminPartnerController::class, 'show'])->name('partners.show');
    Route::put('/partners/{partner}', [App\Http\Controllers\Admin\AdminPartnerController::class, 'update'])->name('partners.update');

    // Programs Management
    Route::get('/programs', [App\Http\Controllers\Admin\AdminProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/create', [App\Http\Controllers\Admin\AdminProgramController::class, 'create'])->name('programs.create');
    Route::post('/programs', [App\Http\Controllers\Admin\AdminProgramController::class, 'store'])->name('programs.store');
    Route::get('/programs/{program}', [App\Http\Controllers\Admin\AdminProgramController::class, 'show'])->name('programs.show');
    Route::get('/programs/{program}/edit', [App\Http\Controllers\Admin\AdminProgramController::class, 'edit'])->name('programs.edit');
    Route::put('/programs/{program}', [App\Http\Controllers\Admin\AdminProgramController::class, 'update'])->name('programs.update');
    Route::delete('/programs/{program}', [App\Http\Controllers\Admin\AdminProgramController::class, 'destroy'])->name('programs.destroy');

    // Game Statistics
    Route::get('/game-statistics', [App\Http\Controllers\Admin\AdminGameStatisticController::class, 'index'])->name('game-statistics.index');
    Route::get('/game-statistics/create', [App\Http\Controllers\Admin\AdminGameStatisticController::class, 'create'])->name('game-statistics.create');
    Route::post('/game-statistics', [App\Http\Controllers\Admin\AdminGameStatisticController::class, 'store'])->name('game-statistics.store');
    Route::get('/game-statistics/{statistic}', [App\Http\Controllers\Admin\AdminGameStatisticController::class, 'show'])->name('game-statistics.show');
    Route::get('/game-statistics/{statistic}/edit', [App\Http\Controllers\Admin\AdminGameStatisticController::class, 'edit'])->name('game-statistics.edit');
    Route::put('/game-statistics/{statistic}', [App\Http\Controllers\Admin\AdminGameStatisticController::class, 'update'])->name('game-statistics.update');
    Route::delete('/game-statistics/{statistic}', [App\Http\Controllers\Admin\AdminGameStatisticController::class, 'destroy'])->name('game-statistics.destroy');

    // Matches Management
    Route::get('/matches', [App\Http\Controllers\Admin\AdminMatchController::class, 'index'])->name('matches.index');
    Route::get('/matches/create', [App\Http\Controllers\Admin\AdminMatchController::class, 'create'])->name('matches.create');
    Route::post('/matches', [App\Http\Controllers\Admin\AdminMatchController::class, 'store'])->name('matches.store');
    Route::get('/matches/{match}', [App\Http\Controllers\Admin\AdminMatchController::class, 'show'])->name('matches.show');
    Route::get('/matches/{match}/edit', [App\Http\Controllers\Admin\AdminMatchController::class, 'edit'])->name('matches.edit');
    Route::put('/matches/{match}', [App\Http\Controllers\Admin\AdminMatchController::class, 'update'])->name('matches.update');
    Route::delete('/matches/{match}', [App\Http\Controllers\Admin\AdminMatchController::class, 'destroy'])->name('matches.destroy');

    // Standings Management
    Route::get('/standings', [App\Http\Controllers\Admin\AdminStandingsController::class, 'index'])->name('standings.index');
    Route::get('/standings/create', [App\Http\Controllers\Admin\AdminStandingsController::class, 'create'])->name('standings.create');
    Route::post('/standings', [App\Http\Controllers\Admin\AdminStandingsController::class, 'store'])->name('standings.store');
    Route::get('/standings/{standing}', [App\Http\Controllers\Admin\AdminStandingsController::class, 'show'])->name('standings.show');
    Route::get('/standings/{standing}/edit', [App\Http\Controllers\Admin\AdminStandingsController::class, 'edit'])->name('standings.edit');
    Route::put('/standings/{standing}', [App\Http\Controllers\Admin\AdminStandingsController::class, 'update'])->name('standings.update');
    Route::delete('/standings/{standing}', [App\Http\Controllers\Admin\AdminStandingsController::class, 'destroy'])->name('standings.destroy');

    // Documents Management
    Route::get('/documents', [App\Http\Controllers\Admin\AdminDocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [App\Http\Controllers\Admin\AdminDocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [App\Http\Controllers\Admin\AdminDocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [App\Http\Controllers\Admin\AdminDocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/edit', [App\Http\Controllers\Admin\AdminDocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [App\Http\Controllers\Admin\AdminDocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [App\Http\Controllers\Admin\AdminDocumentController::class, 'destroy'])->name('documents.destroy');

    // News Management
    Route::get('/news', [App\Http\Controllers\Admin\AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [App\Http\Controllers\Admin\AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/news', [App\Http\Controllers\Admin\AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/news/{news}', [App\Http\Controllers\Admin\AdminNewsController::class, 'show'])->name('news.show');
    Route::get('/news/{news}/edit', [App\Http\Controllers\Admin\AdminNewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}', [App\Http\Controllers\Admin\AdminNewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}', [App\Http\Controllers\Admin\AdminNewsController::class, 'destroy'])->name('news.destroy');

    // Gallery Management
    Route::get('/gallery', [App\Http\Controllers\Admin\AdminGalleryController::class, 'index'])->name('gallery.index');
    Route::get('/gallery/create', [App\Http\Controllers\Admin\AdminGalleryController::class, 'create'])->name('gallery.create');
    Route::post('/gallery', [App\Http\Controllers\Admin\AdminGalleryController::class, 'store'])->name('gallery.store');
    Route::get('/gallery/{gallery}', [App\Http\Controllers\Admin\AdminGalleryController::class, 'show'])->name('gallery.show');
    Route::get('/gallery/{gallery}/edit', [App\Http\Controllers\Admin\AdminGalleryController::class, 'edit'])->name('gallery.edit');
    Route::put('/gallery/{gallery}', [App\Http\Controllers\Admin\AdminGalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery/{gallery}', [App\Http\Controllers\Admin\AdminGalleryController::class, 'destroy'])->name('gallery.destroy');

    // Products Management
    Route::get('/products', [App\Http\Controllers\Admin\AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [App\Http\Controllers\Admin\AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [App\Http\Controllers\Admin\AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [App\Http\Controllers\Admin\AdminProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [App\Http\Controllers\Admin\AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [App\Http\Controllers\Admin\AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [App\Http\Controllers\Admin\AdminProductController::class, 'destroy'])->name('products.destroy');

    // Orders Management
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('orders.update');

    // Payments Management
    Route::get('/payments', [App\Http\Controllers\Admin\AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [App\Http\Controllers\Admin\AdminPaymentController::class, 'show'])->name('payments.show');
    Route::put('/payments/{payment}', [App\Http\Controllers\Admin\AdminPaymentController::class, 'update'])->name('payments.update');
    Route::get('/payments/financial-report', [App\Http\Controllers\Admin\AdminPaymentController::class, 'financialReport'])->name('payments.financial-report');

    // Jobs Management
    Route::get('/jobs', [App\Http\Controllers\Admin\AdminJobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/create', [App\Http\Controllers\Admin\AdminJobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [App\Http\Controllers\Admin\AdminJobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}', [App\Http\Controllers\Admin\AdminJobController::class, 'show'])->name('jobs.show');
    Route::get('/jobs/{job}/edit', [App\Http\Controllers\Admin\AdminJobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [App\Http\Controllers\Admin\AdminJobController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{job}', [App\Http\Controllers\Admin\AdminJobController::class, 'destroy'])->name('jobs.destroy');

    // Coupons Management
    Route::get('/coupons', [App\Http\Controllers\Admin\AdminCouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/create', [App\Http\Controllers\Admin\AdminCouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons', [App\Http\Controllers\Admin\AdminCouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/{coupon}', [App\Http\Controllers\Admin\AdminCouponController::class, 'show'])->name('coupons.show');
    Route::get('/coupons/{coupon}/edit', [App\Http\Controllers\Admin\AdminCouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{coupon}', [App\Http\Controllers\Admin\AdminCouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{coupon}', [App\Http\Controllers\Admin\AdminCouponController::class, 'destroy'])->name('coupons.destroy');

    // Website Players Management
    Route::get('/website-players', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'index'])->name('website-players.index');
    Route::get('/website-players/create', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'create'])->name('website-players.create');
    Route::post('/website-players', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'store'])->name('website-players.store');
    Route::get('/website-players/{websitePlayer}', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'show'])->name('website-players.show');
    Route::get('/website-players/{websitePlayer}/edit', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'edit'])->name('website-players.edit');
    Route::put('/website-players/{websitePlayer}', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'update'])->name('website-players.update');
    Route::delete('/website-players/{websitePlayer}', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'destroy'])->name('website-players.destroy');

    // Image Upload
    Route::get('/image-upload', [App\Http\Controllers\Admin\ImageUploadController::class, 'index'])->name('image-upload');
    Route::post('/image-upload', [App\Http\Controllers\Admin\ImageUploadController::class, 'store'])->name('image-upload.store');

    // Performance Overview
    Route::get('/performance/overview', [App\Http\Controllers\Admin\DashboardController::class, 'performanceOverview'])->name('performance.overview');

    // Compliance Report
    Route::get('/compliance/report', [App\Http\Controllers\Admin\DashboardController::class, 'complianceReport'])->name('compliance.report');
});

// API Routes for AJAX functionality
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/news', [NewsController::class, 'apiIndex'])->name('news.index');
});

// Player Portal Routes
Route::middleware(['auth', 'player'])->prefix('player-portal')->name('player.portal.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\PlayerPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\PlayerPortalController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\PlayerPortalController::class, 'updateProfile'])->name('profile.update');
    Route::get('/programs', [App\Http\Controllers\PlayerPortalController::class, 'programs'])->name('programs');
    Route::get('/training', [App\Http\Controllers\PlayerPortalController::class, 'training'])->name('training');
    Route::get('/schedule', [App\Http\Controllers\PlayerPortalController::class, 'schedule'])->name('schedule');
    Route::get('/resources', [App\Http\Controllers\PlayerPortalController::class, 'resources'])->name('resources');
    Route::get('/orders', [App\Http\Controllers\PlayerPortalController::class, 'orders'])->name('orders');
    Route::get('/communication', [App\Http\Controllers\PlayerPortalController::class, 'communication'])->name('communication');
    Route::get('/support', [App\Http\Controllers\PlayerPortalController::class, 'support'])->name('support');
});

// Partner Routes (if needed)
Route::middleware(['auth', 'partner'])->prefix('partner')->name('partner.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\PartnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/players', [App\Http\Controllers\PartnerController::class, 'players'])->name('players');
    Route::get('/analytics', [App\Http\Controllers\PartnerController::class, 'analytics'])->name('analytics');
});
