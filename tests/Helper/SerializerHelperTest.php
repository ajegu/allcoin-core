<?php


namespace Test\Helper;


use AllCoinCore\Exception\SerializerHelperException;
use AllCoinCore\Helper\SerializerHelper;
use Psr\Log\LoggerInterface;
use RuntimeException;
use stdClass;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Test\TestCase;

class SerializerHelperTest extends TestCase
{
    private SerializerHelper $serializerHelper;

    private SerializerInterface $serializer;
    private NormalizerInterface $normalizer;
    private LoggerInterface $logger;

    public function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->normalizer = $this->createMock(NormalizerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->serializerHelper = new SerializerHelper(
            $this->serializer,
            $this->normalizer,
            $this->logger
        );
    }

    public function testDeserializeWithSerializerErrorShouldThrowException(): void
    {
        $payload = [];
        $className = 'foo';

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(json_encode($payload), $className, SerializerHelper::DEFAULT_FORMAT)
            ->willThrowException($this->createMock(RuntimeException::class));

        $this->logger->expects($this->once())->method('error');

        $this->expectException(SerializerHelperException::class);

        $this->serializerHelper->deserialize($payload, $className);
    }

    public function testDeserializeShouldBeOK(): void
    {
        $payload = [];
        $className = 'foo';

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with(json_encode($payload), $className, SerializerHelper::DEFAULT_FORMAT)
            ->willReturn(new stdClass());

        $this->logger->expects($this->never())->method('error');

        $this->serializerHelper->deserialize($payload, $className);
    }

    public function testSerializeWithSerializerErrorShouldThrowException(): void
    {
        $object = new stdClass();

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($object, SerializerHelper::DEFAULT_FORMAT)
            ->willThrowException($this->createMock(RuntimeException::class));

        $this->logger->expects($this->once())->method('error');

        $this->expectException(SerializerHelperException::class);

        $this->serializerHelper->serialize($object);
    }

    public function testSerializeShouldBeOK(): void
    {
        $object = new stdClass();

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($object, SerializerHelper::DEFAULT_FORMAT)
            ->willReturn('');

        $this->logger->expects($this->never())->method('error');

        $this->serializerHelper->serialize($object);
    }

    public function testNormalizeWithNormalizerErrorShouldThrowException(): void
    {
        $object = new stdClass();

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($object)
            ->willThrowException($this->createMock(RuntimeException::class));

        $this->logger->expects($this->once())->method('error');

        $this->expectException(SerializerHelperException::class);

        $this->serializerHelper->normalize($object);
    }

    public function testNormalizeShouldBeOK(): void
    {
        $object = new stdClass();

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($object)
            ->willReturn([]);

        $this->logger->expects($this->never())->method('error');

        $this->serializerHelper->normalize($object);
    }
}