<?php
declare(strict_types=1);

namespace App\Repositories\Examination;

use Slim\Http\Request;

/**
 * Class ExaminationParams
 * @package App\Repositories\Examination
 */
class ExaminationParams
{

    /**
     * @param Request $request
     * @return array
     */
    public static function map(Request $request): array
    {
        $data = [];
        $map = [
            'anamnesis'                      => 'anamnesis',
            'vod'                            => 'vod',
            'vos'                            => 'vos',
            'tod'                            => 'tod',
            'tos'                            => 'tos',
            'tod-c'                          => 'tod_c',
            'tod-s'                          => 'tod_s',
            'rod'                            => 'rod',
            'ros'                            => 'ros',
            'cct-od'                         => 'cct_od',
            'cct-os'                         => 'cct_os',
            'kf-od'                          => 'kf_od',
            'kf-os'                          => 'kf_os',
            'ar-od'                          => 'ar_od',
            'ar-os'                          => 'ar_os',
            'ar-od-ar-os-measurement-method' => 'ar_od_ar_os_measurement_method',
            'kr-od'                          => 'kr_od',
            'kr-os'                          => 'kr_os',
            'motilitet-ou'                   => 'motilitet_ou',
            'foko-f-od'                      => 'foko_f_od',
            'foko-f-os'                      => 'foko_f_os',
            'foko-n-od'                      => 'foko_n_od',
            'foko-n-os'                      => 'foko_n_os',
            'add-od'                         => 'add_od',
            'add-os'                         => 'add_os',
            'slod'                           => 'slod',
            'slos'                           => 'slos',
            'slou'                           => 'slou',
            'fod'                            => 'fod',
            'fos'                            => 'fos',
            'fou'                            => 'fou',
            'kvp'                            => 'kvp',
            'kvp-conclusion'                 => 'kvp_conclusion',
            'kvp-control'                    => 'kvp_control',
            'oct-pno'                        => 'oct_pno',
            'oct-pno-conclusion'             => 'oct_pno_conclusion',
            'oct-pno-control'                => 'oct_pno_control',
            'eye-ultrasound'                 => 'eye_ultrasound',
            'meibomography'                  => 'meibomography',
            'corneal-topography'             => 'corneal_topography',
            'examination-conclusion'         => 'examination_conclusion',
            'examination-advice'             => 'examination_advice',
            'control-with-ophthalmologist'   => 'control_with_the_ophthalmologist',
            'therapy-at-home'                => 'therapy_at_home',
            'diagnosis'                      => 'diagnosis',
            'lva'                            => 'lva',
            'icd-10'                         => 'icd10IDs'
        ];

        foreach ($request->getParams() as $key => $value)
            if (array_key_exists($key, $map))
                $data[$map[$key]] = $value;
            else
                $data[$key] = $value;

        return $data;
    }

}