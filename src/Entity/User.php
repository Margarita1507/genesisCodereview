<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @method string getUserIdentifier()
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private string $password;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    //pulls all users from a file
    static function getUsers(): array
    {
        $content = file_get_contents('users.txt');
        preg_match_all('/email:(?<email>[\w\d@\.]*)\|password:(?<password>[\w\d@$\.\/]*)/', $content, $users);
        return $users;
    }

    //checks if a user already exists with this email
    public function isCreate(): bool
    {
        $createdUsers[] = static::getUsers();
        if (in_array($this->getEmail(), $createdUsers[0]['email'])) {
            return true;
        }
        return false;
    }

    //checks if the user is logged in
    public function isLogin(): bool
    {
        $createdUsers[] = static::getUsers();
        for ($i = 0; $i < count($createdUsers[0]['email']); $i++) {
            if ($createdUsers[0]['email'][$i] == $this->getEmail() && password_verify($this->getPassword(), $createdUsers[0]['password'][$i])) {
                return true;
            }
        }
        return false;
    }

    //writes the user to file
    public function writeUser(): void
    {
        file_put_contents("users.txt", ['email:'.$this->getEmail().'|','password:'.password_hash($this->getPassword(), PASSWORD_DEFAULT)."\n"], FILE_APPEND);
    }
}
