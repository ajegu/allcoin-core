<?php


namespace Test\Process\Asset;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\DataMapper\AssetMapper;
use AllCoinCore\Dto\AssetResponseDto;
use AllCoinCore\Exception\RequiredParameterException;
use AllCoinCore\Model\Asset;
use AllCoinCore\Process\Asset\AssetGetProcess;
use AllCoinCore\Repository\AssetRepositoryInterface;
use Test\TestCase;

class AssetGetProcessTest extends TestCase
{
    private AssetGetProcess $assetGetProcess;

    private AssetRepositoryInterface $assetRepository;
    private AssetMapper $assetMapper;

    public function setUp(): void
    {
        $this->assetRepository = $this->createMock(AssetRepositoryInterface::class);
        $this->assetMapper = $this->createMock(AssetMapper::class);

        $this->assetGetProcess = new AssetGetProcess(
            $this->assetRepository,
            $this->assetMapper,
        );
    }

    /**
     * @throws ItemReadException
     */
    public function testHandleWithNoAssetIdShouldThrowException(): void
    {
        $params = [];

        $this->expectException(RequiredParameterException::class);

        $this->assetRepository->expects($this->never())->method('findOneById');
        $this->assetMapper->expects($this->never())->method('mapModelToResponseDto');

        $this->assetGetProcess->handle(null, $params);
    }

    /**
     * @throws ItemReadException
     */
    public function testHandleShouldBeOK(): void
    {
        $id = 'foo';
        $params = ['id' => $id];

        $asset = $this->createMock(Asset::class);
        $this->assetRepository->expects($this->once())
            ->method('findOneById')
            ->with($id)
            ->willReturn($asset);


        $responseDto = $this->createMock(AssetResponseDto::class);
        $this->assetMapper->expects($this->once())
            ->method('mapModelToResponseDto')
            ->with($asset)
            ->willReturn($responseDto);

        $this->assetGetProcess->handle(null, $params);
    }
}
