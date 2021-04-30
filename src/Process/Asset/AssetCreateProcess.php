<?php


namespace AllCoinCore\Process\Asset;


use AllCoinCore\Builder\AssetBuilder;
use AllCoinCore\Database\DynamoDb\Exception\ItemSaveException;
use AllCoinCore\DataMapper\AssetMapper;
use AllCoinCore\Dto\RequestDtoInterface;
use AllCoinCore\Dto\ResponseDtoInterface;
use AllCoinCore\Process\ProcessInterface;
use AllCoinCore\Repository\AssetRepositoryInterface;
use JetBrains\PhpStorm\Pure;

class AssetCreateProcess extends AbstractAssetProcess implements ProcessInterface
{
    #[Pure] public function __construct(
        protected AssetRepositoryInterface $assetRepository,
        protected AssetMapper $assetMapper,
        private AssetBuilder $assetBuilder
    )
    {
        parent::__construct(
            $assetRepository,
            $assetMapper
        );
    }

    /**
     * @param RequestDtoInterface|null $dto
     * @param array $params
     * @return ResponseDtoInterface
     * @throws ItemSaveException
     */
    public function handle(RequestDtoInterface $dto = null, array $params = []): ResponseDtoInterface
    {
        $asset = $this->assetBuilder->build($dto->getName());
        $this->assetRepository->save($asset);

        return $this->assetMapper->mapModelToResponseDto($asset);
    }

}
