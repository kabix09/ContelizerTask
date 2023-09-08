<?php
declare(strict_types=1);

namespace App\Utils\Paginator;

use ApiHeadersEnum;
use App\Utils\Http\HttpClient;

class PaginatorWrapper
{
    private Paginator $paginator;
    private HttpClient $httpClient;

    public function __construct(HttpClient $httpClient, ?Paginator $paginator = null)
    {
        $this->httpClient = $httpClient;

        $this->paginator = $paginator ?? $this->init();
    }

    private function init(): Paginator
    {
        // Send test Request
        $testPrsResponse = $this->httpClient->getUsers()->getPsrResponse();

        // Fetch headers
        /** @var array $headers */
        $headers = $testPrsResponse->getHeaders();

        return new Paginator(
            currentPage: $headers[ApiHeadersEnum::X_LINKS_CURRENT->value],
            prevPage: $headers[ApiHeadersEnum::X_LINKS_PREVIOUS->value],
            nextPage: $headers[ApiHeadersEnum::X_LINKS_NEXT->value],
            firstPage: $headers[ApiHeadersEnum::X_PAGINATION_PAGE->value],
            lastPage: $headers[ApiHeadersEnum::X_PAGINATION_PAGES->value]
        );
    }

//    public function nextPage(): Paginator
//    {
//
//    }
}