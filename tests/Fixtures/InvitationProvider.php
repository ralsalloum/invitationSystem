<?php

namespace App\Tests\Fixtures;

use PHPUnit\Framework\TestCase;

class InvitationProvider extends TestCase
{
    /**
     * @return string[][]
     */
    public function provideInvitationData(): array
    {
        return [
            'invitation is created' => ["sent", "sent"]
        ];
    }

    /**
     * @return string[][]
     */
    public function provideInvitationStatusForSender(): array
    {
        return  [
            'update invitation status by valid status' => ["cancelled", "cancelled"], ["sent", "sent"]
        ];
    }

    /**
     * @return string[][]
     */
    public function provideInvitationStatusForReceiver(): array
    {
        return  [
            'update invitation status by valid status' => ["accepted", "accepted"], ["declined", "declined"]
        ];
    }
}
