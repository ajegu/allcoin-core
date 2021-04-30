<?php


namespace AllCoinCore\Process\AssetPair;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Dto\ListResponseDto;
use AllCoinCore\Dto\RequestDtoInterface;
use AllCoinCore\Dto\ResponseDtoInterface;
use AllCoinCore\Model\AssetPair;
use AllCoinCore\Process\ProcessInterface;

class AssetPairListProcess extends AbstractAssetPairProcess implements ProcessInterface
{
    /**
     * @param RequestDtoInterface|null $dto
     * @param array $params
     * @return ResponseDtoInterface|null
     * @throws ItemReadException
     */
    public function handle(RequestDtoInterface $dto = null, array $params = []): ?ResponseDtoInterface
    {
        $asset = $this->assetRepository->findOneById(
            $this->getAssetId($params)
        );

        $assetPairs = $this->assetPairRepository->findAllByAssetId($asset->getId());

        $assetPairsDto = array_map(function (AssetPair $assetPair) {
            return $this->assetPairMapper->mapModelToResponseDto($assetPair);
        }, $assetPairs);

        return new ListResponseDto($assetPairsDto);
    }

}
