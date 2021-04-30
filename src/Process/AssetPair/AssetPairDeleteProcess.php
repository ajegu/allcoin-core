<?php


namespace AllCoinCore\Process\AssetPair;


use AllCoinCore\Database\DynamoDb\Exception\ItemDeleteException;
use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Dto\RequestDtoInterface;
use AllCoinCore\Dto\ResponseDtoInterface;
use AllCoinCore\Process\ProcessInterface;

class AssetPairDeleteProcess extends AbstractAssetPairProcess implements ProcessInterface
{
    /**
     * @param RequestDtoInterface|null $dto
     * @param array $params
     * @return ResponseDtoInterface|null
     * @throws ItemDeleteException
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

        $this->assetPairRepository->delete($assetPair->getId());

        return null;
    }

}
