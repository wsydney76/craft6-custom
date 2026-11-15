<?php

namespace App\Data;

class ContactDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $message,
    ) {}

    public static function make(string $name, string $email, string $message): self
    {
        return new self(name: $name, email: $email, message: $message);
    }
}
