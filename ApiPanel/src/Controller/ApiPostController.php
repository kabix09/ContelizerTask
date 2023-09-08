<?php

namespace App\Controller;

use App\Form\EditPostType;
use App\Utils\Http\HttpClient;
use PHPUnit\Util\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiPostController extends AbstractController
{
    private HttpClient $httpClient;
    private LoggerInterface $logger;

    public function __construct(HttpClient $httpClient, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    #[Route('/user/{id}/posts', name: 'app_api_user_posts')]
    public function index(int $id): Response
    {
        /** @var array $user */
        $user = $this->httpClient->getUser($id)->getResponseContent();

        /** @var array $users
         * Each block has schema like [ id, user_id, title, body ]
         */
        $userPosts = $this->httpClient->getUserPosts($id)->getResponseContent();

        return $this->render('panel/post/list.html.twig', [
            'posts' => $userPosts,
            'author' => $user
        ]);
    }

    #[Route('post/{id}/edit', name: 'app_post_edit')]
    public function edit(int $id, Request $request): Response
    {
        /** @var array $post */
        $post = $this->httpClient->getPost($id)->getResponseContent();

        $form = $this->createForm(EditPostType::class, options: [
            'value_post_title' => $post['title'],
            'value_post_body' => $post['body']
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // TODO add log attemps which failed
            try {
                $newData = $form->getData();

                $response = $this->httpClient->updateUsersPost($post['id'], $newData['title'], $newData['content']);

                $this->logger->info(sprintf('Post %s updated successfully!!!', $post['id']));

            } catch (Exception $e)
            {
                $this->logger->error(sprintf('Post %s edit attempt fail. Details: %s', $post['id'], $e->getMessage()));
            }

            return $this->redirectToRoute('app_api_user_posts', [
                'id' => $response->getResponseContent()['user_id']
            ]);

        }

        return $this->render('panel/post/form.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
