# Comment importer les contraintes

1. Connecter sur le portail de Microsoft Azure
2. Exécuter la requête suivante:
    ```sql
    DECLARE @start Date, @end Date;
    SET @start = 'AAAA-MM-JJ'
    SET @end = 'AAAA-MM-JJ'
    
    SELECT U.Id as User_id, U.FirstName, U.LastName
        , C.Id as Constraint_id, C.StartDate, C.EndDate, C.Weight, C.Comment, C.Status, C.NumberOfOccurrences, C.Disposition, C.IsDayOfWeek, C.Day, C.Day1, C.Discriminator
        , Ct.Id as ConstraintType_id, Ct.Name
    FROM Constraints As C 
    JOIN ConstraintTypes AS Ct ON Ct.Id = C.TypeId
    JOIN Users As U ON U.Id = C.UserId
    WHERE ((StartDate >= @start AND EndDate <= @end) OR StartDate <= @end AND EndDate >= @start) AND TypeID <> 79
    ORDER BY U.Lastname
    ```
3. Sélectionner et copier toutes les lignes (**incluant** l'en-tête)
4. Coller dans Excel et enregistrer sous le format "CSV UTF-8"
5. Exécuter la commande Laravel Artisan : `art import:csv <fichier> -s,`

**Attention**: Toujours s'assurer que le type de contrainte existe dans la base de données (pour prévenir les erreurs liées aux *foreign keys*)