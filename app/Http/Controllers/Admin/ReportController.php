<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ResponsesExport;
use App\Models\Question;
use Backpack\CRUD\app\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends AdminController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // Get filter parameters
        $filters = [
            'question_type' => $request->input('question_type'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'answer' => $request->input('answer')
        ];

        // Get filter options for select dropdowns
        $questionTypes = Question::distinct()->pluck('type', 'type');
        $questions = Question::pluck('question_text', 'id');

        return view('vendor.backpack.reports', [
            'filters' => $filters,
            'questionTypes' => $questionTypes,
            'questions' => $questions,
            'exportUrl' => route('export.responses', $filters)
        ]);
    }
}
