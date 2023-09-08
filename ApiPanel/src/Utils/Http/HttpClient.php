<?php
declare(strict_types=1);

namespace App\Utils\Http;

use App\Utils\Http\Exception\ResponseNotSuccessfulException;
use App\Utils\Http\Response\GoRestResponse;
use App\Utils\Http\Token\Token;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

final class HttpClient
{
    /**
     * Flag to config X-Pagination-Total header
     */
    const RECORDS_PER_PAGE = 20;

    private GuzzleHttpClient $httpClient;

    public Token $token;

    private string $apiVersion;

    /**
     * @param string $baseUrl
     * @param Token $token
     */
    public function __construct(string $apiUrl, string $apiVersion, Token $token)
    {
        $this->token = $token;

        $this->apiVersion = $apiVersion;

        $this->httpClient = $this->createHtttpClient($apiUrl);
    }

    private function createHtttpClient(string $baseUrl, ?LoggerInterface $logger = null) : GuzzleHttpClient
    {
        $handlerStack = HandlerStack::create();

        if($logger != null) {
            $handlerStack->push(
                Middleware::log($logger, new MessageFormatter('HTTP Client - Request: {request} - Response: {response} - Error: {error}'))
            );
        }

        return new GuzzleHttpClient([
            'base_uri' => $baseUrl,
            'handler' => $handlerStack,
        ]);
    }

    public function getUsers(): GoRestResponse
    {
        $response = $this->httpClient->get($this->apiVersion .  '/users', [
            'query' => [
                'per_page' => self::RECORDS_PER_PAGE
            ],
            'headers' => [
                'Authorization' => "Bearer {$this->token->getAccessToken()}",
            ]
        ]);

        return $this->genericResponse($response);
    }

    public function getUser(int $id): GoRestResponse
    {
        $response = $this->httpClient->get($this->apiVersion .  '/users/' . $id, [
            'headers' => [
                'Authorization' => "Bearer {$this->token->getAccessToken()}",
            ]
        ]);

        return $this->genericResponse($response);
    }

    public function getUserPosts(int $id): GoRestResponse
    {
        $response = $this->httpClient->get($this->apiVersion .  '/users/' . $id . '/posts', [
            'headers' => [
                'Authorization' => "Bearer {$this->token->getAccessToken()}",
            ]
        ]);

        return $this->genericResponse($response);
    }

    public function getPost(int $id): GoRestResponse
    {
        $response = $this->httpClient->get($this->apiVersion .  '/posts/' . $id, [
            'headers' => [
                'Authorization' => "Bearer {$this->token->getAccessToken()}",
            ]
        ]);

        return $this->genericResponse($response);
    }

    public function updateUsersPost(int $id, string $title, string $body)
    {
        $response = $this->httpClient->put($this->apiVersion . '/posts/' . $id, [
            'headers' => [
                'Authorization' => "Bearer {$this->token->getAccessToken()}",
            ],
            'form_params' => [
                'title' => $title,
                'body' => $body
            ]
        ]);

        return $this->genericResponse($response);
    }

    private function genericResponse(ResponseInterface $response): GoRestResponse
    {
        if (200 !== $response->getStatusCode()) {
            throw new ResponseNotSuccessfulException($response);
        }

        return new GoRestResponse($response);
    }
}