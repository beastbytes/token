<?php

namespace BeastBytes\Token\Tests;

use BeastBytes\Token\CreateTokenTrait;
use BeastBytes\Token\Tests\Support\ExpiredToken;
use BeastBytes\Token\Tests\Support\TokenType;
use BeastBytes\Token\Tests\Support\WrongTokenType;
use BeastBytes\Token\Token;
use BeastBytes\Token\TokenTypeInterface;
use DateInterval;
use DateTimeImmutable;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    use CreateTokenTrait;

    #[dataProvider('tokenProvider')]
    public function testValidToken(int $i, TokenTypeInterface $tokenType, string $userId, Token $token)
    {
        $this->assertSame("Token$i", $token->getToken());
        $this->assertSame($userId, $token->getUserId());
        $this->assertTrue($token->isValid($tokenType));
        $this->assertTrue($token->isType($tokenType));
        $this->assertFalse($token->isExpired());
    }

    #[dataProvider('tokenProvider')]
    public function testExpiredToken(int $i, TokenTypeInterface $tokenType, string $userId, Token $token)
    {
        $token =  $this->createToken([
            'token' => "Token$i",
            'type' => $tokenType->getType(),
            'user_id' => $userId,
            'valid_until' => (new DateTimeImmutable())
                ->sub(new DateInterval('PT' . $tokenType->getDuration() . 'M'))
                ->getTimestamp(),
        ]);

        $this->assertFalse($token->isValid($tokenType));
        $this->assertTrue($token->isExpired());
    }

    #[dataProvider('tokenProvider')]
    public function testWrongTokenType(int $i, TokenTypeInterface $tokenType, string $userId, Token $token)
    {
        $this->assertFalse($token->isType(WrongTokenType::WrongType));
    }

    #[dataProvider('tokenProvider')]
    public function testCreateTokenTrait(int $i, TokenTypeInterface $tokenType, string $userId, Token $token)
    {
        $createdToken = $this->createToken([
            'token' => "Token$i",
            'type' => $tokenType->getType(),
            'user_id' => $userId,
            'valid_until' => (new DateTimeImmutable())
                ->add(new DateInterval('PT' . $tokenType->getDuration() . 'M'))
                ->getTimestamp(),
        ]);
        $this->assertInstanceOf(Token::class, $createdToken);
        $this->assertSame($token->getToken(), $createdToken->getToken());
    }

    public static function tokenProvider(): Generator
    {
        foreach (TokenType::cases() as $tokenType) {
            for ($i = 0; $i < 5; $i++) {
                $userId = (string) random_int(1, 100);
                $token = new Token("Token$i", $tokenType, $userId);
                yield compact('i', 'tokenType', 'userId', 'token');
            }
        }
    }
}


