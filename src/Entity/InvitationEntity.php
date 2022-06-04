<?php

namespace App\Entity;

use App\Constant\InvitationStatusConstant;
use App\Repository\InvitationEntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: InvitationEntityRepository::class)]
class InvitationEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 20)]
    private $status = InvitationStatusConstant::INVITATION_SENT_STATUS;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $subject;

    #[ORM\Column(type: 'text', nullable: true)]
    private $text;

    #[ORM\ManyToOne(targetEntity: UserEntity::class, inversedBy: 'sentInvitationEntities')]
    #[ORM\JoinColumn(nullable: false)]
    private $sender;

    #[ORM\ManyToOne(targetEntity: UserEntity::class, inversedBy: 'receivedInvitationEntities')]
    #[ORM\JoinColumn(nullable: false)]
    private $receiver;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getSender(): ?UserEntity
    {
        return $this->sender;
    }

    public function setSender(?UserEntity $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?UserEntity
    {
        return $this->receiver;
    }

    public function setReceiver(?UserEntity $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }
}
