<?php

namespace App\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Events\AfterSheet;
use Str;

class TaskExport extends DefaultValueBinder implements FromCollection, WithCustomValueBinder, WithHeadings, WithTitle, ShouldQueue, WithEvents
{
    use Exportable;
    protected $tasks;
    public function __construct($tasks)
    {
        $this->tasks = $tasks;
    }
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Payroll';
    }

    public function collection()
    {
        $tasks = $this->tasks;
        foreach ($tasks as $task) {
            $data[] = [
                'title' => $task->title,
                'assignee' => $task->assignee,
                'due_date' => $task->due_date,
                'time_tracked' => $task->time_tracked,
                'status' => $task->status,
                'priority' => $task->priority
            ];
        }
        return collect($data);
    }
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                foreach (range('A', 'F') as $col) {
                    $event->sheet->getDelegate()->getStyle("{$col}")
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_TEXT);
                }
            },
            AfterSheet::class => function (AfterSheet $event) {
                $taskCount = count($this->tasks);
                $totalRow1 = $taskCount + 2;
                $totalRow2 = $taskCount + 3;

                $event->sheet->setCellValue("A{$totalRow1}", 'Total Task');
                $event->sheet->setCellValueExplicit("B{$totalRow1}", $taskCount, DataType::TYPE_STRING);
                $event->sheet->getStyle("A{$totalRow1}:B{$totalRow1}")->getFont()->setBold(true);

                $totalTime = $this->tasks->sum('time_tracked');
                $event->sheet->setCellValue("A{$totalRow2}", 'Total Time Tracked');
                $event->sheet->setCellValueExplicit("B{$totalRow2}", $totalTime, DataType::TYPE_STRING);
                $event->sheet->getStyle("A{$totalRow2}:B{$totalRow2}")->getFont()->setBold(true);
            }
        ];
    }


    public function map($data): array
    {
        return [
            $data->title,
            $data->assignee,
            $data->due_date,
            $data->time_tracked,
            $data->status,
            $data->priority,
        ];
    }

    public function headings(): array
    {
        return [
            'Title',
            'Assignee',
            'Due Date',
            'Time Tracked',
            'Status',
            'Priority',
        ];
    }
}
