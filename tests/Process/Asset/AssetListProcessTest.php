<?php


namespace Test\Process\Asset;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\DataMapper\AssetMapper;
use AllCoinCore\Dto\ResponseDtoInterface;
use AllCoinCore\Model\Asset;
use AllCoinCore\Process\Asset\AssetListProcess;
use AllCoinCore\Repository\AssetRepositoryInterface;
use Test\TestCase;

class AssetListProcessTest extends TestCase
{
    private AssetListProcess $assetListProcess;

    private AssetRepositoryInterface $assetRepository;
    private AssetMapper $assetMapper;

    public function setUp(): void
    {
        $this->assetRepository = $this->createMock(AssetRepositoryInterface::class);
        $this->assetMapper = $this->createMock(AssetMapper::class);

        $this->assetListProcess = new AssetListProcess(
            $this->assetRepository,
            $this->assetMapper
        );
    }

    /**
     * @throws ItemReadException
     */
    public function testHandleShouldBeOK(): void
    {
        $asset = $this->createMock(Asset::class);

        $assets = [
            $asset
        ];
        $this->assetRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($assets);

        $this->assetMapper->expects($this->once())
            ->method('mapModelToResponseDto')
            ->with($asset)
            ->willReturn($this->createMock(ResponseDtoInterface::class));

        $this->assetListProcess->handle();
    }
}
