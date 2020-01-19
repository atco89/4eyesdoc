<?php
declare(strict_types=1);

namespace App\Reports;

use DateTime;
use Illuminate\Database\Capsule\Manager as DB;
use Slim\Container;

/**
 * Class Reports
 * @package App\Reports
 */
class Reports
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * Reports constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param DateTime|null $startDateTime
     * @param DateTime|null $endDateTime
     * @return array
     */
    public function incomeReportByExaminationType(?DateTime $startDateTime = null, ?DateTime $endDateTime = null): array
    {
        $period = $startDateTime instanceof DateTime && $endDateTime instanceof DateTime
            ? " and app.start_date_time between '" . $startDateTime->format('Y-m-d') . "' and '" . $endDateTime->format('Y-m-d') . "' "
            : '';

        $query = '
            select eme.name as label, sum(tep.price) as y
            from tbl_appointments as app
            join tbl_examination_reports ter on app.id = ter.appointment_id
            join enum_medical_examinations as eme on app.medical_examination_id = eme.id
            join tbl_examination_price tep on eme.id = tep.medical_examination_id
            where eme.active = true
            and tep.active = true
            and ter.active = true
            ' . $period . '
            group by eme.name
            order by y desc;
        ';

        return DB::select($query);
    }

    /**
     * @param DateTime|null $startDateTime
     * @param DateTime|null $endDateTime
     * @return array
     */
    public function incomeReportByPatient(?DateTime $startDateTime = null, ?DateTime $endDateTime = null): array
    {
        $period = $startDateTime instanceof DateTime && $endDateTime instanceof DateTime
            ? " and app.start_date_time between '" . $startDateTime->format('Y-m-d') . "' and '" . $endDateTime->format('Y-m-d') . "' "
            : '';

        $query = "
            select concat(tp.name, ' ', tp.surname) as label,
                   sum(tep.price)                   as y
            from tbl_appointments as app
                     join tbl_patients tp on app.patient_id = tp.id
                     join tbl_examination_reports ter on app.id = ter.appointment_id
                     join enum_medical_examinations as eme on app.medical_examination_id = eme.id
                     join tbl_examination_price tep on eme.id = tep.medical_examination_id
            where eme.active = true
              and tep.active = true
              and ter.active = true
              $period
            group by label
            order by y desc
            limit 20;
        ";

        return DB::select($query);
    }
}
