<?php
declare(strict_types=1);

namespace App\Utils\Http\Token;

final class Token implements TokenInterface
{
    private string $clientSecret;

    public function __construct(string $clientSecretToken)
    {
        $this->clientSecret = $clientSecretToken;
    }

    /**
     * Method to build token according to doc
     * "Authorization":"Basic secretKey"
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return sprintf('%s', $this->clientSecret);
    }
}