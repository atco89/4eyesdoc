<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ContactPerson\ContactPersonRepository;
use App\Repositories\ContactPerson\CreateContactPersonRepository;
use App\Repositories\DialNumbers\DialNumberRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class ContactPersonController
 * @package App\Controllers
 */
class ContactPersonController
{

    /** @var Container */
    protected $container;

    /**
     * ContactPersonController constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response): Response
    {
        $createContactPersonRepository = new CreateContactPersonRepository($this->container);
        $dialNumberRepository = new DialNumberRepository($this->container);

        if ($request->isPost())
            $createContactPersonRepository->run($request);

        return $this->container->view->render($response, 'contact-person/contact-person-modal.html.twig', [
            'dialNumbers' => $dialNumberRepository->loadActive()
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function select(Request $request, Response $response): Response
    {
        $contactPersonRepository = new ContactPersonRepository($this->container);
        return $this->container->view->render($response, 'contact-person/contact-person.html.twig', [
            'contactPersons' => $contactPersonRepository->loadActive()
        ]);
    }

}