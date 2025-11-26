<?php

declare(strict_types=1);

namespace VM\Shared\Infrastructure\Bus\Message;

use VM\Shared\Domain\Message\Message;
use VM\Shared\Domain\Message\MessageSerializer;
use VM\Shared\Domain\Service\Assertion\Assert;
use VM\Shared\Domain\Service\Assertion\AssertionFailedException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Stamp\ErrorDetailsStamp;
use Symfony\Component\Messenger\Stamp\NonSendableStampInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class MessengerMessageSerializer implements SerializerInterface
{
    private const STAMPS_KEY = 'stamps';

    private MessageSerializer $messageSerializer;
    private StampSerializer $stampSerializer;

    public function __construct(MessageSerializer $messageSerializer, StampSerializer $stampSerializer)
    {
        $this->messageSerializer = $messageSerializer;
        $this->stampSerializer = $stampSerializer;
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        try {
            Assert::that($encodedEnvelope)
                ->keyExists('headers')
                ->keyExists('body');

            Assert::isJsonString($encodedEnvelope['body']);

            $message = $this->messageSerializer->deserialize(json_decode($encodedEnvelope['body'], true));

            $stamps = [];
            if (array_key_exists(self::STAMPS_KEY, $encodedEnvelope['headers'])) {
                $stamps = $this->deserializeStamps($encodedEnvelope['headers'][self::STAMPS_KEY]);
            }

            return new Envelope($message, $stamps);
        } catch (AssertionFailedException $exception) {
            throw new MessageDecodingFailedException(
                sprintf(
                    'Error decoding message %s',
                    $exception->getMessage()
                )
            );
        }
    }

    public function encode(Envelope $envelope): array
    {
        $envelope = $envelope->withoutStampsOfType(NonSendableStampInterface::class);

        /* @var Message $message */
        $message = $envelope->getMessage();
        Assert::isInstanceOf($message, Message::class);

        $allStamps = [];
        $errorDetailsStamps = $envelope->all(ErrorDetailsStamp::class);
        if (!empty($errorDetailsStamps)) {
            $allStamps[] = end($errorDetailsStamps);
        }

        foreach ($envelope->withoutStampsOfType(ErrorDetailsStamp::class)->all() as $currentStamps) {
            $allStamps = array_merge($allStamps, $currentStamps);
        }

        return [
            'body' => json_encode($this->messageSerializer->serialize($message)),
            'headers' => [
                self::STAMPS_KEY => array_map(
                    fn ($stamp) => json_encode($this->stampSerializer->serialize($stamp)),
                    $allStamps
                )
            ]
        ];
    }

    private function deserializeStamps(array $encodedStamps): array
    {
        $decodedStamps = [];
        foreach ($encodedStamps as $stamp) {
            $decodedStamps[] = $this->stampSerializer->deserialize(json_decode($stamp, true));
        }

        return $decodedStamps;
    }
}
