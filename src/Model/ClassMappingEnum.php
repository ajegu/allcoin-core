<?php


namespace AllCoinCore\Model;


class ClassMappingEnum
{
    const ASSET = 'asset';
    const ASSET_PAIR = 'assetPair';
    const ASSET_PAIR_PRICE = 'assetPairPrice';
    const ORDER = 'order';

    const CLASS_MAPPING = [
        Asset::class => self::ASSET,
        AssetPair::class => self::ASSET_PAIR,
        AssetPairPrice::class => self::ASSET_PAIR_PRICE,
        Order::class => self::ORDER,
    ];
}
