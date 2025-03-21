<?php

declare(strict_types=1);

namespace BeastBytes\Token;

final class TokenManager implements TokenManagerInterface
{
    public function __construct(private TokenStorageInterface $storage)
    {
    }

    public function add(Token $token): bool
    {
        return $this
            ->storage
            ->add($token)
        ;
    }

    public function clear(): void
    {
        $this
            ->storage
            ->clear()
        ;
    }

    public function exists(string $token): bool
    {
        return $this
            ->storage
            ->exists($token)
        ;

    }

    public function get(string $token): ?Token
    {
        return $this
            ->storage
            ->get($token)
        ;
    }

    public function delete(Token $token): bool
    {
        return $this
            ->storage
            ->delete($token)
        ;
    }
}