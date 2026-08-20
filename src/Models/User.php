<?php

namespace App\Models;

require __DIR__ . ('/Model.php');


use JsonSerializable;

class User extends Model
{

    protected string $firstname;
    protected string $lastname;
    protected string $email;
    protected string $password_hash;
    protected string $phone_number;
    protected bool $admin;
    protected bool $must_change_password;

    public function __construct(
        string $firstname,
        string $lastname,
        string $email,
        string $password_hash,
        string $phone_number,
        bool $admin,
        bool $must_change_password,
        ?int $id = null
    ) {
        parent::__construct($id);
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->phone_number = $phone_number;
        $this->admin = $admin;
        $this->must_change_password = $must_change_password;
    }

    public function getFirstName(): string
    {
        return $this->firstname;
    }

    public function getLastName(): string
    {
        return $this->lastname;
    }

    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    public function getAdmin(): bool
    {
        return $this->admin;
    }

    public function getMustChangePassword()
    {
        return $this->must_change_password;
    }

    protected static function getKeys(): array
    {
        return [
            'id',
            'firstname',
            'lastname',
            'email',
            'password_hash',
            'phone_number',
            'admin',
            'must_change_password'
        ];
    }

    protected function getValues(): array
    {
        return [
            self::getId(),
            self::getFirstName(),
            self::getLastName(),
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
            $data['firstname'],
            $data['lastname'],
            $data['email'],
            $data['password_hash'],
            $data['phone_number'],
            $data['admin'],
            $data['must_change_password'],
            $data['id']
        );
    }

    public function assertPassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, self::getPasswordHash());
    }

    public function assertData(): bool
    {

        if (trim(self::getFirstName()) === '') {
            return false;
        }

        if (trim(self::getLastName()) === '') {
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
