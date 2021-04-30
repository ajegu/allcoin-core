<?php


namespace Test\Process\Asset;


use AllCoinCore\Database\DynamoDb\Exception\ItemDeleteException;
use AllCoinCore\DataMapper\AssetMapper;
use AllCoinCore\Exception\RequiredParameterException;
use AllCoinCore\Process\Asset\AssetDeleteProcess;
use AllCoinCore\Repository\AssetRepositoryInterface;
use Test\TestCase;

class AssetDeleteProcessTest extends TestCase
{
    private AssetDeleteProcess $assetDeleteProcess;

    private AssetRepositoryInterface $assetRepository;

    public function setUp(): void
    {
        $this->assetRepository = $this->createMock(AssetRepositoryInterface::class);

        $this->assetDeleteProcess = new AssetDeleteProcess(
            $this->assetRepository,
            $this->createMock(AssetMapper::class),
        );
    }

    /**
     * @throws ItemDeleteException
     */
    public function testHandleWithNoAssetIdShouldThrowException(): void
    {
        $params = [];

        $this->expectException(RequiredParameterException::class);

        $this->assetRepository->expects($this->never())->method('delete');

        $this->assetDeleteProcess->handle(null, $params);
    }

    /**
     * @throws ItemDeleteException
     */
    public function testHandleShouldBeOK(): void
    {
        $assetId = 'foo';
        $params = ['id' => $assetId];

        $this->assetRepository->expects($this->once())
            ->method('delete')
            ->with($assetId);

        $this->assetDeleteProcess->handle(null, $params);
    }
}
