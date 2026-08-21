<?php

declare(strict_types=1);
require __DIR__ . ('/src/Models/User.php');

use App\Models\User;

$testUser = new User(
    'Jack',
    'Sparrow',
    'jsparrow@dead.com',
    'mdpest',
    '4242564',
    false,
    true,
    2
);
echo 'New user (object) : ' . '<br>';
var_dump($testUser);
echo '<br>';
echo '<br>';

echo 'New user (array from object) : ';
$userArray = $testUser->toArray();
echo '<br>';

var_dump($userArray);
echo '<br>';
echo '<br>';

$user = User::toObject($userArray);
echo 'New user (object from array : ';
echo '<br>';
var_dump($user);
echo '<br>';
echo '<br>';

var_dump($user->getAdmin());
echo 'assertData : ';
echo $user->assertData();


$utilisateur = [
    'id' => 5,
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'jdoe@email.com',
    'password_hash' => 'PasspasswordWord',
    'phone_number' => '6346430',
    'admin' => true,
    'must_change_password' => false
];

$newUser = User::toObject($utilisateur);

var_dump($newUser);
