<?php

namespace App\DataTransferObjects\Auth;
use Spatie\LaravelData\Data;

class Credentials extends Data
{
    public function __construct(
        public string $email,
        public string $password
    )
    {

    }
}
