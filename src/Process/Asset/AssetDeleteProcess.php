<?php


namespace AllCoinCore\Process\Asset;


use AllCoinCore\Database\DynamoDb\Exception\ItemDeleteException;
use AllCoinCore\Dto\RequestDtoInterface;
use AllCoinCore\Dto\ResponseDtoInterface;
use AllCoinCore\Process\ProcessInterface;

class AssetDeleteProcess extends AbstractAssetProcess implements ProcessInterface
{
    /**
     * @param RequestDtoInterface|null $dto
     * @param array $params
     * @return ResponseDtoInterface|null
     * @throws ItemDeleteException
     */
    public function handle(RequestDtoInterface $dto = null, array $params = []): ?ResponseDtoInterface
    {
        $this->assetRepository->delete(
            $this->getAssetId($params)
        );

        return null;
    }

}
