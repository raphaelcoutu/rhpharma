<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

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
        $sheet = $this->spreadsheet;
        $sheet->getDefaultStyle()->getFont()->setName('Arial');
        $sheet->getActiveSheet()->freezePaneByColumnAndRow(3,3);
        $sheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $sheet->getActiveSheet()->getPageSetup()->setFitToPage(true);
        $sheet->getActiveSheet()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_STANDARD_2);
        $sheet->getActiveSheet()->getPageMargins()->setTop(0);
        $sheet->getActiveSheet()->getPageMargins()->setHeader(0);
        $sheet->getActiveSheet()->getPageMargins()->setBottom(0);
        $sheet->getActiveSheet()->getPageMargins()->setFooter(0);
        $sheet->getActiveSheet()->getPageMargins()->setLeft(0);
        $sheet->getActiveSheet()->getPageMargins()->setRight(0);
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
            $sheet->getColumnDimensionByColumn($i + 3)->setWidth(7);
            $cell = $sheet->getCellByColumnAndRow($i + 3, 2);
            $cell->setValue($this->startDate->copy()->addDays($i)->format('j'));
            $cellStyle = $cell->getStyle();
            $cellStyle->getFill()->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('DAE1E7');
            $cellStyle->getBorders()->getBottom()->setBorderStyle(BORDER::BORDER_DOUBLE);
            $cellStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Coloration pour la fin de semaine
            if($i % 7 === 0 || $i % 7 === 6) {
                $column = $cell->getColumn();
                $sheet->getStyle($column . '2:' . $column . ($this->users->count()+2))->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('bfbfbf');
            }
        }
    }

    private function addUsers()
    {
        $rowStart = 3;
        $colStart = 3;
        $sheet = $this->spreadsheet->getActiveSheet();

        // Mettre les bordures sur les cases
        $duration = $this->endDate->diffInDays($this->startDate);
        $lastColumn = $sheet->getCellByColumnAndRow($colStart + $duration, $rowStart)->getColumn();

        $rowStyle = $sheet->getStyle('B' . ($rowStart) . ':' . $lastColumn . ($this->users->count()+$rowStart-1));
        $rowStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        foreach($this->users as $index => $user) {
            // Set la hauteur de la rangée
            $sheet->getRowDimension($rowStart + $index)->setRowHeight(17);

            $cell = $sheet->getCellByColumnAndRow(2, $index + $rowStart);
            $cell->setValue(mb_strtoupper($user->lastname) . ', ' . mb_strtoupper($user->firstname));
            $cell->getStyle()->getFont()->setSize(7);
            $cell->getStyle()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            foreach ($user->assignedShifts as $assignedShift) {
                if($assignedShift->date->gte($this->startDate) && $assignedShift->date->lte($this->endDate)) {
                    $col = $assignedShift->date->diffInDays($this->startDate) + $colStart;

                    $cell = $sheet->getCellByColumnAndRow($col, $index + $rowStart);
                    $cell->getStyle()->getFont()->setSize(9);
                    $cell->getStyle()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $cell->getStyle()->getAlignment()->setShrinkToFit(true);
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

                        if ($constraint->day !== NULL) {
                            // Si la contrainte contient un jour spécisé, on l'inscrit seulement dans celui-ci
                            if($iterateDay->dayOfWeek === $constraint->day) {
                                $this->addConstraintValue($cell, $constraint);
                            }
                        } else {
                           $this->addConstraintValue($cell, $constraint);
                        }
                    }
                }
            }
        }
    }

    private function addConstraintValue($cell, $constraint)
    {
        $value = ($cell->getValue()) ?? "";
        $asteriskPos = strpos($value, "*");

        $value = ($asteriskPos === false) ? $value . "*" : $value;
        if ($value !== "*" && $value{-1} !== "*") {
            $value .= "-";
        }

        $value .= $constraint->constraintType->code;
        $cell->getStyle()
            ->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('b7dee8');

        $richText = new RichText();
        $splitValue = explode("*", $value);
        $beforeAsterisk = $splitValue[0];
        $afterAsterisk = $splitValue[1];
        if($beforeAsterisk != "") {
            $richText->createText($beforeAsterisk . '-');
        }
        $boldText = $richText->createTextRun($afterAsterisk);
        $boldText->getFont()->getColor()->setARGB('CC1F1A');
        $boldText->getFont()->setName('Arial');
        $boldText->getFont()->setSize(9);
        $cell->setValue($richText);
        $cell->getStyle()->getFont()->setSize(9);
        $cell->getStyle()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $cell->getStyle()->getAlignment()->setShrinkToFit(true);
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