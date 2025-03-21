<?php

declare(strict_types=1);

namespace BeastBytes\Token;

interface TokenStorageInterface
{
    public function add(Token $token): bool;
    public function clear(): void;
    public function delete(Token $token): bool;
    public function exists(string $token): bool;
    public function get(string $token): ?Token;
}