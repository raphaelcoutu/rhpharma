<?php

function detectsIntervalCollision($start_date1, $end_date1, $start_date2, $end_date2)
{
    if ($start_date1->gte($start_date2) && $start_date1->lte($end_date2)
        || $end_date2->gte($start_date1) && $start_date2->lte($end_date1)
    ) {
        return true;
    }

    return false;
}

function status($expression)
{
    if ($expression == 0) {
        return 'fa fa-clock-o';
    } elseif ($expression == 1) {
        return 'fa fa-check-circle-o text-success';
    } elseif ($expression == 2) {
        return 'fa fa-exclamation-triangle text-warning';
    } elseif ($expression == 3) {
        return 'fa fa-refresh fa-spin fa-fw text-primary';
    } else {
        return '';
    }
}
