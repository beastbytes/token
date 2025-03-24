<?php

declare(strict_types=1);

namespace BeastBytes\Token;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * Tokens data transfer object
 *
 * @psalm-type RawToken = array{
 *      token: string,
 *      type: string,
 *      userId: string,
 *      validUntil: int
 *  }
 */
final class Token
{
    private ?string $type = null;
    private DateTimeInterface $validUntil;

    /**
     * @param string $token
     * @param TokenTypeInterface $tokenType
     * @param string $userId
     * @throws \DateMalformedIntervalStringException // PHP >= 8.3
     */
    public function __construct(
        private readonly string $token,
        TokenTypeInterface $tokenType,
        private readonly string $userId,
    )
    {
        $this->type = $tokenType->getType();
        $this->validUntil = (new DateTimeImmutable())
            ->add(new DateInterval('PT' . $tokenType->getDuration() . 'M'))
        ;
    }

    /**
     * @return string The token value
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string ID of the user the token applies to.
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return bool Whether the token has expired.
     */
    public function isExpired(): bool
    {
        return new DateTimeImmutable() > $this->validUntil;
    }

    /**
     * @param TokenTypeInterface $tokenType
     * @return bool Whether the token is of the given type.
     */
    public function isType(TokenTypeInterface $tokenType): bool
    {
        return $this->type === $tokenType->getType();
    }

    /**
     * @param TokenTypeInterface $tokenType
     * @return bool Whether the token is valid; is of the given type and is not expired.
     */
    public function isValid(TokenTypeInterface $tokenType): bool
    {
        return $this->isType($tokenType) && !$this->isExpired();
    }

    /**
     * @psalm-return RawToken
     */
    public function toArray(): array
    {
        return [
            'token' => $this->token,
            'type' => $this->type,
            'user_id' => $this->userId,
            'valid_until' => $this->validUntil->getTimestamp()
        ];
    }
}