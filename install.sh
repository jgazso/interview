#!/bin/sh

./vendor/bin/sail down
./vendor/bin/sail up -d

docker exec -i interview_laravel composer install
docker exec -i interview_laravel php artisan migrate
docker exec -i interview_mysql mysql -u sail -p laravel -e "LOAD DATA INFILE '/var/lib/mysql-files/testCompanyDB.csv' INTO TABLE companies FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 0 ROWS;"

docker exec -i interview_mysql mysql -u root -p laravel -e "
set global log_bin_trust_function_creators = 1;
DELIMITER $$
CREATE TRIGGER PreventCompanyFoundationDateUpdate
BEFORE UPDATE ON companies FOR EACH ROW
BEGIN
  IF NEW.companyFoundationDate <> OLD.companyFoundationDate THEN
    signal sqlstate '45000' set message_text = 'Company Foundation Date Can\'t be modify';
  END IF;
END$$

DELIMITER ;
set global log_bin_trust_function_creators = 0;
"

docker exec -i interview_mysql mysql -u root -p laravel -e "
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
"
