<?php

namespace App\Models;

require __DIR__ . ('/Model.php');


use JsonSerializable;

class User extends Model
{

    protected string $firstName;
    protected string $lastName;
    protected string $email;
    protected string $passwordHash;
    protected string $phoneNumber;
    protected bool $admin;
    protected bool $mustChangePassword;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $passwordHash,
        string $phoneNumber,
        bool $admin,
        bool $mustChangePassword,
        ?int $id = null
    ) {
        parent::__construct($id);
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->phoneNumber = $phoneNumber;
        $this->admin = $admin;
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

    public function getAdmin(): bool
    {
        return $this->admin;
    }

    public function getMustChangePassword()
    {
        return $this->mustChangePassword;
    }

    protected function getKeys(): array
    {
        return [
            'id',
            'firstName',
            'lastName',
            'email',
            'passwordHash',
            'phoneNumber',
            'admin',
            'mustChangePassword'
        ];
    }

    protected function getValues(): array
    {
        return [
            self::getId(),
            self::getfirstName(),
            self::getlastName(),
            self::getEmail(),
            self::getPasswordHash(),
            self::getPhoneNumber(),
            self::getAdmin(),
            self::getMustChangePassword()
        ];
    }

    public static function toObject(array $data): static
    {
        return new static(
            id: $data['id'],
            firstName: $data['firstName'],
            lastName: $data['lastName'],
            email: $data['email'],
            passwordHash: $data['passwordHash'],
            phoneNumber: $data['phoneNumber'],
            admin: $data['admin'],
            mustChangePassword: $data['mustChangePassword']

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
}
