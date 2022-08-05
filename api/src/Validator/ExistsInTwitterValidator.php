<?php

declare(strict_types=1);

namespace App\Validator;

use Abraham\TwitterOAuth\TwitterOAuthException;
use App\Twitter\TwitterApi;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ExistsInTwitterValidator extends ConstraintValidator
{
    public function __construct(private TwitterApi $twitterApi)
    {
    }

    /**
     * @throws TwitterOAuthException
     */
    public function validate($username, Constraint $constraint): void
    {
        if (!$constraint instanceof ExistsInTwitter) {
            throw new UnexpectedTypeException($constraint, ExistsInTwitter::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $username || '' === $username) {
            return;
        }

        if (!\is_string($username)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($username, 'string');
            // separate multiple types using pipes
            // throw new UnexpectedValueException($username, 'string|int');
        }

        $user = $this->twitterApi->get('users/by/username/'.substr($username, 1));

        if (property_exists($user, 'errors')) {
            // the argument must be a string or an object implementing __toString()
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ username }}', $username)
                ->addViolation();
        }
    }
}
