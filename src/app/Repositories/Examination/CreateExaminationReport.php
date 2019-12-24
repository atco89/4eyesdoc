<?php
declare(strict_types=1);

namespace App\Repositories\Examination;

use App\Models\TblExaminationReports;
use Illuminate\Database\Connection;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateExaminationReport
 * @package App\Repositories\Examination
 */
class CreateExaminationReport
{

    /** @var int */
    protected $id;
    /** @var int */
    protected $appointmentId;
    /** @var int */
    protected $templateId;
    /** @var Container */
    protected $container;

    /**
     * CreateExaminationReport constructor.
     * @param int $appointmentId
     * @param int $templateId
     * @param Container $container
     */
    public function __construct(int $appointmentId, int $templateId, Container $container)
    {
        $this->appointmentId = $appointmentId;
        $this->templateId = $templateId;
        $this->container = $container;
    }

    /**
     * @param Connection $connection
     * @param Request $request
     */
    public function run(Connection &$connection, Request $request): void
    {
        $table = new TblExaminationReports;
        $connection->table($table->getTable())->where('appointment_id', $this->appointmentId)->update([
            'active' => false
        ]);

        $dataSet = [
            'template_id'                      => $this->templateId,
            'appointment_id'                   => $this->appointmentId,
            'anamnesis'                        => $request->getParam('anamnesis'),
            'vod'                              => $request->getParam('vod'),
            'vos'                              => $request->getParam('vos'),
            'tod'                              => $request->getParam('tod'),
            'tos'                              => $request->getParam('tos'),
            'tod_c'                            => $request->getParam('tod-c'),
            'tod_s'                            => $request->getParam('tod-s'),
            'rod'                              => $request->getParam('rod'),
            'ros'                              => $request->getParam('ros'),
            'cct_od'                           => $request->getParam('cct-od'),
            'cct_os'                           => $request->getParam('cct-os'),
            'kf_od'                            => $request->getParam('kf-od'),
            'kf_os'                            => $request->getParam('kf-os'),
            'ar_od'                            => $request->getParam('ar-od'),
            'ar_os'                            => $request->getParam('ar-os'),
            'ar_od_ar_os_measurement_method'   => $request->getParam('ar-od-ar-os-measurement-method'),
            'kr_od'                            => $request->getParam('kr-od'),
            'kr_os'                            => $request->getParam('kr-os'),
            'motilitet_ou'                     => $request->getParam('motilitet-ou'),
            'foko_f_od'                        => $request->getParam('foko-f-od'),
            'foko_f_os'                        => $request->getParam('foko-f-os'),
            'foko_k_od'                        => $request->getParam('foko-k-od'),
            'foko_k_os'                        => $request->getParam('foko-k-os'),
            'foko_n_od'                        => $request->getParam('foko-n-od'),
            'foko_n_os'                        => $request->getParam('foko-n-os'),
            'add_od'                           => $request->getParam('add-od'),
            'add_os'                           => $request->getParam('add-os'),
            'slod'                             => $request->getParam('slod'),
            'slos'                             => $request->getParam('slos'),
            'slou'                             => $request->getParam('slou'),
            'fod'                              => $request->getParam('fod'),
            'fos'                              => $request->getParam('fos'),
            'fou'                              => $request->getParam('fou'),
            'kvp'                              => $request->getParam('kvp'),
            'kvp_conclusion'                   => $request->getParam('kvp-conclusion'),
            'kvp_control'                      => $request->getParam('kvp-control'),
            'oct_pno'                          => $request->getParam('oct-pno'),
            'oct_pno_conclusion'               => $request->getParam('oct-pno-conclusion'),
            'oct_pno_control'                  => $request->getParam('oct-pno-control'),
            'eye_ultrasound'                   => $request->getParam('eye-ultrasound'),
            'meibomography'                    => $request->getParam('meibomography'),
            'corneal_topography'               => $request->getParam('corneal-topography'),
            'examination_conclusion'           => $request->getParam('examination-conclusion'),
            'examination_advice'               => $request->getParam('examination-advice'),
            'control_with_the_ophthalmologist' => $request->getParam('control-with-ophthalmologist'),
            'therapy_at_home'                  => $request->getParam('therapy-at-home'),
            'diagnosis'                        => $request->getParam('diagnosis'),
            'lva'                              => $request->getParam('lva'),
            'created_by'                       => $_SESSION['id']
        ];

        $connection->table($table->getTable())->insert($dataSet);
        $this->id = intval($connection->getPdo()->lastInsertId());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

}