<?php


namespace Test\Builder;


use AllCoinCore\Builder\AssetPairBuilder;
use AllCoinCore\Service\DateTimeService;
use AllCoinCore\Service\UuidService;
use DateTime;
use Test\TestCase;

class AssetPairBuilderTest extends TestCase
{
    private AssetPairBuilder $assetPairBuilder;

    private UuidService $uuidService;
    private DateTimeService $dateTimeService;

    public function setUp(): void
    {
        $this->uuidService = $this->createMock(UuidService::class);
        $this->dateTimeService = $this->createMock(DateTimeService::class);

        $this->assetPairBuilder = new AssetPairBuilder(
            $this->uuidService,
            $this->dateTimeService
        );
    }

    public function testBuildShouldBeOK(): void
    {
        $name = 'foo';

        $uuid = 'bar';
        $this->uuidService->expects($this->once())
            ->method('generateUuid')
            ->willReturn($uuid);

        $createdAt = new DateTime();
        $this->dateTimeService->expects($this->once())
            ->method('now')
            ->willReturn($createdAt);

        $assetPair = $this->assetPairBuilder->build($name);

        $this->assertEquals($uuid, $assetPair->getId());
        $this->assertEquals($name, $assetPair->getName());
        $this->assertEquals($createdAt, $assetPair->getCreatedAt());
        $this->assertNull($assetPair->getUpdatedAt());
    }
}
