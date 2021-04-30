<?php


namespace Test\Process\Asset;


use AllCoinCore\Builder\AssetBuilder;
use AllCoinCore\Database\DynamoDb\Exception\ItemSaveException;
use AllCoinCore\DataMapper\AssetMapper;
use AllCoinCore\Dto\AssetRequestDto;
use AllCoinCore\Model\Asset;
use AllCoinCore\Process\Asset\AssetCreateProcess;
use AllCoinCore\Repository\AssetRepositoryInterface;
use Psr\Log\LoggerInterface;
use Test\TestCase;

class AssetCreateProcessTest extends TestCase
{
    private AssetCreateProcess $assetCreateProcess;

    private AssetRepositoryInterface $assetRepository;
    private LoggerInterface $logger;
    private AssetMapper $assetMapper;
    private AssetBuilder $assetBuilder;

    public function setUp(): void
    {
        $this->assetRepository = $this->createMock(AssetRepositoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->assetMapper = $this->createMock(AssetMapper::class);
        $this->assetBuilder = $this->createMock(AssetBuilder::class);

        $this->assetCreateProcess = new AssetCreateProcess(
            $this->assetRepository,
            $this->assetMapper,
            $this->assetBuilder,
        );
    }

    /**
     * @throws ItemSaveException
     */
    public function testHandleShouldBeOK(): void
    {
        $dto = $this->createMock(AssetRequestDto::class);
        $dtoName = 'foo';
        $dto->expects($this->once())->method('getName')->willReturn($dtoName);

        $asset = $this->createMock(Asset::class);
        $this->assetBuilder->expects($this->once())
            ->method('build')
            ->with($dtoName)
            ->willReturn($asset);

        $this->assetRepository->expects($this->once())
            ->method('save')
            ->with($asset);

        $this->logger->expects($this->never())->method('error');

        $this->assetMapper->expects($this->once())
            ->method('mapModelToResponseDto')
            ->with($asset);

        $this->assetCreateProcess->handle($dto);
    }
}
