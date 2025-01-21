<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Enum\UserState;

#[ORM\Entity]
class WebUser
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $loginId;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'userstate')]  // Use custom enum type here
    private UserState $state;  // Change to enum type

    // Getters and Setters
    public function getLoginId(): string
    {
        return $this->loginId;
    }

    public function setLoginId(string $loginId): self
    {
        $this->loginId = $loginId;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getState(): UserState
    {
        return $this->state;
    }

    public function setState(UserState $state): self
    {
        $this->state = $state;
        return $this;
    }
}
