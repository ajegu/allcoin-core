<?php


namespace AllCoinCore\DataMapper;


use AllCoinCore\Dto\AssetPairResponseDto;
use AllCoinCore\Dto\ResponseDtoInterface;
use AllCoinCore\Model\ModelInterface;

class AssetPairMapper extends AbstractDataMapper implements DataMapperInterface
{
    public function mapModelToResponseDto(ModelInterface $model): ResponseDtoInterface
    {
        return $this->convertModelToResponseDto($model, AssetPairResponseDto::class);
    }

}
