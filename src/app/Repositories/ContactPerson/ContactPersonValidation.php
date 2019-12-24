<?php
declare(strict_types=1);

namespace App\Repositories\ContactPerson;

use App\Validation\Validator;
use Respect\Validation\Validator as v;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class ContactPersonValidation
 * @package App\Repositories\ContactPerson
 */
class ContactPersonValidation
{

    /** @var Container */
    protected $container;

    /**
     * ContactPersonValidation constructor.
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
    public function run(Request $request): Validator
    {
        return $this->container->validator->validate($request, [
            'name'         => v::notEmpty()->addRule(v::alpha('šđžčćŠĐŽ'))
                ->addRule(v::length(1, 255))
                ->setName('Ime'),
            'surname'      => v::notEmpty()->addRule(v::alpha('šđžčćŠĐŽ'))
                ->addRule(v::length(1, 255))
                ->setName('Prezime'),
            'dial_number'  => v::notEmpty()->addRule(v::intVal())
                ->setName('Pozivni broj'),
            'phone_number' => v::notEmpty()->addRule(v::digit())
                ->addRule(v::length(6, 7))
                ->setName('Broj telefona'),
        ]);
    }

}