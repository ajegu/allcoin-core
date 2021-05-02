<?php


namespace AllCoinCore\Helper;


use AllCoinCore\Exception\SerializerHelperException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use RuntimeException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerHelper
{
    const DEFAULT_FORMAT = 'json';

    public function __construct(
        private SerializerInterface $serializer,
        private NormalizerInterface $normalizer,
        private LoggerInterface $logger
    )
    {
    }

    /**
     * @param array $payload
     * @param string $className
     * @return object
     */
    public function deserialize(array $payload, string $className): object
    {
        try {
            return $this->serializer->deserialize(json_encode($payload), $className, self::DEFAULT_FORMAT);
        } catch (RuntimeException $exception) {
            $this->logger->error('Cannot deserialize payload', [
                'payload' => $payload,
                'exception' => $exception->getMessage()
            ]);
            throw new SerializerHelperException();
        }
    }

    /**
     * @param object $object
     * @return string
     */
    public function serialize(object $object): string
    {
        try {
            return $this->serializer->serialize($object, self::DEFAULT_FORMAT);
        } catch (RuntimeException $exception) {
            $this->logger->error('Cannot serialize object', [
                'class' => get_class($object),
                'exception' => $exception->getMessage()
            ]);
            throw new SerializerHelperException();
        }
    }

    /**
     * @param object $object
     * @return array
     */
    public function normalize(object $object): array
    {
        try {
            return $this->normalizer->normalize($object);
        } catch (RuntimeException | ExceptionInterface $exception) {
            $this->logger->error('Cannot normalize object', [
                'class' => get_class($object),
                'exception' => $exception->getMessage()
            ]);
            throw new SerializerHelperException();
        }
    }
}