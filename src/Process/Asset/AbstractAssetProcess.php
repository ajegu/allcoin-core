<?php


namespace AllCoinCore\Process\Asset;


use AllCoinCore\DataMapper\AssetMapper;
use AllCoinCore\Exception\RequiredParameterException;
use AllCoinCore\Repository\AssetRepositoryInterface;

abstract class AbstractAssetProcess
{
    public function __construct(
        protected AssetRepositoryInterface $assetRepository,
        protected AssetMapper $assetMapper
    )
    {
    }

    /**
     * @param array $params
     * @return string
     */
    protected function getAssetId(array $params): string
    {
        return $params['id'] ?? throw new RequiredParameterException('The asset ID must be defined in $params');
    }
}
