<?php
declare(strict_types=1);

namespace App\Controller;

use App\Utils\Http\HttpClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiUserController extends AbstractController
{
    private HttpClient $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/user', name: 'app_api_user_list')]
    public function index(): Response
    {
        /** @var array $users
         * Each block has schema like [ id, name, email, gender, status ]
         */
        $users = $this->httpClient->getUsers()->getResponseContent();

        return $this->render('panel/user/list.html.twig', [
            'users' => $users,
        ]);
    }
}
