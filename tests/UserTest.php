<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = (new User())
            ->setUsername('usertest')
            ->setEmail('usertest@test.com')
            ->setPassword('monpassword')
        ;

        $this->assertTrue($user->getUsername() === 'usertest');
        $this->assertTrue($user->getEmail() === 'usertest@test.com');
        $this->assertTrue($user->getPassword() === 'monpassword');
    }

    public function testIsFalse(): void
    {
        $user = (new User())
            ->setUsername('usertest')
            ->setEmail('usertest@test.com')
            ->setPassword('monpassword')
        ;

        $this->assertFalse($user->getUsername() === 'notusertest');
        $this->assertFalse($user->getEmail() === 'notusertest@test.com');
        $this->assertFalse($user->getPassword() === 'pasmonpassword');
    }

    public function testIsEmpty(): void
    {
        $user = new User();

        $this->assertEmpty($user->getUsername());
        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getPassword());
    }
}
