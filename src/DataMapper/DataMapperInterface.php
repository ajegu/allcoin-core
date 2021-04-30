<?php


namespace AllCoinCore\DataMapper;


use AllCoinCore\Dto\ResponseDtoInterface;
use AllCoinCore\Model\ModelInterface;

interface DataMapperInterface
{
    public function mapModelToResponseDto(ModelInterface $model): ResponseDtoInterface;
}
