<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ExistsInTwitter extends Constraint
{
    public string $message = 'Le compte Twitter "{{ username }}" n\'existe pas';
    public string $mode = 'strict';
}
