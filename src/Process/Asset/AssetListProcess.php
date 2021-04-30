<?php


namespace AllCoinCore\Process\Asset;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Dto\ListResponseDto;
use AllCoinCore\Dto\RequestDtoInterface;
use AllCoinCore\Dto\ResponseDtoInterface;
use AllCoinCore\Model\Asset;
use AllCoinCore\Process\ProcessInterface;

class AssetListProcess extends AbstractAssetProcess implements ProcessInterface
{
    /**
     * @param RequestDtoInterface|null $dto
     * @param array $params
     * @return ResponseDtoInterface
     * @throws ItemReadException
     */
    public function handle(RequestDtoInterface $dto = null, array $params = []): ResponseDtoInterface
    {
        $assets = $this->assetRepository->findAll();

        $assetsDto = array_map(function (Asset $asset) {
            return $this->assetMapper->mapModelToResponseDto($asset);
        }, $assets);

        return new ListResponseDto($assetsDto);
    }

}
