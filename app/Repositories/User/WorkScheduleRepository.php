<?php
declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\TblUserWorkSchedule;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class WorkScheduleRepository
 * @package App\Repositories\User
 */
class WorkScheduleRepository
{

    /** @var Container */
    protected $container;

    /**
     * WorkScheduleRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return Builder
     */
    private function load(): Builder
    {
        return TblUserWorkSchedule::with(['user', 'updatedBy', 'createdBy']);
    }

    /**
     * @return array
     */
    public function loadScheduleReport(): array
    {
        return $this->load()->get()->filter(function ($row) {
            $followersRepository = new FollowersRepository($this->container);
            $following = $followersRepository->findWhatUserFollows($_SESSION['id']);
            $isActive = $row->active;
            $isDoctor = in_array($row->user->groupRole->group_id, [1, 6]);
            $isFollowed = in_array($row->user->id, $following);
            $userIsDoctor = in_array($_SESSION['group_id'], [1, 6]);

            return $userIsDoctor ? $isFollowed && $isDoctor && $isActive : $isDoctor && $isActive;
        })->map(function ($row) {
            $dateTime = (new DateTime($row->start_date_time))->diff(new DateTime($row->end_date_time));
            $hours = $dateTime->h;
            $minutes = $dateTime->i / 60;
            return $row = [
                'id'            => $row->id,
                'name'          => $row->user->name,
                'surname'       => $row->user->surname,
                'color'         => $row->user->user_color,
                'startDateTime' => (new DateTime($row->start_date_time))->format('Y-m-d H:i:s'),
                'endDateTime'   => (new DateTime($row->end_date_time))->format('Y-m-d H:i:s'),
                'hours'         => number_format($hours + $minutes, 2, '.', ','),
                'username'      => $row->updatedBy->username,
                'updatedAt'     => $row->updated_at->format('Y-m-d H:i:s')
            ];
        })->toArray();
    }

    /**
     * @param int $doctorId
     * @param DateTime $scheduleDate
     * @param DateTime $startDateTime
     * @param DateTime $endDateTime
     * @return bool
     */
    public function overlapping(int $doctorId, DateTime $scheduleDate, DateTime $startDateTime, DateTime $endDateTime): bool
    {
        $startDateTimeFormatted = $startDateTime->format('Y-m-d H:i:s');
        $endDateTimeFormatted = $endDateTime->format('Y-m-d H:i:s');
        $workingPeriods = $this->load()->get()->filter(function ($row) use ($doctorId, $scheduleDate) {
            $startDate = $scheduleDate->format('Y-m-d');
            $startDateTime = (new DateTime($row->start_date_time))->format('Y-m-d');
            $endDateTime = (new DateTime($row->end_date_time))->format('Y-m-d');
            return $row->active && $row->user_id === $doctorId && $startDateTime <= $startDate && $startDate <= $endDateTime;
        });

        if ($endDateTime <= $startDateTime)
            return true;
        return $startDateTimeFormatted <= $workingPeriods->max->end_date_time && $endDateTimeFormatted >= $workingPeriods->min->start_date_time;
    }

    /**
     * @param int $doctorId
     * @param DateTime $startDateTime
     * @param DateTime $endDateTime
     * @return array
     */
    public function findByUserAndPeriod(int $doctorId, DateTime $startDateTime, DateTime $endDateTime): array
    {
        $startDateTime = $startDateTime->format('Y-m-d H:i:s');
        $endDateTime = $endDateTime->format('Y-m-d H:i:s');
        return $this->load()->get()->filter(function ($row) use ($doctorId, $startDateTime, $endDateTime) {
            return $row->active && $row->user_id === $doctorId && ($row->start_date_time >= $startDateTime && $row->end_date_time <= $endDateTime);
        })->map(function ($row) {
            $dateTime = (new DateTime($row->start_date_time))->diff(new DateTime($row->end_date_time));
            $hours = $dateTime->h;
            $minutes = $dateTime->i / 60;
            return $row = [
                'id'            => $row->id,
                'name'          => $row->user->name,
                'surname'       => $row->user->surname,
                'color'         => $row->user->user_color,
                'startDateTime' => (new DateTime($row->start_date_time))->format('Y-m-d H:i:s'),
                'endDateTime'   => (new DateTime($row->end_date_time))->format('Y-m-d H:i:s'),
                'hours'         => number_format($hours + $minutes, 2, '.', ','),
                'username'      => $row->updatedBy->username,
                'updatedAt'     => $row->updated_at->format('Y-m-d H:i:s')
            ];
        })->toArray();
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        if ($id === null)
            return null;
        return $this->load()->get()->filter(function ($row) use ($id) {
            return $row->active && $row->id === $id;
        })->map(function ($row) {
            return $row;
        })->toArray();
    }

    /**
     * @param int $examinationId
     * @param DateTime $startDateTime
     * @return array
     */
    public function loadAvailableDoctors(int $examinationId, DateTime $startDateTime): array
    {
        $startDateTime = $startDateTime->format('Y-m-d H:i:s');
        $userMayDoExaminations = new UserMayDoExaminations($this->container);
        return $this->load()->get()->filter(function ($row) use ($examinationId, $startDateTime, $userMayDoExaminations) {
            $isActive = $row->active;
            $examinationAvailable = in_array($examinationId, $userMayDoExaminations->findWhichExamsUserMayDo($row->user_id));
            $periodMatching = $row->start_date_time <= $startDateTime && $startDateTime <= $row->end_date_time;

            return $isActive && $examinationAvailable && $periodMatching;
        })->map(function ($row) {
            return [
                'id'      => $row->user->id,
                'title'   => $row->user->groupRole->role->title,
                'name'    => $row->user->name,
                'surname' => $row->user->surname
            ];
        })->toArray();
    }

    /**
     * @return array
     */
    public function loadExcelReport(): array
    {
        return $this->load()->get()->filter(function ($row) {
            return $row->active;
        })->map(function ($row) {
            $dateTime = (new DateTime($row->start_date_time))->diff(new DateTime($row->end_date_time));
            $hours = $dateTime->h;
            $minutes = $dateTime->i / 60;
            return $row = [
                'name'          => $row->user->name,
                'surname'       => $row->user->surname,
                'color'         => $row->user->user_color,
                'startDateTime' => $row->start_date_time,
                'endDateTime'   => $row->end_date_time,
                'hours'         => number_format($hours + $minutes, 2, '.', ','),
                'updatedAt'     => $row->updated_at,
                'username'      => $row->updatedBy->username
            ];
        })->toArray();
    }

}