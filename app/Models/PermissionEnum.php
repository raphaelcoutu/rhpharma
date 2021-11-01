<?php

namespace App\Models;

abstract class PermissionEnum
{
    const ReadBranches = 'ReadBranches';
    const WriteBranches = 'WriteBranches';
    const ReadUsers = 'ReadUsers';
    const WriteUsers = 'WriteUsers';
    const ReadWorkplaces = 'ReadWorkplaces';
    const WriteWorkplaces = 'WriteWorkplaces';
    const ReadDepartments = 'ReadDepartments';
    const WriteDepartments = 'WriteDepartments';
    const ReadRoles = 'ReadRoles';
    const WriteRoles = 'WriteRoles';
    const ReadSchedules = 'ReadSchedules';
    const WriteSchedules = 'WriteSchedules';
    const ReadConstraintTypes = 'ReadConstraintTypes';
    const WriteConstraintTypes = 'WriteConstraintTypes';
    const ReadHolidays = 'ReadHolidays';
    const WriteHolidays = 'WriteHolidays';
    const ReadConstraints = 'ReadConstraints';
    const WriteConstraints = 'WriteConstraints';
    const ReadSettings = 'ReadSettings';
    const WriteSettings = 'WriteSettings';
    const ReadShiftTypes = 'ReadShiftTypes';
    const WriteShiftTypes = 'WriteShiftTypes';
    const ReadShifts = 'ReadShifts';
    const WriteShifts = 'WriteShifts';
}
