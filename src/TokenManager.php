<?php

declare(strict_types=1);

namespace BeastBytes\Token;

final class TokenManager implements TokenManagerInterface
{
    public function __construct(
        private readonly TokenFactoryInterface $factory,
        private readonly TokenStorageInterface $storage,
    )
    {
    }

    /**
     * Add a new token to the storage
     *
     * @param TokenTypeInterface $tokenType Token type to add
     * @param string $userId The user ID the token applies to
     * @throws \DateMalformedIntervalStringException // PHP >= 8.3
     */
    public function add(TokenTypeInterface $tokenType, string $userId): bool
    {
        $token = new Token(
            $this->factory->createToken(),
            $tokenType,
            $userId
        );

        return $this
            ->storage
            ->add($token)
        ;
    }

    /**
     * Clear all tokens from the storage
     *
     * @return void
     */
    public function clear(): void
    {
        $this
            ->storage
            ->clear()
        ;
    }

    /**
     * Delete a token from the storage
     *
     * @param Token $token
     * @return bool
     */
    public function delete(Token $token): bool
    {
        return $this
            ->storage
            ->delete($token)
        ;
    }

    /**
     * Test if a token exists in the storage
     *
     * @param string $token
     * @return bool
     */
    public function exists(string $token): bool
    {
        return $this
            ->storage
            ->exists($token)
        ;
    }

    /**
     * Get a token from the storage
     *
     * @param string $token
     * @return Token|null
     */
    public function get(string $token): ?Token
    {
        return $this
            ->storage
            ->get($token)
        ;
    }
}