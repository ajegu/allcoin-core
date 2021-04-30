<?php


namespace AllCoinCore\DataMapper;


use AllCoinCore\Dto\AssetResponseDto;
use AllCoinCore\Dto\ResponseDtoInterface;
use AllCoinCore\Model\ModelInterface;

class AssetMapper extends AbstractDataMapper implements DataMapperInterface
{
    public function mapModelToResponseDto(ModelInterface $model): ResponseDtoInterface
    {
        return $this->convertModelToResponseDto($model, AssetResponseDto::class);
    }

}
