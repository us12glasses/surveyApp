<?php

namespace App\Exports;

use App\Models\Response;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ResponsesExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Response::with('question');

        // Apply filters
        if (!empty($this->filters['question_type'])) {
            $query->whereHas('question', function($q) {
                $q->where('type', $this->filters['question_type']);
            });
        }

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('created_at', '>=', Carbon::parse($this->filters['start_date']));
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('created_at', '<=', Carbon::parse($this->filters['end_date']));
        }

        if (!empty($this->filters['answer'])) {
            $query->where('answer', 'like', '%'.$this->filters['answer'].'%');
        }

        return $query->get()->map(function ($response) {
            return [
                'Question' => $response->question->question_text ?? 'N/A',
                'Answer' => $response->answer,
                'Date' => $response->created_at->format('Y-m-d H:i:s')
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Question Text', 
            'User Answer', 
            'Submission Date'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY, // Format date column
        ];
    }
}
