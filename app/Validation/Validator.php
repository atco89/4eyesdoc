<?php
declare(strict_types=1);

namespace App\Validation;

use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class Validator
 * @package App\Validation
 */
class Validator
{

    /** @var array */
    protected $errors;
    /** @var Container */
    protected $container;

    /**
     * Validator constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param array $rules
     * @return Validator
     */
    public function validate(Request $request, array $rules): Validator
    {
        foreach ($rules as $field => $rule)
            try {
                $rule->assert($request->getParam($field));
            } catch (NestedValidationException $e) {
                $this->errors[$field] = $e->getMessages();
            }
        $this->container->view->getEnvironment()->addGlobal('errors', $this->errors);
        return $this;
    }

    /**
     * @return bool
     */
    public function failed(): bool
    {
        return !empty($this->errors);
    }

}