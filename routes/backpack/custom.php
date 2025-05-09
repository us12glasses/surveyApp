<?php

use Illuminate\Support\Facades\Route;
use App\Exports\ResponsesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Response;
use Barryvdh\DomPDF\Facade\Pdf;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('question', 'QuestionCrudController');
    Route::crud('response', 'ResponseCrudController');
    // Reports
    Route::get('reports', \App\Http\Controllers\Admin\ReportController::class)
    ->middleware(['web', 'auth:backpack'])
    ->name('reports');
    // Export
    Route::get('export-responses', function (Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'question_type' => 'nullable|in:text,multiple_choice,rating',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'answer' => 'nullable|string|max:255'
        ]);
        
        return Excel::download(
            new ResponsesExport($validated), 
            'survey-responses-'.now()->format('Ymd-His').'.xlsx'
        );
    })->name('export.responses');

    Route::get('export-responses-pdf', function (Illuminate\Http\Request $request) {
        $filters = $request->validate([
            // Same filters as Excel export
            'question_type' => 'nullable|in:text,multiple_choice,rating',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'answer' => 'nullable|string|max:255'
        ]);
    
        $query = Response::with('question')  // Sorting by date in ascending order
            ->orderBy('created_at', 'asc') // Most oldest first
            ->orderBy('id'); // Secondary sort
        
        // Reuse filtering logic from ResponsesExport
        if (!empty($filters['question_type'])) {
            $query->whereHas('question', fn($q) => $q->where('type', $filters['question_type']));
        }
    
        if (!empty($filters['start_date'])) {
            $query->where('created_at', '>=', \Carbon\Carbon::parse($filters['start_date'])->startOfDay());
        }
    
        if (!empty($filters['end_date'])) {
            $query->where('created_at', '<=', \Carbon\Carbon::parse($filters['end_date'])->endOfDay());
        }
    
        if (!empty($filters['answer'])) {
            $query->where('answer', 'like', '%' . $filters['answer'] . '%');
        }
    
        $data = $query->get()->map(function ($response) {
            return [
                'question' => $response->question->question_text ?? 'N/A',
                'answer' => $response->answer,
                'date' => $response->created_at->timezone('Asia/Jakarta')->format('d M Y H:i')
            ];
        });
    
        $pdf = Pdf::loadView('pdf.responses', ['responses' => $data]);
        return $pdf->download('survey-responses-'.now()->format('Ymd-His').'.pdf');
    })->name('export.responses.pdf');
}); // this should be the absolute last line of this file