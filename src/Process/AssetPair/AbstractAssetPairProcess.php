<?php


namespace AllCoinCore\Process\AssetPair;


use AllCoinCore\DataMapper\AssetPairMapper;
use AllCoinCore\Exception\RequiredParameterException;
use AllCoinCore\Repository\AssetPairRepositoryInterface;
use AllCoinCore\Repository\AssetRepositoryInterface;

abstract class AbstractAssetPairProcess
{
    public function __construct(
        protected AssetRepositoryInterface $assetRepository,
        protected AssetPairRepositoryInterface $assetPairRepository,
        protected AssetPairMapper $assetPairMapper
    )
    {
    }

    /**
     * @param array $params
     * @return string
     */
    protected function getAssetId(array $params): string
    {
        return $params['assetId'] ?? throw new RequiredParameterException('The asset ID must be defined in $params');
    }

    /**
     * @param array $params
     * @return string
     */
    protected function getAssetPairId(array $params): string
    {
        return $params['id'] ?? throw new RequiredParameterException('The asset pair ID must be defined in $params');
    }
}
