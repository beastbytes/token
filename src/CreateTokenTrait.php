<?php

declare(strict_types=1);

namespace BeastBytes\Token;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Trait for creating a Token instance from a raw token array; used by TokenStorageInterface implementations.
 *
 * @psalm-type RawToken = array{
 *     token: string,
 *     type: string,
 *     user_id: string,
 *     valid_until: int
 * }
 */
trait CreateTokenTrait
{
    /**
     * A factory method for creating a Token instance.
     *
     * @param array $rawToken
     * @psalm-param RawToken $rawToken
     * @return Token Token instance
     * @throws ReflectionException
     */
    private function createToken(array $rawToken): Token
    {
        $token = (new ReflectionClass(Token::class))
            ->newInstanceWithoutConstructor()
        ;

        foreach ([
            'token' => 'token',
            'type' => 'type',
            'userId' => 'user_id',
            'validUntil' => 'valid_until'
        ] as $property => $raw) {
            $reflectionProperty = new ReflectionProperty(Token::class, $property);
            $reflectionProperty->setValue(
                $token,
                $property === 'validUntil'
                    ? (new \DateTimeImmutable())->setTimestamp((int) $rawToken[$raw])
                    : (string) $rawToken[$raw]
            );
        }

        return $token;
    }
}