<?php


namespace Test\Database\DynamoDb\Manager\ItemReadManager;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Database\DynamoDb\Exception\MarshalerException;
use AllCoinCore\Database\DynamoDb\ItemManager;
use AllCoinCore\Database\DynamoDb\MarshalerService;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\Result;
use Psr\Log\LoggerInterface;
use Test\TestCase;

class FetchAllTest extends TestCase
{
    private ItemManager $itemManager;

    private DynamoDbClient $dynamoDbClient;
    private MarshalerService $marshalerService;
    private LoggerInterface $logger;
    private string $tableName = 'table';

    public function setUp(): void
    {
        $this->dynamoDbClient = $this->createMock(DynamoDbClient::class);
        $this->marshalerService = $this->createMock(MarshalerService::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->itemManager = new ItemManager(
            $this->dynamoDbClient,
            $this->marshalerService,
            $this->logger,
            $this->tableName,
        );
    }

    public function testFetchAllWithMarshalValueErrorShouldThrowException(): void
    {
        $partitionKey = 'foo';

        $this->marshalerService->expects($this->once())
            ->method('marshalValue')
            ->with($partitionKey)
            ->willThrowException($this->createMock(MarshalerException::class));

        $this->logger->expects($this->once())
            ->method('error');

        $this->expectException(ItemReadException::class);

        $this->dynamoDbClient->expects($this->never())
            ->method('__call');
        $this->marshalerService->expects($this->never())->method('unmarshalItem');

        $this->itemManager->fetchAll($partitionKey);
    }

    public function testFetchAllWithDynamoDdQueryErrorShouldThrowException(): void
    {
        $partitionKey = 'foo';

        $marshaledPartitionKey = ['foo' => 'bar'];
        $this->marshalerService->expects($this->once())
            ->method('marshalValue')
            ->with($partitionKey)
            ->willReturn($marshaledPartitionKey);

        $query = [
            'TableName' => $this->tableName,
            'KeyConditionExpression' => ItemManager::PARTITION_KEY_NAME . " = :value",
            'ExpressionAttributeValues' => [':value' => $marshaledPartitionKey]
        ];

        $this->dynamoDbClient->expects($this->once())
            ->method('__call')
            ->with('query', [$query])
            ->willThrowException($this->createMock(DynamoDbException::class));

        $this->logger->expects($this->once())
            ->method('error');

        $this->expectException(ItemReadException::class);

        $this->marshalerService->expects($this->never())->method('unmarshalItem');

        $this->itemManager->fetchAll($partitionKey);
    }

    public function testFetchAllWithMarshalItemErrorShouldThrowException(): void
    {
        $partitionKey = 'foo';

        $marshaledPartitionKey = ['foo' => 'bar'];
        $this->marshalerService->expects($this->once())
            ->method('marshalValue')
            ->with($partitionKey)
            ->willReturn($marshaledPartitionKey);

        $query = [
            'TableName' => $this->tableName,
            'KeyConditionExpression' => ItemManager::PARTITION_KEY_NAME . " = :value",
            'ExpressionAttributeValues' => [':value' => $marshaledPartitionKey]
        ];

        $item = ['foo' => ''];
        $result = $this->createMock(Result::class);
        $result->expects($this->once())
            ->method('get')
            ->with('Items')
            ->willReturn([$item]);
        $this->dynamoDbClient->expects($this->once())
            ->method('__call')
            ->with('query', [$query])
            ->willReturn($result);

        $this->marshalerService->expects($this->once())
            ->method('unmarshalItem')
            ->with($item)
            ->willThrowException($this->createMock(MarshalerException::class));

        $this->expectException(ItemReadException::class);
        $this->logger->expects($this->once())->method('error');

        $this->itemManager->fetchAll($partitionKey);
    }

    /**
     * @throws ItemReadException
     */
    public function testFetchAllShouldBeOK(): void
    {
        $partitionKey = 'foo';

        $marshaledPartitionKey = ['foo' => 'bar'];
        $this->marshalerService->expects($this->once())
            ->method('marshalValue')
            ->with($partitionKey)
            ->willReturn($marshaledPartitionKey);

        $query = [
            'TableName' => $this->tableName,
            'KeyConditionExpression' => ItemManager::PARTITION_KEY_NAME . " = :value",
            'ExpressionAttributeValues' => [':value' => $marshaledPartitionKey]
        ];

        $item = ['foo' => ''];
        $result = $this->createMock(Result::class);
        $result->expects($this->once())
            ->method('get')
            ->with('Items')
            ->willReturn([$item]);
        $this->dynamoDbClient->expects($this->once())
            ->method('__call')
            ->with('query', [$query])
            ->willReturn($result);

        $this->marshalerService->expects($this->once())
            ->method('unmarshalItem')
            ->with($item)
            ->willReturn($item);

        $this->logger->expects($this->never())->method('error');

        $itemsExpected = [
            ['foo' => null]
        ];
        $items = $this->itemManager->fetchAll($partitionKey);

        $this->assertEquals($itemsExpected, $items);
    }
}
