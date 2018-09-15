<?php
declare(strict_types=1);

namespace App\Repositories\User;

use App\Validation\Validator;
use Respect\Validation\Validator as v;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class UserValidator
 * @package App\Repositories\User
 */
class UserValidator
{

    /** @var Container */
    protected $container;

    /**
     * UserValidator constructor.
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
    public function createValidation(Request $request): Validator
    {
        $isDoctor = function ($input) use ($request) {
            return in_array($request->getParam('groupId'), [1, 6]) ? v::notEmpty()->addRule(
                v::each(
                    v::intVal()->addRule(
                        v::positive()
                    )
                )
            )->validate($input) : v::alwaysValid();
        };

        return $this->container->validator->validate($request, [
            'name'         => v::notEmpty()->addRule(v::alpha('šđžčćŠĐŽČĆ'))->addRule(v::length(1, 255))->setName('Ime'),
            'surname'      => v::notEmpty()->addRule(v::alpha('šđžčćŠĐŽČĆ'))->addRule(v::length(1, 255))->setName('Prezime'),
            'groupId'      => v::notEmpty()->addRule(v::intVal())->addRule(v::positive())->setName('Kategorija'),
            'roleId'       => v::notEmpty()->addRule(v::intVal())->addRule(v::positive())->setName('Zvanje'),
            'username'     => v::notEmpty()->addRule(v::alpha('.'))->addRule(v::length(1, 255))->setName('Korisničko ime'),
            'password'     => v::notEmpty()->addRule(v::length(8, 255))->setName('Lozinka'),
            'color'        => v::notEmpty()->addRule(v::hexRgbColor())->setName('Boja'),
            'email'        => v::optional(v::email()->addRule(v::length(1, 255)))->setName('E adresa'),
            'dialNumberId' => v::notEmpty()->addRule(v::intVal())->addRule(v::positive())->setName('Pozivni broj'),
            'phoneNumber'  => v::notEmpty()->addRule(v::digit())->addRule(v::positive())->setName('Broj telefona'),
            'following'    => v::optional(v::each(v::intVal()->addRule(v::positive())))->setName('Pratim'),
            'followers'    => v::optional(v::each(v::intVal()->addRule(v::positive())))->setName('Prate me'),
            'examinations' => v::callback($isDoctor)->setName('Lista pregleda')
        ]);
    }

    /**
     * @param Request $request
     * @return Validator
     */
    public function updateValidation(Request $request): Validator
    {
        $isDoctor = function ($input) use ($request) {
            return in_array($request->getParam('groupId'), [1, 6]) ? v::notEmpty()->addRule(
                v::each(
                    v::intVal()->addRule(
                        v::positive()
                    )
                )
            )->validate($input) : v::alwaysValid();
        };

        return $this->container->validator->validate($request, [
            'name'         => v::notEmpty()->addRule(v::alpha('šđžčćŠĐŽČĆ'))->addRule(v::length(1, 255))->setName('Ime'),
            'surname'      => v::notEmpty()->addRule(v::alpha('šđžčćŠĐŽČĆ'))->addRule(v::length(1, 255))->setName('Prezime'),
            'groupId'      => v::notEmpty()->addRule(v::intVal())->addRule(v::positive())->setName('Kategorija'),
            'roleId'       => v::notEmpty()->addRule(v::intVal())->addRule(v::positive())->setName('Zvanje'),
            'username'     => v::notEmpty()->addRule(v::alpha('.'))->addRule(v::length(1, 255))->setName('Korisničko ime'),
            'password'     => v::optional(v::length(8, 255))->setName('Lozinka'),
            'color'        => v::notEmpty()->addRule(v::hexRgbColor())->setName('Boja'),
            'email'        => v::optional(v::email()->addRule(v::length(1, 255)))->setName('E adresa'),
            'dialNumberId' => v::notEmpty()->addRule(v::intVal())->addRule(v::positive())->setName('Pozivni broj'),
            'phoneNumber'  => v::notEmpty()->addRule(v::digit())->addRule(v::positive())->setName('Broj telefona'),
            'following'    => v::optional(v::each(v::intVal()->addRule(v::positive())))->setName('Pratim'),
            'followers'    => v::optional(v::each(v::intVal()->addRule(v::positive())))->setName('Prate me'),
            'examinations' => v::callback($isDoctor)->setName('Lista pregleda')
        ]);
    }

    /**
     * @param Request $request
     * @return Validator
     */
    public function passwordValidation(Request $request): Validator
    {
        return $this->container->validator->validate($request, [
            'oldPassword' => v::notEmpty()->setName('Stara lozinka'),
            'newPassword' => v::notEmpty()->addRule(v::length(8, 255))->setName('Nova lozinka')
        ]);
    }

}