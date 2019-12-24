<?php
declare(strict_types=1);

namespace App\Repositories\Appointment;

use App\Validation\Validator;
use Respect\Validation\Validator as v;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class AppointmentValidation
 * @package App\Repositories\Appointment
 */
class AppointmentValidation
{

    /** @var Container */
    protected $container;

    /**
     * AppointmentValidation constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @return Validator
     */
    public function createAppointmentValidation(Request $request): Validator
    {
        return $this->container->validator->validate($request, [
            'patient'       => v::notEmpty()->addRule(v::intVal())->addRule(v::positive())->setName('Pacijent'),
            'examination'   => v::notEmpty()->addRule(v::intVal())->addRule(v::positive())->setName('Pregled'),
            'doctor'        => v::notEmpty()->addRule(v::intVal())->addRule(v::positive())->setName('Doktor'),
            'startDateTime' => v::notEmpty()->addRule(v::date('d.m.Y H:i'))->setName('Datum početka')
        ]);
    }

    /**
     * @param Request $request
     * @return Validator
     */
    public function updateValidation(Request $request): Validator
    {
        return $this->container->validator->validate($request, [
            'examination' => v::notEmpty()->addRule(v::intVal())->addRule(v::positive())->setName('Pregled'),
            'doctor'      => v::notEmpty()->addRule(v::intVal())->addRule(v::positive())->setName('Doktor')
        ]);
    }

    /**
     * @param Request $request
     * @return Validator
     */
    public function updateOnEventValidation(Request $request): Validator
    {
        return $this->container->validator->validate($request, [
            'id'            => v::notEmpty()->addRule(v::intVal())->addRule(v::positive())->setName('ID Pregleda'),
            'startDateTime' => v::notEmpty()->addRule(v::date('d.m.Y H:i'))->setName('Početak'),
            'endDateTime'   => v::notEmpty()->addRule(v::date('d.m.Y H:i'))->setName('Kraj')
        ]);
    }

}