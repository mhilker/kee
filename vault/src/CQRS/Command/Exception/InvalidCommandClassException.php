<?php

declare(strict_types=1);

namespace App\CQRS\Command\Exception;

use InvalidArgumentException;

final class InvalidCommandClassException extends InvalidArgumentException
{

}
