<?php

namespace App\DataFixtures;

use App\Entity\User;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessor implements ProcessorInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function preProcess(string $fixtureId, object $object): void
    {
        if ($object instanceof User) {
            $object->setPassword($this->passwordHasher->hashPassword($object, $object->getPassword()));
        }
    }

    public function postProcess(string $fixtureId, object $object): void
    {
        // No-op
    }
}
