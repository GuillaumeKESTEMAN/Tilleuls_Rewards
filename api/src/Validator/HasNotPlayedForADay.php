<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class HasNotPlayedForADay extends Constraint
{
    public string $message = 'The player {{username}} has an existing game less than 1 day old';
    public string $mode = 'strict';
}
