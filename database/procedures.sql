DROP PROCEDURE IF EXISTS searchAppointments;
DROP PROCEDURE IF EXISTS findAppointmentsRelatedToSchedule;

DELIMITER $$
CREATE PROCEDURE `searchAppointments`(start_date DATE, end_date DATE, doctor_id INT(11), diagnosis TEXT)
  BEGIN
    SET @period := if(start_date IS NULL AND end_date IS NULL, "",
                      concat(" AND DATE_FORMAT(a.start_date_time, '%Y-%m-%d') BETWEEN '", start_date, "' AND '",
                             end_date, "' "));
    SET @doctor := if(doctor_id IS NULL, "", CONCAT(" AND a.doctor_id = ", doctor_id));
    SET @diagnosis := if(diagnosis IS NULL, "",
                         CONCAT(" AND MATCH( r.diagnosis ) AGAINST('", diagnosis, "*' IN BOOLEAN MODE) "));
    SET @stmt := concat("
            SELECT DISTINCT
                a.id
            FROM `tbl_examination_reports` AS r
            RIGHT JOIN `tbl_appointments` AS a ON r.appointment_id = a.id
            WHERE 1 = 1
            ", @period, " ", @doctor, " ", @diagnosis, ";
        ");
    PREPARE STMT1 FROM @stmt;
    EXECUTE STMT1;
  END $$
DELIMITER ;

######################################################################################################

DELIMITER $$
CREATE PROCEDURE `findAppointmentsRelatedToSchedule`(work_schedule_id INT(11))
  BEGIN
    SELECT a.id
    FROM tbl_user_work_schedule AS w
      INNER JOIN tbl_appointments AS a ON w.user_id = a.doctor_id
      INNER JOIN enum_examination_status AS s ON a.examination_status_id = s.id
    WHERE w.id = work_schedule_id
          AND s.treatment NOT IN (0, 2)
          AND (
            a.start_date_time BETWEEN w.start_date_time AND w.end_date_time
            OR
            a.end_date_time BETWEEN w.start_date_time AND w.end_date_time
          );
  END $$
DELIMITER ;