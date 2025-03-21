<?php

declare(strict_types=1);

namespace BeastBytes\Token;

trait TokenTrait
{
    /**
     * @return string The type of token.
     */
    public function getType(): string
    {
        return $this->name;
    }

    /**
     * @return int The duration of the token in minutes.
     */
    public function getDuration(): int
    {
        return $this->value;
    }
}