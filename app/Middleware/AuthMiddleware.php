<?php
declare(strict_types=1);

namespace App\Middleware;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AuthMiddleware
 * @package App\Middleware
 */
class AuthMiddleware extends Middleware
{

    /** @var array */
    protected $userInfo = [
        'id',
        'name',
        'surname',
        'username',
        'group_id',
        'role_id',
        'role_title',
        'may_do_examinations'
    ];

    /**
     * AuthMiddleware constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        if (empty($_SESSION))
            return $response->withRedirect($this->container->router->pathFor('login.index'));

        $list = array_filter($this->userInfo, function ($value) {
            return !array_key_exists($value, $_SESSION);
        });

        if (!empty($list))
            return $response->withRedirect($this->container->router->pathFor('login.index'));

        return $next($request, $response);
    }

}