<?php

declare(strict_types=1);

namespace App\Validator;

use Abraham\TwitterOAuth\TwitterOAuthException;
use App\Twitter\TwitterApi;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class ExistsInTwitterValidator extends ConstraintValidator
{
    public function __construct(private readonly TwitterApi $twitterApi)
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

        if (null === $username || '' === $username) {
            return;
        }

        if (!\is_string($username)) {
            throw new UnexpectedValueException($username, 'string');
        }

        $user = $this->twitterApi->get('users/by/username/'.str_replace('@', '', $username));

        if (property_exists($user, 'errors')) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ username }}', $username)
                ->addViolation();
        }
    }
}
