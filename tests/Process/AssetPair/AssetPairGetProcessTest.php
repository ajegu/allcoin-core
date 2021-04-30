<?php


namespace Test\Process\AssetPair;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\DataMapper\AssetPairMapper;
use AllCoinCore\Dto\AssetPairRequestDto;
use AllCoinCore\Dto\AssetPairResponseDto;
use AllCoinCore\Exception\RequiredParameterException;
use AllCoinCore\Model\Asset;
use AllCoinCore\Model\AssetPair;
use AllCoinCore\Process\AssetPair\AssetPairGetProcess;
use AllCoinCore\Repository\AssetPairRepositoryInterface;
use AllCoinCore\Repository\AssetRepositoryInterface;
use Test\TestCase;

class AssetPairGetProcessTest extends TestCase
{
    private AssetPairGetProcess $assetPairGetProcess;

    private AssetRepositoryInterface $assetRepository;
    private AssetPairRepositoryInterface $assetPairRepository;
    private AssetPairMapper $assetPairMapper;

    public function setUp(): void
    {
        $this->assetRepository = $this->createMock(AssetRepositoryInterface::class);
        $this->assetPairRepository = $this->createMock(AssetPairRepositoryInterface::class);
        $this->assetPairMapper = $this->createMock(AssetPairMapper::class);

        $this->assetPairGetProcess = new AssetPairGetProcess(
            $this->assetRepository,
            $this->assetPairRepository,
            $this->assetPairMapper,
        );
    }

    public function testHandleWithNoAssetIdShouldThrowException(): void
    {
        $requestDto = $this->createMock(AssetPairRequestDto::class);
        $params = [];

        $this->expectException(RequiredParameterException::class);

        $this->assetRepository->expects($this->never())->method('findOneById');
        $this->assetPairRepository->expects($this->never())->method('findOneById');
        $this->assetPairMapper->expects($this->never())->method('mapModelToResponseDto');

        $this->assetPairGetProcess->handle($requestDto, $params);
    }

    public function testHandleWithNoAssetPairIdShouldThrowException(): void
    {
        $requestDto = $this->createMock(AssetPairRequestDto::class);
        $assetId = 'foo';
        $params = ['assetId' => $assetId];

        $this->assetRepository->expects($this->once())
            ->method('findOneById')
            ->with($assetId)
            ->willReturn($this->createMock(Asset::class));

        $this->expectException(RequiredParameterException::class);

        $this->assetPairRepository->expects($this->never())->method('findOneById');
        $this->assetPairMapper->expects($this->never())->method('mapModelToResponseDto');

        $this->assetPairGetProcess->handle($requestDto, $params);
    }

    /**
     * @throws ItemReadException
     */
    public function testHandleShouldBeOK(): void
    {
        $requestDto = $this->createMock(AssetPairRequestDto::class);
        $assetId = 'foo';
        $id = 'bar';
        $params = ['assetId' => $assetId, 'id' => $id];

        $this->assetRepository->expects($this->once())
            ->method('findOneById')
            ->with($assetId)
            ->willReturn($this->createMock(Asset::class));

        $assetPair = $this->createMock(AssetPair::class);
        $this->assetPairRepository->expects($this->once())
            ->method('findOneById')
            ->with($id)
            ->willReturn($assetPair);

        $this->assetPairMapper->expects($this->once())
            ->method('mapModelToResponseDto')
            ->with($assetPair)
            ->willReturn($this->createMock(AssetPairResponseDto::class));

        $this->assetPairGetProcess->handle($requestDto, $params);
    }
}
