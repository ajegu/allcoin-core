<?php


namespace Test\Process\AssetPair;


use AllCoinCore\Database\DynamoDb\Exception\ItemDeleteException;
use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\DataMapper\AssetPairMapper;
use AllCoinCore\Exception\RequiredParameterException;
use AllCoinCore\Model\Asset;
use AllCoinCore\Model\AssetPair;
use AllCoinCore\Process\AssetPair\AssetPairDeleteProcess;
use AllCoinCore\Repository\AssetPairRepositoryInterface;
use AllCoinCore\Repository\AssetRepositoryInterface;
use Test\TestCase;

class AssetPairDeleteProcessTest extends TestCase
{
    private AssetPairDeleteProcess $assetPairDeleteProcess;

    private AssetRepositoryInterface $assetRepository;
    private AssetPairRepositoryInterface $assetPairRepository;
    private AssetPairMapper $assetPairMapper;

    public function setUp(): void
    {
        $this->assetRepository = $this->createMock(AssetRepositoryInterface::class);
        $this->assetPairRepository = $this->createMock(AssetPairRepositoryInterface::class);
        $this->assetPairMapper = $this->createMock(AssetPairMapper::class);

        $this->assetPairDeleteProcess = new AssetPairDeleteProcess(
            $this->assetRepository,
            $this->assetPairRepository,
            $this->assetPairMapper,
        );
    }

    public function testHandleWithNoAssetIdShouldThrowException(): void
    {
        $params = [];

        $this->expectException(RequiredParameterException::class);

        $this->assetRepository->expects($this->never())->method('findOneById');
        $this->assetPairRepository->expects($this->never())->method('findOneById');
        $this->assetPairRepository->expects($this->never())->method('delete');

        $this->assetPairDeleteProcess->handle(null, $params);
    }

    public function testHandleWithNoAssetPairIdShouldThrowException(): void
    {
        $assetId = 'foo';
        $params = ['assetId' => $assetId];

        $this->assetRepository->expects($this->once())
            ->method('findOneById')
            ->with($assetId)
            ->willReturn($this->createMock(Asset::class));

        $this->expectException(RequiredParameterException::class);

        $this->assetPairRepository->expects($this->never())->method('findOneById');
        $this->assetPairRepository->expects($this->never())->method('delete');

        $this->assetPairDeleteProcess->handle(null, $params);
    }

    /**
     * @throws ItemDeleteException
     * @throws ItemReadException
     */
    public function testHandleShouldBeOK(): void
    {
        $assetId = 'foo';
        $id = 'bar';
        $params = ['assetId' => $assetId, 'id' => $id];

        $this->assetRepository->expects($this->once())
            ->method('findOneById')
            ->with($assetId)
            ->willReturn($this->createMock(Asset::class));

        $assetPair = $this->createMock(AssetPair::class);
        $assetPair->expects($this->once())->method('getId')->willReturn($id);
        $this->assetPairRepository->expects($this->once())
            ->method('findOneById')
            ->with($id)
            ->willReturn($assetPair);

        $this->assetPairRepository->expects($this->once())
            ->method('delete')
            ->with($id);

        $this->assetPairDeleteProcess->handle(null, $params);
    }
}
