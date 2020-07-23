<?php

declare(strict_types=1);

namespace Tests\Unit\Entity\User;

use Domain\User\Entity\Email;
use Domain\User\Entity\Login;
use Domain\User\Entity\Status;
use Domain\User\Entity\User;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = User::signUpByEmail(
            $login = new Login('some login'),
            $email = new Email('app@test.app')
        );

        $this->assertEquals($user->getLogin(), $login);
        $this->assertTrue($user->getLogin()->isEqual($login));

        $this->assertEquals($user->getEmail(), $email);
        $this->assertTrue($user->getEmail()->isEqual($email));

        $this->assertEquals($user->getStatus(), Status::draft());
        $this->assertTrue($user->getStatus()->isEqual(Status::draft()));
    }

    public function testEmptyLogin(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a non-empty value. Got: ""');

        User::signUpByEmail(
            $login = new Login(''),
            $email = new Email('app@test.app')
        );
    }

    public function testTooLongLogin(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a value to contain between 4 and 32 characters. Got: "sssssssssssssssssssssssssssssssssssssssssssssssss"');

        User::signUpByEmail(
            $login = new Login('sssssssssssssssssssssssssssssssssssssssssssssssss'),
            $email = new Email('app@test.app')
        );
    }

    public function testTooShortLogin(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a value to contain between 4 and 32 characters. Got: "s"');

        User::signUpByEmail(
            $login = new Login('s'),
            $email = new Email('app@test.app')
        );
    }

    public function testEmptyEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a non-empty value. Got: ""');

        User::signUpByEmail(
            $login = new Login('login'),
            $email = new Email('')
        );
    }

    public function testInvalidEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a value to be a valid e-mail address. Got: "email"');

        User::signUpByEmail(
            $login = new Login('login'),
            $email = new Email('email')
        );
    }

    public function testTooLongEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a value to contain between 5 and 32 characters. Got: "sssssssssssssssssssssssssssssssssssssssssssssssss"');

        User::signUpByEmail(
            $login = new Login('login'),
            $email = new Email('sssssssssssssssssssssssssssssssssssssssssssssssss')
        );
    }

    public function testTooShortEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a value to contain between 5 and 32 characters. Got: "s"');

        User::signUpByEmail(
            $login = new Login('login'),
            $email = new Email('s')
        );
    }
}