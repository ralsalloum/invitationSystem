<?php

namespace App\Entity;

use App\Repository\UserEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: UserEntityRepository::class)]
class UserEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: InvitationEntity::class)]
    private $sentInvitationEntities;

    #[ORM\OneToMany(mappedBy: 'receiver', targetEntity: InvitationEntity::class)]
    private $receivedInvitationEntities;

    public function __construct($email)
    {
        $this->email = $email;
        $this->sentInvitationEntities = new ArrayCollection();
        $this->receivedInvitationEntities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @return Collection<int, InvitationEntity>
     */
    public function getSentInvitationEntities(): Collection
    {
        return $this->sentInvitationEntities;
    }

    public function addSentInvitationEntity(InvitationEntity $sentInvitationEntity): self
    {
        if (!$this->sentInvitationEntities->contains($sentInvitationEntity)) {
            $this->sentInvitationEntities[] = $sentInvitationEntity;
            $sentInvitationEntity->setSender($this);
        }

        return $this;
    }

    public function removeSentInvitationEntity(InvitationEntity $sentInvitationEntity): self
    {
        if ($this->sentInvitationEntities->removeElement($sentInvitationEntity)) {
            // set the owning side to null (unless already changed)
            if ($sentInvitationEntity->getSender() === $this) {
                $sentInvitationEntity->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, InvitationEntity>
     */
    public function getReceivedInvitationEntities(): Collection
    {
        return $this->receivedInvitationEntities;
    }

    public function addReceivedInvitationEntity(InvitationEntity $receivedInvitationEntity): self
    {
        if (!$this->receivedInvitationEntities->contains($receivedInvitationEntity)) {
            $this->receivedInvitationEntities[] = $receivedInvitationEntity;
            $receivedInvitationEntity->setReceiver($this);
        }

        return $this;
    }

    public function removeReceivedInvitationEntity(InvitationEntity $receivedInvitationEntity): self
    {
        if ($this->receivedInvitationEntities->removeElement($receivedInvitationEntity)) {
            // set the owning side to null (unless already changed)
            if ($receivedInvitationEntity->getReceiver() === $this) {
                $receivedInvitationEntity->setReceiver(null);
            }
        }

        return $this;
    }
}
