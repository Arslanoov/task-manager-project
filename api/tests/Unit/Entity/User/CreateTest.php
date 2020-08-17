<?php

declare(strict_types=1);

namespace Tests\Unit\Entity\User;

use Domain\User\Entity\User\Email;
use Domain\User\Entity\User\Id;
use Domain\User\Entity\User\Login;
use Domain\User\Entity\User\Password;
use Domain\User\Entity\User\Status;
use Domain\User\Entity\User\User;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = User::signUpByEmail(
            Id::uuid4(),
            $login = new Login('some login'),
            $email = new Email('app@test.app'),
            $password = new Password('Some password')
        );

        $this->assertEquals($user->getLogin(), $login);
        $this->assertTrue($user->getLogin()->isEqual($login));

        $this->assertEquals($user->getEmail(), $email);
        $this->assertTrue($user->getEmail()->isEqual($email));

        $this->assertEquals($password, $user->getPassword());
        $this->assertTrue($user->getPassword()->isEqual($password));
    }

    public function testEmptyLogin(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User login required');

        User::signUpByEmail(
            Id::uuid4(),
            $login = new Login(''),
            $email = new Email('app@test.app'),
            new Password('Some password')
        );
    }

    public function testTooLongLogin(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User login must be between 4 and 32 chars length');

        User::signUpByEmail(
            Id::uuid4(),
            $login = new Login('sssssssssssssssssssssssssssssssssssssssssssssssss'),
            $email = new Email('app@test.app'),
            new Password('Some password')
        );
    }

    public function testTooShortLogin(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User login must be between 4 and 32 chars length');

        User::signUpByEmail(
            Id::uuid4(),
            $login = new Login('s'),
            $email = new Email('app@test.app'),
            new Password('Some password')
        );
    }

    public function testEmptyEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User email required');

        User::signUpByEmail(
            Id::uuid4(),
            $login = new Login('login'),
            $email = new Email(''),
            new Password('Some password')
        );
    }

    public function testInvalidEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Incorrect email');

        User::signUpByEmail(
            Id::uuid4(),
            $login = new Login('login'),
            $email = new Email('email'),
            new Password('Some password')
        );
    }

    public function testTooLongEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User email must be between 5 and 32 chars length');

        User::signUpByEmail(
            Id::uuid4(),
            $login = new Login('login'),
            $email = new Email('sssssssssssssssssssssssssssssssssssssssssssssssss'),
            new Password('Some password')
        );
    }

    public function testTooShortEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User email must be between 5 and 32 chars length');

        User::signUpByEmail(
            Id::uuid4(),
            $login = new Login('login'),
            $email = new Email('s'),
            new Password('Some password')
        );
    }
}