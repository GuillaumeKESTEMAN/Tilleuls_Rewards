<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ExistsInTwitter extends Constraint
{
    public string $message = 'The Twitter account "{{ username }}" does not exists.';
    public string $mode = 'strict';
}
