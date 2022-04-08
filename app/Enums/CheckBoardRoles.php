<?php

namespace App\Enums;

/**
 * Class CheckBoardRoles
 * @package App\Enums
 */
class CheckBoardRoles
{
    /** @var string */
    public const CAT_NAME = 'C';

    /** @var string */
    public const MOUSE_NAME_1 = 'X';

    /** @var string */
    public const MOUSE_NAME_2 = 'Y';

    /** @var string */
    public const MOUSE_NAME_3 = 'Z';

    /** @var string[]  */
    public const MOUSE_NAMES = [
        self::MOUSE_NAME_1,
        self::MOUSE_NAME_2,
        self::MOUSE_NAME_3,
    ];
}
