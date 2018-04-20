<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        setlocale(LC_ALL, 'fr_CA');
        Carbon::setLocale('fr');

        Validator::extend('unique_interval', function ($attribute, $value, $parameters, $validator) {
            if(count($parameters) < 1) {
                throw new \InvalidArgumentException("Validation rule unique_interval requires at 1 parameter.");
            }

            $a_start = \Carbon\Carbon::parse($validator->getData()[$parameters[0]]);
            $a_end = \Carbon\Carbon::parse($value);

            $schedules = \App\Schedule::select('id','start_date','end_date')->orderBy('end_date', 'desc')->limit(12)->get();

            foreach ($schedules as $schedule) {
                //Si on édit un horaire, on skip la vérification
                if(isset($validator->getData()['id']) && $schedule->id == $validator->getData()['id']) continue;

                if(detectsIntervalCollision($a_start, $a_end, $schedule->start_date, $schedule->end_date)) {
                    return false;
                }
            }

            return true;
        });

        Validator::extend('day', function($attribute, $value, $parameters, $validator) {
            // Laravel uses Carbon. Just `use Carbon\Carbon;` it
            $day = \Carbon\Carbon::parse($value)->dayOfWeek;
            switch (strtolower($parameters[0])) {
                case 'sunday':
                    return $day == 0;
                case 'monday':
                    return $day == 1;
                case 'tuesday':
                    return $day == 2;
                case 'wednesday':
                    return $day == 3;
                case 'thursday':
                    return $day == 4;
                case 'friday':
                    return $day == 5;
                case 'saturday':
                    return $day == 6;
                default:
                    return false;
            }
        });

        Validator::replacer('day', function($message, $attribute, $rule, $parameters){
            $jour_francais = $parameters[0] == 'sunday' ? 'dimanche' : 'samedi';
            return str_replace(':day', $jour_francais, $message);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
