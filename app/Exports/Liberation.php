<?php

namespace App\Exports;

use App\Models\Schedule;
use Illuminate\Support\Collection;
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
        $sheet->setCellValue([1, 1], 'CONTRAINTES SELON DISPONIBILITÉ DU ' . $startDate . ' AU ' . $endDate);
        $sheet->mergeCells([1, 1, 10, 1]);
        $styleHeader = $sheet->getStyle([1, 1, 10, 1]);
        $styleHeader->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $styleHeader->getBorders()->getBottom()->setBorderStyle(BORDER::BORDER_DOUBLE);

        $subHeaders = ['Nom', 'Prénom', 'Type', '# Fois', 'Journée', 'Disposition', 'Début', 'Fin', 'Importance', 'Raison'];
        foreach($subHeaders as $index => $subHeader) {
            $cell = $sheet->getCell([$index + 1, 3]);
            $cell->setValue($subHeader);
        }

        $styleSubHeader = $sheet->getStyle([1, 3, 10, 3]);
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
                $sheet->getCell([1, $row])->setValue($user->lastname);
                $sheet->getCell([2, $row])->setValue($user->firstname);
                $sheet->getCell([3, $row])->setValue($constraint->constraintType->code);
                $sheet->getCell([4, $row])->setValue($constraint->number_of_occurrences);

                if($constraint->day !== NULL) {
                    $weekDays = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                    $sheet->getCell([5, $row])->setValue($weekDays[$constraint->day]);
                }

                if($constraint->disposition !== NULL) {
                    $dispositions = ['Peu importe', 'Consécutifs', 'Séparés'];
                    $sheet->getCell([6, $row])->setValue($dispositions[$constraint->disposition]);
                }

                $sheet->getCell([7, $row])->setValue($constraint->start_datetime);
                $sheet->getCell([8, $row])->setValue($constraint->end_datetime);

                $weight = ['Forte', 'faible'];
                $sheet->getCell([9, $row])->setValue($weight[$constraint->weight]);

                $sheet->getCell([10, $row])
                    ->setValue($constraint->comment)
                    ->getStyle()->getAlignment()->setWrapText(true);

                // Coloration alternée des rangées
                if($row % 2 == 0) {
                    $sheet->getStyle([1, $row, 10, $row])->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('BCDEFA');
                }

                $row++;
            }
        }
    }


}
