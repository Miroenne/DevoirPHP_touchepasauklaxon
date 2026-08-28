<?php

namespace App\Models;

use App\Models\Model;
use JsonSerializable;

class User extends Model implements JsonSerializable
{

    protected string $firstName;
    protected string $lastName;
    protected string $email;
    protected string $passwordHash;
    protected string $phoneNumber;
    protected bool $isAdmin;
    protected bool $mustChangePassword;



    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $phoneNumber,
        string $passwordHash,
        bool $isAdmin,
        bool $mustChangePassword,
        ?int $id = null
    ) {

        parent::__construct($id);
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->phoneNumber = $phoneNumber;
        $this->isAdmin = $isAdmin;
        $this->mustChangePassword = $mustChangePassword;
    }

    public function getfirstName(): string
    {
        return $this->firstName;
    }

    public function getlastName(): string
    {
        return $this->lastName;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getIsAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function getMustChangePassword(): bool
    {
        return $this->mustChangePassword;
    }

    public function setFirstName(string $firstName): void
    {
        if (trim($firstName) !== '') {
            $this->firstName = $firstName;
        }
    }

    public function setLastName(string $lastName): void
    {
        if (trim($lastName) !== '') {
            $this->lastName = $lastName;
        }
    }

    public function setEmail(string $email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        }
    }

    public function setPasswordHash(string $plainPassword): void
    {
        if (trim($plainPassword) !== '' && strlen($plainPassword) > 8) {
            $this->passwordHash = password_hash($plainPassword, PASSWORD_BCRYPT);
        }
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        if (trim($phoneNumber) !== '') {
            $this->phoneNumber = $phoneNumber;
        }
    }

    public function setIsAdmin(bool $isAdmin): void
    {
        if (filter_var($isAdmin, FILTER_VALIDATE_BOOL)) {
            $this->isAdmin = $isAdmin;
        }
    }

    public function setMustChangePassword(bool $mustChangePassword): void
    {
        if (filter_var($mustChangePassword, FILTER_VALIDATE_BOOL)) {
            $this->mustChangePassword = $mustChangePassword;
        }
    }

    public function getKeys(): array
    {
        return [
            'id',
            'first_name',
            'last_name',
            'phone_number',
            'email',
            'password_hash',
            'must_change_password',
            'is_admin',
        ];
    }

    public function getValues(): array
    {
        return [
            self::getId(),
            self::getfirstName(),
            self::getlastName(),
            self::getEmail(),
            self::getPasswordHash(),
            self::getPhoneNumber(),
            (int) self::getIsAdmin(),
            (int) self::getMustChangePassword()
        ];
    }

    public static function toObject(array $data): static
    {
        return new static(
            id: $data['id'],
            firstName: $data['first_name'],
            lastName: $data['last_name'],
            email: $data['email'],
            passwordHash: $data['password_hash'],
            phoneNumber: $data['phone_number'],
            isAdmin: $data['is_admin'],
            mustChangePassword: $data['must_change_password']

        );
    }

    public function assertPassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, self::getPasswordHash());
    }

    public function assertData(): bool
    {

        if (trim(self::getfirstName()) === '') {
            return false;
        }

        if (trim(self::getlastName()) === '') {
            return false;
        }

        if (!filter_var(self::getEmail(), FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (trim(self::getPasswordHash()) === '') {
            return false;
        }

        if (trim(self::getPhoneNumber()) === '') {
            return false;
        }

        return true;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'firstName' => $this->getfirstName(),
            'lastName' => $this->getlastName(),
            'email' => $this->getEmail(),
            'phoneNumber' => $this->getPhoneNumber(),
            'isAdmin' => $this->getIsAdmin(),
            'mustChangePassword' => $this->getMustChangePassword()
        ];
    }

    public function display(): void
    {
        echo 'Nom : ' . $this->getlastName() . ' Prénom : ' . $this->firstName . '<br>';
        echo 'Email : ' . $this->getEmail() . '<br>';
        echo 'Numéro de téléphone : ' . $this->getPhoneNumber() . '<br>';

        if ($this->getIsAdmin() === true) {
            echo 'Cet utilisateur est administrateur.' . '<br>';
        } else {
            echo "Cet utilisateur n'est pas administrateur." . '<br>';
        }

        if ($this->getMustChangePassword() === true) {
            echo 'Cet utilisateur doit changer son mot de passe.' . '<br>';
        } else {
            echo 'Cet utilisateur a changé son mot de passe.' . '<br>';
        }
        echo '<br>';
    }
}
