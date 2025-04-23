<?php

use Illuminate\Support\Facades\Route;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\Response;
use Illuminate\Support\Facades\Validator;
use App\Exports\ResponsesExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/welcome', function () {
  $questions = Question::all();
  return view('welcome', compact('questions'));
});

Route::post('/submit-survey', function (Request $request) {
    // Get all question IDs that require an answer
    $questionIds = Question::pluck('id')->toArray();
  
    // Custom validation rule to ensure all questions are answered
    $validator = Validator::make($request->all(), [
      'answers' => [
        'required',
        'array',
        function ($attribute, $value, $fail) use ($questionIds) {
          $answeredIds = array_keys($value);
          $missingIds = array_diff($questionIds, $answeredIds);
          if (!empty($missingIds)) {
            $fail('Please answer all questions.');
          }
        },
      ],
      'answers.*' => 'required',
    ]);
  
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }
  
    // Save responses
    foreach ($request->answers as $questionId => $answer) {
      Response::create([
        'question_id' => $questionId,
        'answer' => $answer
      ]);
    }
  
    return redirect()->route('thank-you');
  });

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

Route::get('/', function () {
    $questions = Question::all(); // Fetch all questions
    return view('welcome', ['questions' => $questions]); // Pass to view
});

// Thank-you page route
Route::get('/thank-you', function () {
    return view('thank-you');
})->name('thank-you');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
