<?php

namespace App\Exports;

use App\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Liberation
{
    protected $schedule;
    protected $users;

    protected $spreadsheet;

    public function __construct(Schedule $schedule, Collection $users)
    {
        $this->spreadsheet = new Spreadsheet();
        $this->schedule = $schedule;
        $this->users = $users;

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
        $title = 'Libérations';
        $this->spreadsheet->getActiveSheet()->setTitle($title);
        $this->addDefaultStyle();
        $this->addHeading();
        $this->addUsers();
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
        $sheet->setCellValueByColumnAndRow(1,1, 'CONTRAINTES SELON DISPONIBILITÉ DU ' . $startDate . ' AU ' . $endDate);
        $sheet->mergeCellsByColumnAndRow(1,1,10,1);
        $styleHeader = $sheet->getStyleByColumnAndRow(1,1,10,1);
        $styleHeader->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $styleHeader->getBorders()->getBottom()->setBorderStyle(BORDER::BORDER_DOUBLE);

        $subHeaders = ['Nom', 'Prénom', 'Type', '# Fois', 'Journée', 'Disposition', 'Début', 'Fin', 'Importance', 'Raison'];
        foreach($subHeaders as $index => $subHeader) {
            $cell = $sheet->getCellByColumnAndRow($index + 1, 3);
            $cell->setValue($subHeader);
        }

        $styleSubHeader = $sheet->getStyleByColumnAndRow(1,3,10,3);
        $styleSubHeader->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $styleSubHeader->getFont()->setBold(true);

        $sheet->getColumnDimensionByColumn(1)->setWidth(20);
        $sheet->getColumnDimensionByColumn(2)->setWidth(20);
        $sheet->getColumnDimensionByColumn(3)->setWidth(10);
        $sheet->getColumnDimensionByColumn(4)->setWidth(10);
        $sheet->getColumnDimensionByColumn(5)->setWidth(10);
        $sheet->getColumnDimensionByColumn(6)->setWidth(10);
        $sheet->getColumnDimensionByColumn(7)->setWidth(15);
        $sheet->getColumnDimensionByColumn(8)->setWidth(15);
        $sheet->getColumnDimensionByColumn(9)->setWidth(10);
        $sheet->getColumnDimensionByColumn(10)->setWidth(70);

    }

    private function addUsers()
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $row = 4;

        foreach ($this->users as $user) {
            foreach($user->constraints->where('constraintType.is_group_constraint', 1) as $constraint) {
                $sheet->getCellByColumnAndRow(1, $row)->setValue($user->lastname);
                $sheet->getCellByColumnAndRow(2, $row)->setValue($user->firstname);
                $sheet->getCellByColumnAndRow(3, $row)->setValue($constraint->constraintType->code);
                $sheet->getCellByColumnAndRow(4, $row)->setValue($constraint->number_of_occurrences);

                if($constraint->day !== NULL) {
                    $weekDays = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                    $sheet->getCellByColumnAndRow(5, $row)->setValue($weekDays[$constraint->day]);
                }

                if($constraint->disposition !== NULL) {
                    $dispositions = ['Peu importe', 'Consécutifs', 'Séparés'];
                    $sheet->getCellByColumnAndRow(6, $row)->setValue($dispositions[$constraint->disposition]);
                }

                $sheet->getCellByColumnAndRow(7, $row)->setValue($constraint->start_datetime);
                $sheet->getCellByColumnAndRow(8, $row)->setValue($constraint->end_datetime);

                $weight = ['Forte', 'faible'];
                $sheet->getCellByColumnAndRow(9, $row)->setValue($weight[$constraint->weight]);

                $sheet->getCellByColumnAndRow(10, $row)
                    ->setValue($constraint->comment)
                    ->getStyle()->getAlignment()->setWrapText(true);

                // Coloration alternée des rangées
                if($row % 2 == 0) {
                    $sheet->getStyleByColumnAndRow(1, $row, 10, $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('BCDEFA');
                }

                $row++;
            }
        }
    }


}