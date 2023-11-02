## A Projekt
Egy alap szintű API Laravelben megvalósítva. 
A prject Laravel 8-at, PHP7.4-et és MYSQL 8-at használ.

## Végpontok

A root könyvtárban megtalálható egy `Interview.postman_collection.json` nevű file. Ez egy postman collection-t tartalmaz. 

## Projekt indítása
A Laravel által biztosított sail commanddal el tudjuk indítani, a Projekt rootjában állva a `./vendor/bin/sail up` parancs kiadásával.

Ez buildeli a megfelelő image-eket és fellövi a megfelelő containereket.
A projekt a http://localhost url-en lesz elérhető

Adatbázis kezeléshez phpmyadmin-t tartalmaz a docker aminek az elérése a http://localhost:8081

## install.sh

Az installálás megkönnyítéséhez elég az install.sh file-t futtatni ez a következő lépéseket hajtja végre:

- leállítja a docker container-eket ha futnak
- elindítja a docker container-eket a háttérben, ha nem futnak és amennyiben szükséges újra lebuildeli a image-et.
- lefuttatja a laravel container-ében a composer isntall-t
- lefuttatja a laravel container-ében az artisan migrate parancsát
- importálja a testCompanyDB.csv tartalmát az adatbázisba
- létrehozza a feladatban meghatározott megszorításhoz a triggert a companies táblán, hogy ne lehessen módosítania  regusztráció dátumát.
- létrehozza a feladatban leírtak szerinti adat visszaadáshoz szükséges tárolt eljárást amenyniben az még nem létezik: későbbiekben ezt db oldalon a `CALL DynamicActivityQuery();` sql futtatásával tudjuk lekérni.

## Adatbázis lekérések a kiírásban leirtak megvalósításához

### Feladat: A regisztráció idejét ne lehessen módosítani (ezt db szinten oldjuk meg, hogy más kódrészlet se tudjon módosítani)

```sql 
set global log_bin_trust_function_creators = 1;
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS PreventCompanyFoundationDateUpdate
BEFORE UPDATE ON companies FOR EACH ROW
BEGIN
    IF NEW.companyFoundationDate <> OLD.companyFoundationDate THEN
        signal sqlstate '45000' set message_text = 'Company Foundation Date Can\'t be modify';
    END IF;
END$$

DELIMITER;
set global log_bin_trust_function_creators = 0;
```

### Feladat: Készíts egy olyan lekérdezést amely visszaadja, hogy 2001.01.01 napjától kezdve egészen a mai napig az adott napon mely cégek alakultak meg. (azon a napon ahol nem volt cég alapítás ott null értéket vegyen fel)

Amennyiben lehetséges ezt root felhasználóval futtassuk. A SET GLOBAL `cte_max_recursion_depth=100000;` miatt. 

```sql 
SET GLOBAL cte_max_recursion_depth=100000;
WITH RECURSIVE DateRange AS (
    SELECT DATE('2001-01-01') AS Date
    UNION ALL
    SELECT DATE_ADD(Date, INTERVAL 1 DAY)
    FROM DateRange
    WHERE Date < NOW()
)

SELECT DateRange.Date, IFNULL(companies.companyName, 'Nincs alapítás!') AS Company
FROM DateRange
LEFT JOIN companies ON DateRange.Date = companies.companyFoundationDate
ORDER BY DateRange.Date;
```

### Feladat: Készíts egy lekérdezést melynek az oszlopai az “activity” mezőben lévő értékek
(ezek dinamikus adatok), sorai pedig az adott activity-hez tartozó cég név legyen.

```sql 
DROP PROCEDURE IF EXISTS DynamicActivityQuery;

DELIMITER $$
CREATE PROCEDURE DynamicActivityQuery()
BEGIN
SET SESSION group_concat_max_len = 1000000;
SET @sql = NULL;

SELECT GROUP_CONCAT(DISTINCT CONCAT('MAX(CASE WHEN activity = ''', activity, ''' THEN companyName END) AS `', activity, '`'))
INTO @activityColumns
FROM companies;

SET @sql = CONCAT('
    SELECT ', @activityColumns, '
    FROM companies
    GROUP BY companyName
');

PREPARE dynamic_sql FROM @sql;
EXECUTE dynamic_sql;
DEALLOCATE PREPARE dynamic_sql;
END$$
DELIMITER ;


CALL DynamicActivityQuery();
```

