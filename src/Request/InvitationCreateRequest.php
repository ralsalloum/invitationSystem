<?php

namespace App\Request;

use App\Entity\UserEntity;

class InvitationCreateRequest
{
    /**
     * @var string|null
     */
    private $subject;

    /**
     * @var string|null
     */
    private $text;

//    /**
//     * @var int|UserEntity
//     */
    private $sender;

//    /**
//     * @var string|UserEntity
//     */
    private $receiver;

//    /**
//     * @return UserEntity|int
//     */
    public function getSender()
    {
        return $this->sender;
    }

    public function setSender($sender): void
    {
        $this->sender = $sender;
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function setReceiver($receiver): void
    {
        $this->receiver = $receiver;
    }
}
