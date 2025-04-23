<?php

namespace App\Exports;

use App\Models\Response;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ResponsesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Response::with('question') // Eager load the relationship
        ->get()
        ->map(function ($response) {
            return [
                'Question' => $response->question->question_text ?? 'N/A',
                'Answer' => $response->answer,
                'Date' => $response->created_at->format('Y-m-d H:i:s')
            ];
        });
    }

    public function headings(): array
    {
        return ['Question', 'Answer', 'Date'];
    }
}
