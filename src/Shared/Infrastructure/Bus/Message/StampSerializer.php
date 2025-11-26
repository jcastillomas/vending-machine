<?php

declare(strict_types=1);

namespace VM\Shared\Infrastructure\Bus\Message;

use Symfony\Component\Messenger\Stamp\StampInterface;
use Symfony\Component\Messenger\Stamp\ErrorDetailsStamp;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ProblemNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final class StampSerializer
{
    private SerializerInterface $errorSerializer;

    public function __construct(private readonly SerializerInterface $serializer)
    {
        $this->errorSerializer = new Serializer([new ObjectNormalizer(), new ProblemNormalizer(true)], [new JsonEncoder()]);
    }

    public function deserialize(array $stamp): StampInterface
    {
        if ($stamp['class_name'] !== 'Symfony\Component\Messenger\Stamp\ErrorDetailsStamp') {
            return $this->serializer->deserialize($stamp['fields'], $stamp['class_name'], JsonEncoder::FORMAT);
        }

        $fields = json_decode($stamp['fields'], true);

        return new ErrorDetailsStamp(
            $fields['exceptionClass'],
            $fields['exceptionCode'],
            $fields['exceptionMessage'],
            $fields['flattenException']
        );
    }

    public function serialize(StampInterface $stamp): array
    {
        $serializedStamp =  [
            'class_name' => get_class($stamp),
        ];

        if (!$stamp instanceof ErrorDetailsStamp) {
            $serializedStamp['fields'] = $this->serializer->serialize($stamp, JsonEncoder::FORMAT);
        } else {
            $errorStamp = new ErrorDetailsStamp(
                $stamp->getExceptionClass(),
                $stamp->getExceptionCode(),
                $stamp->getExceptionMessage(),
                $stamp->getFlattenException()
            );
            $serializedStamp['fields'] = $this->errorSerializer->serialize($errorStamp, JsonEncoder::FORMAT);
        }

        return $serializedStamp;
    }
}
