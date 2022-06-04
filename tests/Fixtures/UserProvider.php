<?php

namespace App\Tests\Fixtures;

use PHPUnit\Framework\TestCase;

class UserProvider extends TestCase
{
    /**
     * @return string[][]
     */
    public function provideUserCreateRole(): array
    {
        return [
            'user is created' => [['ROLE_USER'], ['ROLE_USER']]
        ];
    }

    /**
     * @return string[][]
     */
    public function provideUserFoundResult(): array
    {
        return [
            'user is already created' => ["yes", "userIsFound"]
        ];
    }
}
