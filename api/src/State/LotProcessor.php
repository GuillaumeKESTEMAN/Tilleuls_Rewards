<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Doctrine\Common\State\RemoveProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Symfony\Validator\Exception\ValidationException;
use App\Entity\Lot;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LotProcessor implements ProcessorInterface
{
    public function __construct(private readonly RemoveProcessor $removeProcessor, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * @param $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return object|null
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): ?object
    {
        if ($data instanceof Lot && $context['operation']->getMethod() === 'DELETE') {
            $violations = $this->validator->validate($data, null, $operation->getValidationContext()["groups"]);
            if (0 !== \count($violations)) {
                throw new ValidationException($violations);
            }
        }

        return $this->removeProcessor->process($data, $operation, $uriVariables, $context);
    }
}
