<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity(fields={"Surname"}, message="TEST")
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tokenExpire;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActiveUser;

    /**
     * @ORM\Column(type="boolean")
     */
    private $completed;

    /*
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    /*private $imageFilename;*/

    /*
     * @ORM\ManyToOne(targetEntity=Excersises::class, inversedBy="Users")
     * @ORM\JoinColumn(nullable=false)
     */
    //private $excersises;

    public function __construct(
        string $email,
        string $password,
        string $name,
        string $surname,
        string $roles
    ){
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->password =  password_hash($password, PASSWORD_BCRYPT);
        $this->roles = $roles;
        $this->isActiveUser = 0;
        $this->token = md5(uniqid());
        $this->tokenExpire = new \DateTime("now + 60 minutes");
        $this->completed = 0;
    }

    public function activation(){
        $this->isActiveUser = 1;
        $this->token = null;
        $this->tokenExpire = null;
    }

    public function expire(){
        $this->token = md5(uniqid());
        $this->tokenExpire = new \DateTime("now + 60 minutes");
    }

    public function preChange(){
        $this->token = md5(uniqid());
        $this->tokenExpire = new \DateTime("now + 60 minutes");
        $this->isActiveUser = 0;
    }

    public function change(
        string $password
    ){
        $this->password = base64_encode($password);
        $this->token = null;
        $this->tokenExpire = null;
        $this->isActiveUser = 1;
    }

    /**
     * @see UserInterface
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getRoles()
    {
        $role = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = $role;

        return array_unique($roles);
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @see UserInterface
     */
    public function getUsername()
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @param string $roles
     */
    public function setRoles(string $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTokenExpire(): \DateTime
    {
        return $this->tokenExpire;
    }

    /**
     * @param \DateTime $tokenExpire
     */
    public function setTokenExpire(\DateTime $tokenExpire): void
    {
        $this->tokenExpire = $tokenExpire;
    }

    /**
     * @return int
     */
    public function getIsActiveUser(): int
    {
        return $this->isActiveUser;
    }

    /**
     * @param int $isActiveUser
     */
    public function setIsActiveUser(int $isActiveUser): void
    {
        $this->isActiveUser = $isActiveUser;
    }

    /**
     * @return mixed
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param mixed $completed
     */
    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;

        return $this;
    }
}
