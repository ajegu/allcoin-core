<?php


namespace AllCoinCore\Process\AssetPair;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Dto\RequestDtoInterface;
use AllCoinCore\Dto\ResponseDtoInterface;
use AllCoinCore\Process\ProcessInterface;

class AssetPairGetProcess extends AbstractAssetPairProcess implements ProcessInterface
{
    /**
     * @param RequestDtoInterface|null $dto
     * @param array $params
     * @return ResponseDtoInterface|null
     * @throws ItemReadException
     */
    public function handle(RequestDtoInterface $dto = null, array $params = []): ?ResponseDtoInterface
    {
        $this->assetRepository->findOneById(
            $this->getAssetId($params)
        );

        $assetPair = $this->assetPairRepository->findOneById(
            $this->getAssetPairId($params)
        );

        return $this->assetPairMapper->mapModelToResponseDto($assetPair);
    }

}
