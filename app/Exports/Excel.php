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
            if($duration < 3) {
                $nbCalendars = 1;
            } else {
                throw \Exception('Nombre de semaines à l\'horaire non divisible par 3 ou 4.');
            }
        }

        if($nbCalendars > 1) {
            for ($i = 0; $i < $nbCalendars; $i++) {
                $start = $this->schedule->start_date->addDays($i * $divideBy * 7);
                $end = $this->schedule->start_date->addDays(($i+1) * $divideBy * 7 - 1);

                $calendar = new Calendar($start, $end, $this->users);

                $this->calendars[] = $calendar;
            }
        } else {
            $start = $this->schedule->start_date;
            $end = $this->schedule->end_date;

            $calendar = new Calendar($start, $end, $this->users);

            $this->calendars[] = $calendar;
        }

    }

    public function download(string $fileName = 'Horaire')
    {
        return $this->zip($fileName);
    }

    public function zip(string $fileName)
    {
        $zipFile = tempnam(sys_get_temp_dir(), 'laravel-zip');
        $zip = new ZipArchive();

        if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {

            $timestamp = date('Ymd-His');
            $tmpFiles = [];

            // Création des différents horaires en xlsx
            foreach ($this->calendars as $index => $calendar) {
                $writer = new Xlsx($calendar->getSpreadsheet());
                $tmpFiles[$index] = sys_get_temp_dir() . '/laravel-calendar'. $index .'_' . $timestamp . '.xlsx';

                $writer->save($tmpFiles[$index]);

                $zip->addFile($tmpFiles[$index], $fileName . '_' . $index .'_' . $timestamp . '.xlsx');

                $calendar->clear();
            }

            // Ajout du fichier des libérations
            $liberations = new Liberation($this->schedule, $this->users);
            $writer = new Xlsx($liberations->getSpreadsheet());

            $tmpFiles['liberations'] = sys_get_temp_dir() . '/liberations_' . $timestamp . '.xlsx';
            $writer->save($tmpFiles['liberations']);
            $zip->addFile($tmpFiles['liberations'], 'Liberations_' . $timestamp . '.xlsx');
            $liberations->clear();

            // Ajout du fichier des conflits
            $conflicts = new Conflict($this->schedule);
            $writer = new Xlsx($conflicts->getSpreadsheet());

            $tmpFiles['conflits'] = sys_get_temp_dir() . '/conflits_' . $timestamp . '.xlsx';
            $writer->save($tmpFiles['conflits']);
            $zip->addFile($tmpFiles['conflits'], 'Conflits_' . $timestamp . '.xlsx');
            $conflicts->clear();

            $zip->close();

            foreach ($tmpFiles as $tmpFile) {
                unlink($tmpFile);
            }

            return response()->download($zipFile, $fileName . '_' . $timestamp . '.zip')
                ->deleteFileAfterSend(true);
        }
    }
}