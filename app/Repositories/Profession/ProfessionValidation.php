<?php
declare(strict_types=1);

namespace App\Repositories\Profession;

use App\Validation\Validator;
use Respect\Validation\Validator as v;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class ProfessionValidation
 * @package App\Repositories\Profession
 */
class ProfessionValidation
{

    /** @var Container */
    protected $container;

    /**
     * ProfessionValidation constructor.
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
            'profession' => v::notEmpty()->addRule(v::length(1, 255))->setName('Profesija')
        ]);
    }

}