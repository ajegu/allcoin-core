<?php


namespace AllCoinCore\Process\AssetPair;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Database\DynamoDb\Exception\ItemSaveException;
use AllCoinCore\DataMapper\AssetPairMapper;
use AllCoinCore\Dto\RequestDtoInterface;
use AllCoinCore\Dto\ResponseDtoInterface;
use AllCoinCore\Process\ProcessInterface;
use AllCoinCore\Repository\AssetPairRepositoryInterface;
use AllCoinCore\Repository\AssetRepositoryInterface;
use AllCoinCore\Service\DateTimeService;
use JetBrains\PhpStorm\Pure;

class AssetPairUpdateProcess extends AbstractAssetPairProcess implements ProcessInterface
{
    #[Pure] public function __construct(
        protected AssetRepositoryInterface $assetRepository,
        protected AssetPairRepositoryInterface $assetPairRepository,
        private DateTimeService $dateTimeService,
        protected AssetPairMapper $assetPairMapper
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
     * @return ResponseDtoInterface
     * @throws ItemSaveException
     * @throws ItemReadException
     */
    public function handle(RequestDtoInterface $dto = null, array $params = []): ResponseDtoInterface
    {
        $asset = $this->assetRepository->findOneById(
            $this->getAssetId($params)
        );

        $assetPair = $this->assetPairRepository->findOneById(
            $this->getAssetPairId($params)
        );

        $assetPair->setName($dto->getName());
        $assetPair->setUpdatedAt($this->dateTimeService->now());

        $this->assetPairRepository->save($assetPair, $asset->getId());

        return $this->assetPairMapper->mapModelToResponseDto($assetPair);
    }


}
