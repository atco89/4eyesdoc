<?php
declare(strict_types=1);

namespace App\Repositories\Patient\Validation;

use App\Validation\Validator;
use Respect\Validation\Validator as v;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class PatientValidation
 * @package App\Repositories\Patient\Validation
 */
class PatientValidation
{

    /** @var Container */
    protected $container;

    /**
     * PatientValidation constructor.
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
    public function createPatientValidation(Request $request): Validator
    {
        return $this->container->validator->validate($request, [
            'name'               => v::notEmpty()->addRule(v::alpha('šđžčćŠĐŽČĆ'))->addRule(v::length(1, 255))->setName('Ime'),
            'surname'            => v::notEmpty()->addRule(v::alpha('šđžčćŠĐŽČĆ'))->addRule(v::length(1, 255))->setName('Prezime'),
            'personalId'         => v::optional(v::digit()->addRule(v::noWhitespace())->addRule(v::length(13, 13))->setName('JMBG')),
            'dateOfBirth'        => v::notEmpty()->addRule(v::date('d.m.Y'))->setName('Datum rođenja'),
            'sex'                => v::callback(function ($input) {
                return in_array($input, [0, 1]);
            })->setName('Pol'),
            'email'              => v::optional(v::email()->addRule(v::length(1, 255)))->setName('E adresa'),
            'dialNumber'         => v::notEmpty()->addRule(v::intVal()->addRule(v::positive()))->setName('Pozivni broj'),
            'phoneNumber'        => v::notEmpty()->addRule(v::digit())->addRule(v::length(6, 7))->setName('Broj telefona'),
            'contactPerson'      => v::optional(v::intVal()->addRule(v::positive()))->setName('Kontakt osoba'),
            'profession'         => v::optional(v::intVal()->addRule(v::positive()))->setName('Profesija'),
            'recommendationType' => v::optional(v::intVal()->addRule(v::positive()))->setName('Tip preporuke'),
            'associate'          => v::optional(v::intVal()->addRule(v::positive()))->setName('Podtip preporuke')
        ]);
    }

}