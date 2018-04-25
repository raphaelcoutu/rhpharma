<?php

namespace App\Exports;

use App\Schedule;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Conflict
{
    protected $schedule;
    protected $users;

    protected $spreadsheet;

    public function __construct(Schedule $schedule)
    {
        $this->spreadsheet = new Spreadsheet();
        $this->schedule = $schedule;

        $this->create();
    }

    public function getSpreadsheet()
    {
        return $this->spreadsheet;
    }

    public function clear()
    {
        $this->spreadsheet->disconnectWorksheets();
        unset($this->spreadsheet);
    }

    private function create()
    {
        $title = 'Conflits';
        $this->spreadsheet->getActiveSheet()->setTitle($title);
        $this->addDefaultStyle();
        $this->addHeading();
        $this->addConflicts();
    }

    private function addDefaultStyle()
    {
        $this->spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $this->spreadsheet->getDefaultStyle()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $this->spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
    }

    private function addHeading()
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $startDate = $this->schedule->start_date->format("d-m-Y");
        $endDate = $this->schedule->end_date->format("d-m-Y");
        $sheet->setCellValueByColumnAndRow(1,1, 'CONFLITS ' . $startDate . ' AU ' . $endDate);
        $sheet->mergeCellsByColumnAndRow(1,1,4,1);
        $styleHeader = $sheet->getStyleByColumnAndRow(1,1,4,1);
        $styleHeader->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $styleHeader->getBorders()->getBottom()->setBorderStyle(BORDER::BORDER_DOUBLE);

        $subHeaders = ['Id', 'Départment', 'Date', 'Message'];
        foreach($subHeaders as $index => $subHeader) {
            $cell = $sheet->getCellByColumnAndRow($index + 1, 3);
            $cell->setValue($subHeader);
        }

        $styleSubHeader = $sheet->getStyleByColumnAndRow(1,3,10,3);
        $styleSubHeader->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $styleSubHeader->getFont()->setBold(true);

        $sheet->getColumnDimensionByColumn(1)->setWidth(10);
        $sheet->getColumnDimensionByColumn(2)->setWidth(30);
        $sheet->getColumnDimensionByColumn(3)->setWidth(20);
        $sheet->getColumnDimensionByColumn(4)->setWidth(50);

    }

    private function addConflicts()
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $row = 4;

        $conflicts = \App\Conflict::with('department')
            ->where('date', '>=', $this->schedule->start_date)
            ->where('date', '<=', $this->schedule->end_date)
            ->get();

        foreach($conflicts as $conflict) {
            $sheet->getCellByColumnAndRow(1, $row)->setValue($conflict->id);
            $sheet->getCellByColumnAndRow(2, $row)->setValue($conflict->department->name);
            $sheet->getCellByColumnAndRow(3, $row)->setValue($conflict->date);
            $sheet->getCellByColumnAndRow(4, $row)->setValue($conflict->message);

            $row++;
        }
    }
}