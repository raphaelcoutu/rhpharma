<?php

namespace App\Exports;

use App\Schedule;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ZipArchive;

class Excel
{
    protected $schedule;
    protected $users;

    protected $calendars = [];

    public function __construct(Schedule $schedule, Collection $users)
    {
        $this->schedule = $schedule;
        $this->users = $users;

        $this->split($schedule);
    }

    private function split($schedule)
    {
        $duration = $schedule->end_date->diffInWeeks($schedule->start_date) + 1;
        $nbCalendars = 0;
        $divideBy = 0;

        if ($duration % 4 === 0) {
            $nbCalendars = $duration / 4;
            $divideBy = 4;
        } else if ($duration % 3 === 0) {
            $nbCalendars = $duration / 3;
            $divideBy = 3;
        } else {
            return new \Exception('Nombre de semaines à l\'horaire non divisible par 3 ou 4.');
        }

        for ($i = 0; $i < $nbCalendars; $i++) {
            $start = $this->schedule->start_date->addDays($i * $divideBy * 7);
            $end = $this->schedule->start_date->addDays(($i+1) * $divideBy * 7 - 1);

            $calendar = new Calendar($start, $end, $this->users);
            $calendar->create();

            $this->calendars[] = $calendar;
        }
    }

    public function download(string $fileName = 'Horaire')
    {
        if (count($this->calendars) > 1) {
            return $this->zip($fileName);
        } else {
            $timestamp = date('Ymd-His');
            $writer = new Xlsx($this->calendars[0]->getSpreadSheet());
            $tmpFile = sys_get_temp_dir() . 'laravel-calendar_' . $timestamp . '.xlsx';

            $writer->save($tmpFile);

            $this->calendars[0]->clear();

            return response()->download($tmpFile, $fileName . '_' . $timestamp . '.xlsx')
                ->deleteFileAfterSend(true);
        }
    }

    public function zip(string $fileName)
    {
        $zipFile = tempnam(sys_get_temp_dir(), 'laravel-zip');
        $zip = new ZipArchive();

        if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {

            $timestamp = date('Ymd-His');
            $tmpFiles = [];

            foreach ($this->calendars as $index => $calendar) {
                $writer = new Xlsx($calendar->getSpreadsheet());
                $tmpFiles[$index] = sys_get_temp_dir() . 'laravel-calendar'. $index .'_' . $timestamp . '.xlsx';

                $writer->save($tmpFiles[$index]);

                $zip->addFile($tmpFiles[$index], $fileName . '_' . $index .'_' . $timestamp . '.xlsx');

                $calendar->clear();
            }

            $zip->close();

            foreach ($tmpFiles as $tmpFile) {
                unlink($tmpFile);
            }

            return response()->download($zipFile, $fileName . '_' . $timestamp . '.zip')
                ->deleteFileAfterSend(true);
        }
    }
}