<?php

declare(strict_types=1);

namespace BeastBytes\Token;

interface TokenInterface
{
    /**
     * @return string The type of token.
     */
    public function getType(): string;

    /**
     * @return int The duration of the token in minutes.
     */
    public function getDuration(): int;
}