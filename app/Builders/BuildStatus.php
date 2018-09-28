<?php

namespace App\Builders;

abstract class BuildStatus {
    const Standby = 0;
    const Success = 1;
    const Error = 2;
    const Build = 3;
    const Cancel = 4;
    const Reset = 5;
    const Analyze = 6;
}