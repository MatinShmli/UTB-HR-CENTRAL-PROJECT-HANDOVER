<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('LandingPage');
});

Route::get('/LandingPage', function () {
    return view('Login');
});

// Dashboard and main application routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/staff', function () {
    return view('staff.index');
})->middleware('auth')->name('staff.index');

Route::get('/departments', function () {
    return view('departments.index');
})->middleware('auth')->name('departments.index');

Route::get('/reports', function () {
    return view('reports.index');
})->middleware('auth')->name('reports.index');

Route::get('/settings', function () {
    return view('settings.index');
})->middleware('auth')->name('settings.index');

Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->middleware('auth')->name('profile');
Route::post('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->middleware('auth')->name('profile.update');

Route::get('/recruitment', [App\Http\Controllers\JobPostingController::class, 'index'])->middleware(['auth', 'profile.complete'])->name('recruitment');
Route::get('/new-employment', function () {
    return view('new-employment');
})->middleware(['auth', 'profile.complete'])->name('new-employment');

// Job Application Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/my-applications', [App\Http\Controllers\JobApplicationController::class, 'myApplications'])->name('job-applications.my-applications');
    Route::get('/job-applications/create/{jobId}', [App\Http\Controllers\JobApplicationController::class, 'create'])->name('job-applications.create');
    Route::post('/job-applications', [App\Http\Controllers\JobApplicationController::class, 'store'])->name('job-applications.store');
    Route::get('/job-applications/{id}', [App\Http\Controllers\JobApplicationController::class, 'show'])->name('job-applications.show');
    Route::get('/job-applications/{id}/download-cv', [App\Http\Controllers\JobApplicationController::class, 'downloadCV'])->name('job-applications.download-cv');
    Route::get('/api/job-applications/{id}', [App\Http\Controllers\JobApplicationController::class, 'getApplication'])->name('job-applications.get');
});

// Visa & Pass Routes
Route::get('/visa-pass', [App\Http\Controllers\VisaPassController::class, 'index'])->middleware(['auth', 'profile.complete'])->name('visa-pass.index');
Route::get('/visa-pass/download/{filename}', [App\Http\Controllers\VisaPassController::class, 'downloadPdf'])->middleware(['auth', 'profile.complete'])->name('visa-pass.download');

// CPD Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/cpd', [App\Http\Controllers\CpdController::class, 'index'])->name('cpd.index');
    Route::get('/cpd/create', [App\Http\Controllers\CpdController::class, 'create'])->name('cpd.create');
    Route::post('/cpd', [App\Http\Controllers\CpdController::class, 'store'])->name('cpd.store');
    
    // CPD Recommendation Routes (must come before parameterized routes)
    Route::get('/cpd/recommendations', [App\Http\Controllers\CpdRecommendationController::class, 'index'])->name('cpd.recommendations.index');
    Route::get('/cpd/recommendations/create', function() {
        return redirect()->route('cpd.index')->with('warning', 'Please select a CPD application to create a recommendation for.');
    })->name('cpd.recommendations.create.fallback');
    Route::get('/cpd/recommendations/{recommendation}', [App\Http\Controllers\CpdRecommendationController::class, 'show'])->name('cpd.recommendations.show');
    Route::get('/cpd/recommendations/{recommendation}/edit', [App\Http\Controllers\CpdRecommendationController::class, 'edit'])->name('cpd.recommendations.edit');
    Route::put('/cpd/recommendations/{recommendation}', [App\Http\Controllers\CpdRecommendationController::class, 'update'])->name('cpd.recommendations.update');
    Route::delete('/cpd/recommendations/{recommendation}', [App\Http\Controllers\CpdRecommendationController::class, 'destroy'])->name('cpd.recommendations.destroy');
    
    // CPD Application Routes (parameterized routes come after specific routes)
    Route::get('/cpd/{application}', [App\Http\Controllers\CpdController::class, 'show'])->name('cpd.show');
    Route::get('/cpd/{application}/edit', [App\Http\Controllers\CpdController::class, 'edit'])->name('cpd.edit');
    Route::put('/cpd/{application}', [App\Http\Controllers\CpdController::class, 'update'])->name('cpd.update');
    Route::post('/cpd/{application}/submit', [App\Http\Controllers\CpdController::class, 'submit'])->name('cpd.submit');
    Route::post('/cpd/{application}/rework', [App\Http\Controllers\CpdController::class, 'rework'])->name('cpd.rework');
    Route::post('/cpd/{application}/resubmit', [App\Http\Controllers\CpdController::class, 'resubmit'])->name('cpd.resubmit');
    Route::delete('/cpd/{application}', [App\Http\Controllers\CpdController::class, 'destroy'])->name('cpd.destroy');
    Route::get('/cpd/{application}/download-pdf', [App\Http\Controllers\CpdController::class, 'downloadPdf'])->name('cpd.download-pdf');
    Route::get('/cpd/{application}/recommendation/create', [App\Http\Controllers\CpdRecommendationController::class, 'create'])->name('cpd.recommendations.create');
    Route::post('/cpd/{application}/recommendation', [App\Http\Controllers\CpdRecommendationController::class, 'store'])->name('cpd.recommendations.store');
});

// Memo Request Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/memo', [App\Http\Controllers\MemoRequestController::class, 'index'])->name('memo.index');
    Route::get('/memo/create', [App\Http\Controllers\MemoRequestController::class, 'create'])->name('memo.create');
    Route::post('/memo', [App\Http\Controllers\MemoRequestController::class, 'store'])->name('memo.store');
    Route::get('/memo/{memo}', [App\Http\Controllers\MemoRequestController::class, 'show'])->name('memo.show');
    Route::get('/memo/{memo}/edit', [App\Http\Controllers\MemoRequestController::class, 'edit'])->name('memo.edit');
    Route::put('/memo/{memo}', [App\Http\Controllers\MemoRequestController::class, 'update'])->name('memo.update');
    Route::delete('/memo/{memo}', [App\Http\Controllers\MemoRequestController::class, 'destroy'])->name('memo.destroy');
    Route::get('/memo/{memo}/download-form/{fileIndex}', [App\Http\Controllers\MemoRequestController::class, 'downloadForm'])->name('memo.download-form');
    Route::get('/memo/{memo}/download-memo', [App\Http\Controllers\MemoRequestController::class, 'downloadMemo'])->name('memo.download-memo');
});

Route::get('/logout', function () {
    return redirect('/');
})->name('logout');

// Authentication routes
Route::get('/signup', function () {
    return view('auth.signup');
})->name('signup.page');

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/signup', [App\Http\Controllers\AuthController::class, 'signup']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout.post');

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/pending-approvals', [App\Http\Controllers\AdminController::class, 'pendingApprovals'])->name('admin.pending-approvals');
    Route::get('/admin/manage-users', [App\Http\Controllers\AdminController::class, 'manageUsers'])->name('admin.manage-users');
    Route::post('/admin/approve-user/{id}', [App\Http\Controllers\AdminController::class, 'approveUser'])->name('admin.approve-user');
    Route::post('/admin/reject-user/{id}', [App\Http\Controllers\AdminController::class, 'rejectUser'])->name('admin.reject-user');
    Route::post('/admin/update-user-role/{id}', [App\Http\Controllers\AdminController::class, 'updateUserRole'])->name('admin.update-user-role');
    Route::post('/admin/delete-user/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.delete-user');
    Route::post('/admin/update-user-details/{id}', [App\Http\Controllers\AdminController::class, 'updateUserDetails'])->name('admin.update-user-details');
    
    // Job Posting routes
    Route::post('/job-postings', [App\Http\Controllers\JobPostingController::class, 'store'])->name('job-postings.store');
    Route::put('/job-postings/{id}', [App\Http\Controllers\JobPostingController::class, 'update'])->name('job-postings.update');
    Route::post('/job-postings/{id}/close', [App\Http\Controllers\JobPostingController::class, 'close'])->name('job-postings.close');
    Route::post('/job-postings/{id}/reopen', [App\Http\Controllers\JobPostingController::class, 'reopen'])->name('job-postings.reopen');
    Route::delete('/job-postings/{id}', [App\Http\Controllers\JobPostingController::class, 'destroy'])->name('job-postings.destroy');
    
    // Admin CPD Routes
    Route::get('/admin/cpd', [App\Http\Controllers\AdminController::class, 'cpdApplications'])->name('admin.cpd.index');
    Route::get('/admin/cpd/{application}', [App\Http\Controllers\AdminController::class, 'cpdApplicationShow'])->name('admin.cpd.show');
    Route::post('/admin/cpd/{application}/approve', [App\Http\Controllers\AdminController::class, 'cpdApplicationApprove'])->name('admin.cpd.approve');
    Route::post('/admin/cpd/{application}/reject', [App\Http\Controllers\AdminController::class, 'cpdApplicationReject'])->name('admin.cpd.reject');
    Route::post('/admin/cpd/{application}/rework', [App\Http\Controllers\AdminController::class, 'cpdApplicationRework'])->name('admin.cpd.rework');
    Route::delete('/admin/cpd/{application}', [App\Http\Controllers\AdminController::class, 'cpdApplicationDelete'])->name('admin.cpd.delete');
    Route::get('/admin/cpd/{application}/download-pdf', [App\Http\Controllers\AdminController::class, 'cpdApplicationDownloadPdf'])->name('admin.cpd.download-pdf');
    
    // Admin Memo Routes
    Route::get('/admin/memo', [App\Http\Controllers\AdminController::class, 'memoIndex'])->name('admin.memo.index');
    Route::get('/admin/memo/{memo}', [App\Http\Controllers\AdminController::class, 'memoShow'])->name('admin.memo.show');
    Route::post('/admin/memo/{memo}/approve', [App\Http\Controllers\AdminController::class, 'memoApprove'])->name('admin.memo.approve');
    Route::post('/admin/memo/{memo}/reject', [App\Http\Controllers\AdminController::class, 'memoReject'])->name('admin.memo.reject');
    Route::delete('/admin/memo/{memo}', [App\Http\Controllers\AdminController::class, 'memoDelete'])->name('admin.memo.delete');
    Route::get('/admin/memo/{memo}/download-form/{fileIndex}', [App\Http\Controllers\AdminController::class, 'memoDownloadForm'])->name('admin.memo.download-form');
    Route::get('/admin/memo/{memo}/download-memo', [App\Http\Controllers\AdminController::class, 'memoDownloadMemo'])->name('admin.memo.download-memo');
    
    // Admin Job Application Routes
    Route::get('/admin/job-applications', [App\Http\Controllers\JobApplicationController::class, 'index'])->name('admin.job-applications.index');
    Route::get('/admin/job-applications/job/{jobId}', [App\Http\Controllers\JobApplicationController::class, 'jobApplications'])->name('admin.job-applications.job');
    Route::get('/api/job-applications/job/{jobId}', [App\Http\Controllers\JobApplicationController::class, 'getJobApplications'])->name('admin.job-applications.get-job-applications');
    Route::post('/admin/job-applications/{id}/under-review', [App\Http\Controllers\JobApplicationController::class, 'markUnderReview'])->name('admin.job-applications.under-review');
    Route::post('/admin/job-applications/{id}/accept', [App\Http\Controllers\JobApplicationController::class, 'accept'])->name('admin.job-applications.accept');
    Route::post('/admin/job-applications/{id}/reject', [App\Http\Controllers\JobApplicationController::class, 'reject'])->name('admin.job-applications.reject');
    Route::post('/admin/job-applications/{id}/update', [App\Http\Controllers\JobApplicationController::class, 'update'])->name('admin.job-applications.update');
});

// Head Of Section routes
Route::middleware(['auth', 'headofsection'])->group(function () {
    Route::get('/headofsection/dashboard', [App\Http\Controllers\HeadOfSectionController::class, 'dashboard'])->name('headofsection.dashboard');
    
    // Head Of Section CPD Routes
    Route::get('/headofsection/cpd', [App\Http\Controllers\HeadOfSectionController::class, 'cpdApplications'])->name('headofsection.cpd.index');
    Route::get('/headofsection/cpd/{application}', [App\Http\Controllers\HeadOfSectionController::class, 'cpdApplicationShow'])->name('headofsection.cpd.show');
    Route::post('/headofsection/cpd/{application}/approve', [App\Http\Controllers\HeadOfSectionController::class, 'cpdApplicationApprove'])->name('headofsection.cpd.approve');
    Route::post('/headofsection/cpd/{application}/reject', [App\Http\Controllers\HeadOfSectionController::class, 'cpdApplicationReject'])->name('headofsection.cpd.reject');
    Route::post('/headofsection/cpd/{application}/rework', [App\Http\Controllers\HeadOfSectionController::class, 'cpdApplicationRework'])->name('headofsection.cpd.rework');
    Route::get('/headofsection/cpd/{application}/download-pdf', [App\Http\Controllers\HeadOfSectionController::class, 'cpdApplicationDownloadPdf'])->name('headofsection.cpd.download-pdf');
    
    // Head Of Section Memo Routes
    Route::get('/headofsection/memo', [App\Http\Controllers\HeadOfSectionController::class, 'memoIndex'])->name('headofsection.memo.index');
    Route::get('/headofsection/memo/{memo}', [App\Http\Controllers\HeadOfSectionController::class, 'memoShow'])->name('headofsection.memo.show');
    Route::post('/headofsection/memo/{memo}/approve', [App\Http\Controllers\HeadOfSectionController::class, 'memoApprove'])->name('headofsection.memo.approve');
    Route::post('/headofsection/memo/{memo}/reject', [App\Http\Controllers\HeadOfSectionController::class, 'memoReject'])->name('headofsection.memo.reject');
    Route::get('/headofsection/memo/{memo}/download-form/{fileIndex}', [App\Http\Controllers\HeadOfSectionController::class, 'memoDownloadForm'])->name('headofsection.memo.download-form');
    Route::get('/headofsection/memo/{memo}/download-memo', [App\Http\Controllers\HeadOfSectionController::class, 'memoDownloadMemo'])->name('headofsection.memo.download-memo');
});


