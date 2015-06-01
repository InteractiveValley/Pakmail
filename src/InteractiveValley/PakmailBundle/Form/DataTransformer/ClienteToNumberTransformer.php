<?php

namespace InteractiveValley\PakmailBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class ClienteToNumberTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($cliente)
    {
        if (null === $cliente) {
            return "";
        }

        return $cliente->getId();
    }

    /**
     * Transforms a string (number) to an object (cliente).
     *
     * @param  string $number
     *
     * @return Comentario|null
     *
     * @throws TransformationFailedException if object (cliente) is not found.
     */
    public function reverseTransform($number)
    {
        if (!$number) {
            return null;
        }

        $cliente = $this->om
            ->getRepository('PakmailBundle:Cliente')
            ->find($number);
        ;

        if (null === $cliente) {
            throw new TransformationFailedException(sprintf(
                'An Comentario with id "%s" does not exist!',
                $number
            ));
        }

        return $cliente;
    }
}