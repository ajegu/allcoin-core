<?php


namespace Test\Process\AssetPair;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Database\DynamoDb\Exception\ItemSaveException;
use AllCoinCore\DataMapper\AssetPairMapper;
use AllCoinCore\Dto\AssetPairRequestDto;
use AllCoinCore\Dto\AssetResponseDto;
use AllCoinCore\Exception\RequiredParameterException;
use AllCoinCore\Model\Asset;
use AllCoinCore\Model\AssetPair;
use AllCoinCore\Process\AssetPair\AssetPairUpdateProcess;
use AllCoinCore\Repository\AssetPairRepositoryInterface;
use AllCoinCore\Repository\AssetRepositoryInterface;
use AllCoinCore\Service\DateTimeService;
use DateTime;
use Test\TestCase;

class AssetPairUpdateProcessTest extends TestCase
{
    private AssetPairUpdateProcess $assetPairUpdateProcess;

    private AssetRepositoryInterface $assetRepository;
    private AssetPairRepositoryInterface $assetPairRepository;
    private DateTimeService $dateTimeService;
    private AssetPairMapper $assetPairMapper;

    public function setUp(): void
    {
        $this->assetRepository = $this->createMock(AssetRepositoryInterface::class);
        $this->assetPairRepository = $this->createMock(AssetPairRepositoryInterface::class);
        $this->dateTimeService = $this->createMock(DateTimeService::class);
        $this->assetPairMapper = $this->createMock(AssetPairMapper::class);

        $this->assetPairUpdateProcess = new AssetPairUpdateProcess(
            $this->assetRepository,
            $this->assetPairRepository,
            $this->dateTimeService,
            $this->assetPairMapper,
        );
    }

    /**
     * @throws ItemReadException
     * @throws ItemSaveException
     */
    public function testHandleWithNoAssetIdShouldThrowException(): void
    {
        $requestDto = $this->createMock(AssetPairRequestDto::class);
        $params = [];

        $this->expectException(RequiredParameterException::class);

        $this->assetRepository->expects($this->never())->method('findOneById');
        $this->dateTimeService->expects($this->never())->method('now');
        $this->assetPairRepository->expects($this->never())->method('save');
        $this->assetPairMapper->expects($this->never())->method('mapModelToResponseDto');

        $this->assetPairUpdateProcess->handle($requestDto, $params);
    }

    /**
     * @throws ItemReadException
     * @throws ItemSaveException
     */
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
        $this->dateTimeService->expects($this->never())->method('now');
        $this->assetPairRepository->expects($this->never())->method('save');
        $this->assetPairMapper->expects($this->never())->method('mapModelToResponseDto');

        $this->assetPairUpdateProcess->handle($requestDto, $params);
    }

    /**
     * @throws ItemReadException
     * @throws ItemSaveException
     */
    public function testHandleShouldBeOK(): void
    {
        $requestDto = $this->createMock(AssetPairRequestDto::class);
        $name = 'bar';
        $requestDto->expects($this->once())->method('getName')->willReturn($name);

        $assetId = 'foo';
        $id = 'bar';
        $params = ['assetId' => $assetId, 'id' => $id];

        $asset = $this->createMock(Asset::class);
        $asset->expects($this->once())->method('getId')->willReturn($assetId);

        $this->assetRepository->expects($this->once())
            ->method('findOneById')
            ->with($assetId)
            ->willReturn($asset);

        $assetPair = $this->createMock(AssetPair::class);
        $this->assetPairRepository->expects($this->once())
            ->method('findOneById')
            ->with($id)
            ->willReturn($assetPair);

        $now = new DateTime();
        $this->dateTimeService->expects($this->once())
            ->method('now')
            ->willReturn($now);

        $assetPair->expects($this->once())->method('setName')->with($name);
        $assetPair->expects($this->once())->method('setUpdatedAt')->with($now);

        $this->assetPairRepository->expects($this->once())
            ->method('save')
            ->with($assetPair, $assetId);

        $this->assetPairMapper->expects($this->once())
            ->method('mapModelToResponseDto')
            ->with($assetPair)
            ->willReturn($this->createMock(AssetResponseDto::class));

        $this->assetPairUpdateProcess->handle($requestDto, $params);
    }
}
