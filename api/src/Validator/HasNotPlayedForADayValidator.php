<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Player;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class HasNotPlayedForADayValidator extends ConstraintValidator
{
    public function validate($player, Constraint $constraint): void
    {
        if (!$constraint instanceof HasNotPlayedForADay) {
            throw new UnexpectedTypeException($constraint, HasNotPlayedForADay::class);
        }

        if (!$player instanceof Player) {
            throw new UnexpectedTypeException($player, Player::class);
        }

        if (null !== $player->getLastPlayDate() && date_diff($player->getLastPlayDate(), new \DateTime())->d < 1) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ username }}', $player->getUsername())
                ->addViolation();
        }
    }
}
