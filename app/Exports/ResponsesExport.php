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
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }
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

        // Date filtering with time boundaries
        if (!empty($this->filters['start_date'])) {
            $startDate = Carbon::parse($this->filters['start_date'])->startOfDay();
            $query->where('created_at', '>=', $startDate);
        }

        if (!empty($this->filters['end_date'])) {
            $endDate = Carbon::parse($this->filters['end_date'])->endOfDay();
            $query->where('created_at', '<=', $endDate);
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
