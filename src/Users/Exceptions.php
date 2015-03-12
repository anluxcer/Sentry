<?php

/*
 * This file is part of Sentry.
 *
 * (c) Cartalyst LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cartalyst\Sentry\Users;

class LoginRequiredException extends \UnexpectedValueException
{
}
class PasswordRequiredException extends \UnexpectedValueException
{
}
class UserAlreadyActivatedException extends \RuntimeException
{
}
class UserNotFoundException extends \OutOfBoundsException
{
}
class UserNotActivatedException extends \RuntimeException
{
}
class UserExistsException extends \UnexpectedValueException
{
}
class WrongPasswordException extends UserNotFoundException
{
}
