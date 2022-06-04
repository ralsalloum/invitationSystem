<?php

namespace App\Constant;

final class InvitationStatusConstant
{
    const INVITATION_SENT_STATUS = 'sent';

    const INVITATION_CANCELLED_STATUS = 'cancelled';

    const INVITATION_ACCEPTED_STATUS = 'accepted';

    const INVITATION_DECLINED_STATUS = 'declined';

    const WRONG_INVITATION_STATUS = 'wrongInvitationStatus';

    const INVITATION_STATUS_ARRAY = [
        self::INVITATION_CANCELLED_STATUS,
    ];
}
