<?php


namespace Test\Database\DynamoDb\Manager\ItemReadManager;


use AllCoinCore\Database\DynamoDb\Exception\ItemNotFoundException;
use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Database\DynamoDb\Exception\MarshalerException;
use AllCoinCore\Database\DynamoDb\ItemManager;
use AllCoinCore\Database\DynamoDb\MarshalerService;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\Result;
use Psr\Log\LoggerInterface;
use Test\TestCase;

class FetchOneTest extends TestCase
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

    public function testFetchOneWithMarshalValueErrorShouldThrowException(): void
    {
        $partitionKey = 'foo';
        $sortKey = 'foo';

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

        $this->itemManager->fetchOne($partitionKey, $sortKey);
    }

    public function testFetchOneWithDynamoDdQueryErrorShouldThrowException(): void
    {
        $partitionKey = 'foo';
        $sortKey = 'bar';

        $marshaledPartitionKey = ['foo' => 'bar'];
        $marshaledSortKey = ['bar' => 'foo'];
        $this->marshalerService->expects($this->exactly(2))
            ->method('marshalValue')
            ->withConsecutive([$partitionKey], [$sortKey])
            ->willReturn($marshaledPartitionKey, $marshaledSortKey);

        $query = [
            'TableName' => $this->tableName,
            'KeyConditionExpression' => ItemManager::PARTITION_KEY_NAME . ' = :partitionKeyValue and ' . ItemManager::SORT_KEY_NAME . ' = :sortKeyValue',
            'ExpressionAttributeValues' => [
                ':partitionKeyValue' => $marshaledPartitionKey,
                ':sortKeyValue' => $marshaledSortKey
            ]
        ];

        $this->dynamoDbClient->expects($this->once())
            ->method('__call')
            ->with('query', [$query])
            ->willThrowException($this->createMock(DynamoDbException::class));

        $this->logger->expects($this->once())
            ->method('error');

        $this->expectException(ItemReadException::class);

        $this->marshalerService->expects($this->never())->method('unmarshalItem');

        $this->itemManager->fetchOne($partitionKey, $sortKey);
    }

    /**
     * @throws ItemReadException
     */
    public function testFetchOneWithNoItemShouldThrowException(): void
    {
        $partitionKey = 'foo';
        $sortKey = 'bar';

        $marshaledPartitionKey = ['foo' => 'bar'];
        $marshaledSortKey = ['bar' => 'foo'];
        $this->marshalerService->expects($this->exactly(2))
            ->method('marshalValue')
            ->withConsecutive([$partitionKey], [$sortKey])
            ->willReturn($marshaledPartitionKey, $marshaledSortKey);

        $query = [
            'TableName' => $this->tableName,
            'KeyConditionExpression' => ItemManager::PARTITION_KEY_NAME . ' = :partitionKeyValue and ' . ItemManager::SORT_KEY_NAME . ' = :sortKeyValue',
            'ExpressionAttributeValues' => [
                ':partitionKeyValue' => $marshaledPartitionKey,
                ':sortKeyValue' => $marshaledSortKey
            ]
        ];

        $result = $this->createMock(Result::class);
        $result->expects($this->once())
            ->method('get')
            ->with('Items')
            ->willReturn([]);
        $this->dynamoDbClient->expects($this->once())
            ->method('__call')
            ->with('query', [$query])
            ->willReturn($result);

        $this->expectException(ItemNotFoundException::class);

        $this->logger->expects($this->never())->method('error');
        $this->marshalerService->expects($this->never())->method('unmarshalItem');

        $this->itemManager->fetchOne($partitionKey, $sortKey);
    }

    public function testFetchOneWithItemCountErrorShouldThrowException(): void
    {
        $partitionKey = 'foo';
        $sortKey = 'bar';

        $marshaledPartitionKey = ['foo' => 'bar'];
        $marshaledSortKey = ['bar' => 'foo'];
        $this->marshalerService->expects($this->exactly(2))
            ->method('marshalValue')
            ->withConsecutive([$partitionKey], [$sortKey])
            ->willReturn($marshaledPartitionKey, $marshaledSortKey);

        $query = [
            'TableName' => $this->tableName,
            'KeyConditionExpression' => ItemManager::PARTITION_KEY_NAME . ' = :partitionKeyValue and ' . ItemManager::SORT_KEY_NAME . ' = :sortKeyValue',
            'ExpressionAttributeValues' => [
                ':partitionKeyValue' => $marshaledPartitionKey,
                ':sortKeyValue' => $marshaledSortKey
            ]
        ];

        $item = ['foo' => ''];
        $result = $this->createMock(Result::class);
        $result->expects($this->once())
            ->method('get')
            ->with('Items')
            ->willReturn([$item, $item]);
        $this->dynamoDbClient->expects($this->once())
            ->method('__call')
            ->with('query', [$query])
            ->willReturn($result);

        $this->logger->expects($this->once())
            ->method('error');

        $this->expectException(ItemReadException::class);

        $this->marshalerService->expects($this->never())->method('unmarshalItem');

        $this->itemManager->fetchOne($partitionKey, $sortKey);
    }

    public function testFetchOneWithMarshalItemErrorShouldThrowException(): void
    {
        $partitionKey = 'foo';
        $sortKey = 'bar';

        $marshaledPartitionKey = ['foo' => 'bar'];
        $marshaledSortKey = ['bar' => 'foo'];
        $this->marshalerService->expects($this->exactly(2))
            ->method('marshalValue')
            ->withConsecutive([$partitionKey], [$sortKey])
            ->willReturn($marshaledPartitionKey, $marshaledSortKey);

        $query = [
            'TableName' => $this->tableName,
            'KeyConditionExpression' => ItemManager::PARTITION_KEY_NAME . ' = :partitionKeyValue and ' . ItemManager::SORT_KEY_NAME . ' = :sortKeyValue',
            'ExpressionAttributeValues' => [
                ':partitionKeyValue' => $marshaledPartitionKey,
                ':sortKeyValue' => $marshaledSortKey
            ]
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

        $this->logger->expects($this->once())
            ->method('error');

        $this->expectException(ItemReadException::class);

        $this->itemManager->fetchOne($partitionKey, $sortKey);
    }

    /**
     * @throws ItemReadException
     */
    public function testFetchOneShouldBeOK(): void
    {
        $partitionKey = 'foo';
        $sortKey = 'bar';

        $marshaledPartitionKey = ['foo' => 'bar'];
        $marshaledSortKey = ['bar' => 'foo'];
        $this->marshalerService->expects($this->exactly(2))
            ->method('marshalValue')
            ->withConsecutive([$partitionKey], [$sortKey])
            ->willReturn($marshaledPartitionKey, $marshaledSortKey);

        $query = [
            'TableName' => $this->tableName,
            'KeyConditionExpression' => ItemManager::PARTITION_KEY_NAME . ' = :partitionKeyValue and ' . ItemManager::SORT_KEY_NAME . ' = :sortKeyValue',
            'ExpressionAttributeValues' => [
                ':partitionKeyValue' => $marshaledPartitionKey,
                ':sortKeyValue' => $marshaledSortKey
            ]
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

        $this->logger->expects($this->never())
            ->method('error');

        $this->itemManager->fetchOne($partitionKey, $sortKey);
    }

}
