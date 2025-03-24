<?php

declare(strict_types=1);

namespace BeastBytes\Token\Tests\Support;

use BeastBytes\Token\TokenTypeInterface;
use BeastBytes\Token\TokenTypeTrait;

enum TokenType: int implements TokenTypeInterface
{
    use TokenTypeTrait; // implements TokenTypeInterface

    case ValidType0 = 10;
    case ValidType1 = 20;
    case ValidType2 = 30;
    case ValidType3 = 40;
    case ValidType4 = 50;
    case ValidType5 = 60;
    case ValidType6 = 70;
    case ValidType7 = 80;
    case ValidType8 = 90;
    case ValidType9 = 100;
}