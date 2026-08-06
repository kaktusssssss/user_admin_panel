<?php
declare(strict_types=1);

namespace App\Models;

class User
{
    private ?int $id;
    private string $login;
    private ?string $password;
    private string $firstName;
    private string $lastName;
    private string $gender;
    private string $birthDate;
    private string $createdAt;

    public function __construct(
        ?int $id,
        string $login,
        ?string $password = null,
        string $firstName,
        string $lastName,
        string $gender,
        string $birthDate,
        string $createdAt
    ) {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->gender = $gender;
        $this->birthDate = $birthDate;
        $this->createdAt = $createdAt;
    }

    // Геттеры
    public function getId(): ?int { return $this->id; }
    public function getLogin(): string { return $this->login; }
    public function getPassword(): string { return $this->password; }
    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getGender(): string { return $this->gender; }
    public function getBirthDate(): string { return $this->birthDate; }
    public function getCreatedAt(): string { return $this->createdAt; }

    // Сеттеры (для изменяемых полей, например, без пароля)
    public function setFirstName(string $firstName): void { $this->firstName = $firstName; }
    public function setLastName(string $lastName): void { $this->lastName = $lastName; }
    public function setGender(string $gender): void { $this->gender = $gender; }
    public function setBirthDate(string $birthDate): void { $this->birthDate = $birthDate; }
}