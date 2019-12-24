<?php
declare(strict_types=1);

namespace App\Repositories\Patient\Update;

use App\Models\TblPatientsModel;
use DateTime;
use Illuminate\Database\Connection;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class UpdatePatient
 * @package App\Repositories\Patient
 */
class UpdatePatient
{

    /** @var int */
    public $id;
    /** @var Container */
    protected $container;

    /**
     * UpdatePatient constructor.
     * @param int $id
     * @param Container $container
     */
    public function __construct(int $id, Container $container)
    {
        $this->id = $id;
        $this->container = $container;
    }

    /**
     * @param Connection $connection
     * @param Request $request
     */
    public function run(Connection &$connection, Request $request): void
    {
        $profession = $request->getParam('profession');
        $contactPerson = $request->getParam('contactPerson');
        $dataSet = [
            'name'              => $request->getParam('name'),
            'surname'           => $request->getParam('surname'),
            'personal_id'       => $request->getParam('personalId'),
            'date_of_birth'     => (new DateTime($request->getParam('dateOfBirth')))->format('Y-m-d'),
            'sex_id'            => $request->getParam('sex'),
            'email'             => $request->getParam('email'),
            'dial_number_id'    => $request->getParam('dialNumber'),
            'phone_number'      => $request->getParam('phoneNumber'),
            'address'           => $request->getParam('address'),
            'contact_person_id' => empty($contactPerson) ? 1 : $contactPerson,
            'profession_id'     => empty($profession) ? 1 : $profession,
            'user_comment'      => $request->getParam('comment'),
            'updated_by'        => $_SESSION['id']
        ];
        $connection->table((new TblPatientsModel)->getTable())
            ->where('id', '=', $this->id)
            ->update($dataSet);

        $this->id = (integer)$connection->getPdo()->lastInsertId();
    }

}