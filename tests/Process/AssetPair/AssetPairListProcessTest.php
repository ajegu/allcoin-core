<?php


namespace Test\Process\AssetPair;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\DataMapper\AssetPairMapper;
use AllCoinCore\Dto\AssetPairRequestDto;
use AllCoinCore\Dto\AssetResponseDto;
use AllCoinCore\Dto\ListResponseDto;
use AllCoinCore\Exception\RequiredParameterException;
use AllCoinCore\Model\Asset;
use AllCoinCore\Model\AssetPair;
use AllCoinCore\Process\AssetPair\AssetPairListProcess;
use AllCoinCore\Repository\AssetPairRepositoryInterface;
use AllCoinCore\Repository\AssetRepositoryInterface;
use Test\TestCase;

class AssetPairListProcessTest extends TestCase
{
    private AssetPairListProcess $assetPairListProcess;

    private AssetRepositoryInterface $assetRepository;
    private AssetPairRepositoryInterface $assetPairRepository;
    private AssetPairMapper $assetPairMapper;

    public function setUp(): void
    {
        $this->assetRepository = $this->createMock(AssetRepositoryInterface::class);
        $this->assetPairRepository = $this->createMock(AssetPairRepositoryInterface::class);
        $this->assetPairMapper = $this->createMock(AssetPairMapper::class);

        $this->assetPairListProcess = new AssetPairListProcess(
            $this->assetRepository,
            $this->assetPairRepository,
            $this->assetPairMapper,
        );
    }

    /**
     * @throws ItemReadException
     */
    public function testHandleWithNoAssetIdShouldThrowException(): void
    {
        $requestDto = $this->createMock(AssetPairRequestDto::class);
        $params = [];

        $this->expectException(RequiredParameterException::class);

        $this->assetRepository->expects($this->never())->method('findOneById');
        $this->assetPairRepository->expects($this->never())->method('findAllByAssetId');
        $this->assetPairMapper->expects($this->never())->method('mapModelToResponseDto');

        $this->assetPairListProcess->handle($requestDto, $params);
    }

    /**
     * @throws ItemReadException
     */
    public function testHandleShouldBeOK(): void
    {
        $requestDto = $this->createMock(AssetPairRequestDto::class);
        $assetId = 'foo';
        $params = ['assetId' => $assetId];

        $asset = $this->createMock(Asset::class);
        $asset->expects($this->once())->method('getId')->willReturn($assetId);

        $this->assetRepository->expects($this->once())
            ->method('findOneById')
            ->with($assetId)
            ->willReturn($asset);

        $assetPair = $this->createMock(AssetPair::class);
        $assetPairs = [$assetPair];
        $this->assetPairRepository->expects($this->once())
            ->method('findAllByAssetId')
            ->with($assetId)
            ->willReturn($assetPairs);

        $assetResponseDto = $this->createMock(AssetResponseDto::class);
        $this->assetPairMapper->expects($this->once())
            ->method('mapModelToResponseDto')
            ->with($assetPair)
            ->willReturn($assetResponseDto);

        /** @var ListResponseDto $response */
        $response = $this->assetPairListProcess->handle($requestDto, $params);

        $this->assertInstanceOf(ListResponseDto::class, $response);
        $this->assertEquals([$assetResponseDto], $response->getData());
    }
}
