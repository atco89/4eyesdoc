<?php
declare(strict_types=1);

namespace App\Repositories\Appointment;

use DateTime;

/**
 * Class EditingRestrictions
 * @package App\Repositories\Appointment
 */
class EditingRestrictions
{

    /**
     * @param int $examinationStatusId
     * @return bool
     */
    public static function statusDoesntAllowsEditing(int $examinationStatusId): bool
    {
        return in_array($examinationStatusId, [4, 6]);
    }

    /**
     * @param string $startDateTime
     * @return bool
     */
    public static function termHasBeenExpired(string $startDateTime): bool
    {
        return new DateTime($startDateTime) < new DateTime;
    }

}