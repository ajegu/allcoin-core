<?php


namespace AllCoinCore\ServiceProvider;


use AllCoinCore\Repository\AssetPairPriceRepository;
use AllCoinCore\Repository\AssetPairPriceRepositoryInterface;
use AllCoinCore\Repository\AssetPairRepository;
use AllCoinCore\Repository\AssetPairRepositoryInterface;
use AllCoinCore\Repository\AssetRepository;
use AllCoinCore\Repository\AssetRepositoryInterface;
use AllCoinCore\Repository\OrderRepository;
use AllCoinCore\Repository\OrderRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AssetRepositoryInterface::class, AssetRepository::class);
        $this->app->bind(AssetPairRepositoryInterface::class, AssetPairRepository::class);
        $this->app->bind(AssetPairPriceRepositoryInterface::class, AssetPairPriceRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }
}
