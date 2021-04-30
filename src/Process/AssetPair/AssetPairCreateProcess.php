<?php


namespace AllCoinCore\Process\AssetPair;


use AllCoinCore\Builder\AssetPairBuilder;
use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Database\DynamoDb\Exception\ItemSaveException;
use AllCoinCore\DataMapper\AssetPairMapper;
use AllCoinCore\Dto\RequestDtoInterface;
use AllCoinCore\Dto\ResponseDtoInterface;
use AllCoinCore\Process\ProcessInterface;
use AllCoinCore\Repository\AssetPairRepositoryInterface;
use AllCoinCore\Repository\AssetRepositoryInterface;
use JetBrains\PhpStorm\Pure;

class AssetPairCreateProcess extends AbstractAssetPairProcess implements ProcessInterface
{
    #[Pure] public function __construct(
        protected AssetRepositoryInterface $assetRepository,
        protected AssetPairRepositoryInterface $assetPairRepository,
        protected AssetPairMapper $assetPairMapper,
        private AssetPairBuilder $assetPairBuilder
    )
    {
        parent::__construct(
            $assetRepository,
            $assetPairRepository,
            $assetPairMapper,
        );
    }

    /**
     * @param RequestDtoInterface|null $dto
     * @param array $params
     * @return ResponseDtoInterface|null
     * @throws ItemSaveException
     * @throws ItemReadException
     */
    public function handle(RequestDtoInterface $dto = null, array $params = []): ?ResponseDtoInterface
    {
        $asset = $this->assetRepository->findOneById(
            $this->getAssetId($params)
        );

        $assetPair = $this->assetPairBuilder->build($dto->getName());

        $this->assetPairRepository->save($assetPair, $asset->getId());

        return $this->assetPairMapper->mapModelToResponseDto($assetPair);
    }

}
