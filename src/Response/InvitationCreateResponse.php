<?php

namespace App\Response;

use DateTime;

class InvitationCreateResponse
{
    /**
     * @var int|null
     */
    public $id;

    /**
     * @var DateTime|null
     */
    public $createdAt;

    /**
     * @var string|null
     */
    public $status;
}
