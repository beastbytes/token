<?php

declare(strict_types=1);

namespace BeastBytes\Token\Tests\Support;

use BeastBytes\Token\TokenTypeInterface;
use BeastBytes\Token\TokenTypeTrait;

/**
 * A token type to test against.
 * For testing purposes only.
 */
enum WrongTokenType: int implements TokenTypeInterface
{
    use TokenTypeTrait; // implements TokenTypeInterface

    case WrongType = 1000;
}