<?php


namespace Test\Repository;


use AllCoinCore\Database\DynamoDb\Exception\ItemDeleteException;
use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Database\DynamoDb\Exception\ItemSaveException;
use AllCoinCore\Database\DynamoDb\ItemManager;
use AllCoinCore\Database\DynamoDb\ItemManagerInterface;
use AllCoinCore\Model\AssetPair;
use AllCoinCore\Model\ClassMappingEnum;
use AllCoinCore\Repository\AssetPairRepository;
use AllCoinCore\Service\SerializerService;
use Test\TestCase;

class AssetPairRepositoryTest extends TestCase
{
    private AssetPairRepository $assetPairRepository;

    private ItemManagerInterface $itemManager;
    private SerializerService $serializerService;

    public function setUp(): void
    {
        $this->itemManager = $this->createMock(ItemManagerInterface::class);
        $this->serializerService = $this->createMock(SerializerService::class);

        $this->assetPairRepository = new AssetPairRepository(
            $this->itemManager,
            $this->serializerService
        );
    }

    /**
     * @throws ItemSaveException
     */
    public function testSaveShouldBeOK(): void
    {
        $assetId = 'bar';

        $assetPair = $this->createMock(AssetPair::class);
        $assetPairId = 'foo';
        $assetPair->expects($this->once())->method('getId')->willReturn($assetPairId);

        $item = [
            'asset' => []
        ];
        $this->serializerService->expects($this->once())
            ->method('normalizeModel')
            ->with($assetPair)
            ->willReturn($item);

        $itemExpected = [
            ItemManager::LSI_1 => $assetId
        ];

        $this->itemManager->expects($this->once())
            ->method('save')
            ->with($itemExpected, ClassMappingEnum::CLASS_MAPPING[AssetPair::class], $assetPairId);

        $this->assetPairRepository->save($assetPair, $assetId);
    }

    /**
     * @throws ItemReadException
     */
    public function testFindOneByIdShouldBeOK(): void
    {
        $assetPairId = 'foo';

        $item = [];
        $this->itemManager->expects($this->once())
            ->method('fetchOne')
            ->with(
                ClassMappingEnum::CLASS_MAPPING[AssetPair::class],
                $assetPairId
            )
            ->willReturn($item);

        $this->serializerService->expects($this->once())
            ->method('deserializeToModel')
            ->with(
                $item,
                AssetPair::class
            )
            ->willReturn($this->createMock(AssetPair::class));

        $this->assetPairRepository->findOneById($assetPairId);
    }

    /**
     * @throws ItemDeleteException
     */
    public function testDeleteShouldBeOK(): void
    {
        $assetPairId = 'foo';

        $this->itemManager->expects($this->once())
            ->method('delete')
            ->with(
                ClassMappingEnum::CLASS_MAPPING[AssetPair::class],
                $assetPairId
            );

        $this->assetPairRepository->delete($assetPairId);
    }

    /**
     * @throws ItemReadException
     */
    public function testFindAllByAssetIdShouldBeOK(): void
    {
        $assetId = 'foo';

        $item = [];
        $items = [$item];
        $this->itemManager->expects($this->once())
            ->method('fetchAllOnLSI')
            ->with(
                partitionKey: ClassMappingEnum::CLASS_MAPPING[AssetPair::class],
                lsiKeyName: ItemManager::LSI_1,
                lsiKey: $assetId,
            )
            ->willReturn($items);

        $this->serializerService->expects($this->once())
            ->method('deserializeToModel')
            ->with($item, AssetPair::class)
            ->willReturn($this->createMock(AssetPair::class));

        $this->assetPairRepository->findAllByAssetId($assetId);
    }

    /**
     * @throws ItemReadException
     */
    public function testFindAllShouldBeOK(): void
    {
        $item = [];
        $items = [$item];
        $this->itemManager->expects($this->once())
            ->method('fetchAll')
            ->with(
                partitionKey: ClassMappingEnum::CLASS_MAPPING[AssetPair::class]
            )
            ->willReturn($items);

        $this->serializerService->expects($this->once())
            ->method('deserializeToModel')
            ->with($item, AssetPair::class)
            ->willReturn($this->createMock(AssetPair::class));

        $this->assetPairRepository->findAll();
    }
}
