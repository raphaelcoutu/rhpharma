<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Calendar
{
    protected $startDate;
    protected $endDate;
    protected $users;

    protected $spreadsheet;

    public function __construct(Carbon $startDate, Carbon $endDate, Collection $users)
    {
        $this->spreadsheet = new Spreadsheet();
        $this->startDate = $startDate;
        $this->endDate = $endDate->setTime(23,59,59);
        $this->users = $users;

        $this->create();
    }

    private function create()
    {
        $title = 'Horaire';
        $this->spreadsheet->getActiveSheet()->setTitle($title);
        $this->addDefaultStyle();
        $this->addHeading();
        $this->addUsers();
    }

    private function addDefaultStyle()
    {
        $this->spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $this->spreadsheet->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
        $this->spreadsheet->getActiveSheet()->freezePaneByColumnAndRow(3,3);
    }

    private function addHeading()
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', $this->startDate->formatLocalized("%d %B") . ' au ' . $this->endDate->formatLocalized('%d %B'));
        $sheet->setCellValueByColumnAndRow(1,2, 'Site');
        $sheet->setCellValueByColumnAndRow(2,2, 'NomPrénom');
        $sheet->getStyle('A2:B2')->getBorders()->getBottom()->setBorderStyle(BORDER::BORDER_DOUBLE);

        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(20);

        $duration = $this->endDate->diffInDays($this->startDate) + 1;

        for($i = 0; $i < $duration; $i++)
        {
            $sheet->getColumnDimensionByColumn($i + 3)->setWidth(6);
            $cell = $sheet->getCellByColumnAndRow($i + 3, 2);
            $cell->setValue($this->startDate->copy()->addDays($i)->format('j'));
            $cellStyle = $cell->getStyle();
            $cellStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $cellStyle->getFill()->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('DAE1E7');
            $cellStyle->getBorders()->getBottom()->setBorderStyle(BORDER::BORDER_DOUBLE);

            // Coloration pour la fin de semaine
            if($i % 7 === 0 || $i % 7 === 6) {
                $column = $cell->getColumn();
                $sheet->getStyle($column . '2:' . $column . ($this->users->count()+2))->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('BCDEFA');
            }
        }
    }

    private function addUsers()
    {
        $rowStart = 3;
        $colStart = 3;
        $sheet = $this->spreadsheet->getActiveSheet();

        foreach($this->users as $index => $user) {
            $cell = $sheet->getCellByColumnAndRow(2, $index + $rowStart);
            $cell->setValue(mb_strtoupper($user->lastname) . ', ' . mb_strtoupper($user->firstname));
            $cell->getStyle()->getFont()->setSize(7);

            foreach ($user->assignedShifts as $assignedShift) {
                if($assignedShift->date->gte($this->startDate) && $assignedShift->date->lte($this->endDate)) {
                    $col = $assignedShift->date->diffInDays($this->startDate) + $colStart;

                    $cell = $sheet->getCellByColumnAndRow($col, $index + $rowStart);
                    $value = $cell->getValue();
                    $text = $assignedShift->shift->code;

                    if($value != null) {
                        $cell->setValue($value.'-'.$text);
                    } else {
                        $cell->setValue($text);
                    }
                }
            }

            foreach($user->constraints as $constraint) {
                // Ne pas afficher les constraintes selon dispo sur le calendrier
                if($constraint->constraintType->is_group_constraint == 1) continue;

                if(detectsIntervalCollision($constraint->start_datetime, $constraint->end_datetime,
                    $this->startDate, $this->endDate)) {

                    // Maintenant qu'on sait que la contrainte est dans le fichier d'horaire en cours
                    // On peut faire une itération sur tous les jours de la contrainte

                    $duration = $this->endDate->diffInDays($this->startDate) + 1;
                    $constraintAdjStartDate = ($constraint->start_datetime->lt($this->startDate)) ?
                        $this->startDate : $constraint->start_datetime;

                    $constraintAdjEndDate = ($constraint->end_datetime->gt($this->endDate)) ?
                        $this->endDate : $constraint->end_datetime;

                    $constraintDuration = $constraintAdjEndDate->diffInDays($constraintAdjStartDate) + 1;

                    for ($i = 0; $i < $constraintDuration; $i++) {
                        $iterateDay = $constraintAdjStartDate->copy()->addDays($i);
                        $col = $iterateDay->diffInDays($this->startDate) + $colStart;

                        $cell = $sheet->getCellByColumnAndRow($col, $index + $rowStart);

                        $value = ($cell->getValue() !== NULL) ? [$cell->getValue()] : [];
                        $value[] = $constraint->constraintType->code;

                        // Si la contrainte contient un jour ET que celui-ci n'est pas celui de l'itération => on l'enlève
                        if ($constraint->day !== NULL && $iterateDay->dayOfWeek !== $constraint->day) {
                            array_pop($value);
                        }

                        if (!empty($value)) {
                            $cell->setValue(implode("-", $value));
                        }
                    }
                }
            }
        }
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
}