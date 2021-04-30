<?php


namespace AllCoinCore\Process\Asset;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Database\DynamoDb\Exception\ItemSaveException;
use AllCoinCore\DataMapper\AssetMapper;
use AllCoinCore\Dto\RequestDtoInterface;
use AllCoinCore\Dto\ResponseDtoInterface;
use AllCoinCore\Process\ProcessInterface;
use AllCoinCore\Repository\AssetRepositoryInterface;
use AllCoinCore\Service\DateTimeService;
use JetBrains\PhpStorm\Pure;

class AssetUpdateProcess extends AbstractAssetProcess implements ProcessInterface
{
    #[Pure] public function __construct(
        protected AssetRepositoryInterface $assetRepository,
        protected AssetMapper $assetMapper,
        private DateTimeService $dateTimeService
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
     * @throws ItemReadException
     * @throws ItemSaveException
     */
    public function handle(RequestDtoInterface $dto = null, array $params = []): ResponseDtoInterface
    {
        $asset = $this->assetRepository->findOneById(
            $this->getAssetId($params)
        );

        $asset->setName($dto->getName());
        $asset->setUpdatedAt($this->dateTimeService->now());

        $this->assetRepository->save($asset);

        return $this->assetMapper->mapModelToResponseDto($asset);
    }

}
