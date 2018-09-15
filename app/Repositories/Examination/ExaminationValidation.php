<?php
declare(strict_types=1);

namespace App\Repositories\Examination;

use App\Validation\Validator;
use Respect\Validation\Validator as v;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class ExaminationValidation
 * @package App\Repositories\Examination
 */
class ExaminationValidation
{

    /** @var Container */
    protected $container;

    /**
     * ExaminationValidation constructor.
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
    public function createReportValidation(Request $request): Validator
    {
        return $this->container->validator->validate($request, [
            'icd-10' => v::notEmpty()->addRule(v::each(v::intVal()->addRule(v::positive())))->setName('ICD')
        ]);
    }

}