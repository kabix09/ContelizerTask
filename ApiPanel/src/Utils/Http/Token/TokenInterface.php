<?php
declare(strict_types=1);

namespace App\Utils\Http\Token;

interface TokenInterface
{
    public function getAccessToken():string;
}