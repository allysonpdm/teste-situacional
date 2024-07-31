<?php

namespace App\DataTransferObjects\Auth;

use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class Token extends Data
{
    #[Computed]
    public string $expires_in;

    public function __construct(
        public string $access_token,
        public string $token_type = 'bearer',

    )
    {
        $this->expires_in = auth()->factory()->getTTL() * 60;
    }
}
