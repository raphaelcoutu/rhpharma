<?php

namespace App\Services;

use PDO;

class AzureRepository
{
    function constraints($startDate, $endDate)
    {
        $time_start = microtime(true);

        $conn = new PDO('sqlsrv:server='.config('azure.host').';Database='.config('azure.database'),
            config('azure.username'), config('azure.password'));
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $tsql = "SELECT U.Id as User_id, U.FirstName, U.LastName,
            C.Id as Constraint_id, C.StartDate, C.EndDate, C.Weight, C.Comment, C.Status, C.NumberOfOccurrences, C.Disposition, C.IsDayOfWeek, C.Day, C.Day1, C.Discriminator,
            Ct.Id as ConstraintType_id, Ct.Name
            FROM Constraints As C
            JOIN ConstraintTypes AS Ct ON Ct.Id = C.TypeId
            JOIN Users As U ON U.Id = C.UserId
            WHERE ((StartDate >= ? AND EndDate <= ?) OR StartDate <= ? AND EndDate >= ?) AND TypeID <> 79
            ORDER BY U.Lastname";
        $getResults = $conn->prepare($tsql);
        $getResults->execute([$startDate, $endDate, $endDate, $startDate]);
        $results = $getResults->fetchAll(PDO::FETCH_ASSOC);

        $time_end = microtime(true);
        $execution_time = round((($time_end - $time_start)*1000),2);

        return $results;

    }

    function constraintTypesByIds($ids) {
        $conn = new PDO('sqlsrv:server='.config('azure.host').';Database='.config('azure.database'),
            config('azure.username'), config('azure.password'));
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $qMarks = str_repeat('?,', count($ids) -1) . '?';

        $tsql = "SELECT Id, Name, Description
            FROM ConstraintTypes As CT
            WHERE Id IN ($qMarks)";
        $getResult = $conn->prepare($tsql);
        $getResult->execute($ids);
        $result = $getResult->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function usersByIds($ids) {
        $conn = new PDO('sqlsrv:server='.config('azure.host').';Database='.config('azure.database'),
            config('azure.username'), config('azure.password'));
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $qMarks = str_repeat('?,', count($ids) -1) . '?';

        $tsql = "SELECT Id, FirstName, LastName
            FROM Users As U
            WHERE Id IN ($qMarks)";
        $getResult = $conn->prepare($tsql);
        $getResult->execute($ids);
        $result = $getResult->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
