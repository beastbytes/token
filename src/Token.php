<?php

declare(strict_types=1);

namespace BeastBytes\Token;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Yiisoft\Auth\IdentityInterface;

final class Token
{
    private ?string $token = null;
    private ?string $type = null;
    private ?string $userId = null;
    private DateTimeInterface $validUntil;

    public function __construct(
        readonly TokenInterface $tokenType,
        readonly IdentityInterface $user
    )
    {
        $time = new DateTimeImmutable();
        $this->token = Uuid::uuid4()->toString();
        $this->userId = $user->getId();
        $this->validUntil = ($time)
            ->add(new DateInterval('PT' . $this->tokenType->getDuration() . 'M'))
        ;
    }

    /**
     * @return ?string The token value
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return ?string Id of the user the token applies to.
     */
    public function getUserId(): ?string
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
     * @param TokenInterface $tokenType
     * @return bool Whether the token is of the given type.
     */
    public function isType(TokenInterface $tokenType): bool
    {
        return $this->type === $tokenType->getType();
    }

    /**
     * @param TokenInterface $tokenType
     * @return bool Whether the token is valid; is of the given type and has not expired.
     */
    public function isValid(TokenInterface $tokenType): bool
    {
        return $this->isType($tokenType) && !$this->isExpired();
    }
}