<?php

declare(strict_types=1);

namespace BeastBytes\Token;

interface TokenFactoryInterface
{
    /**
     * @return string The token value
     */
    public function create(): string;
}