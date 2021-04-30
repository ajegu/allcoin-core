<?php


namespace Test\DataMapper;


use AllCoinCore\DataMapper\AssetMapper;
use AllCoinCore\Dto\AssetResponseDto;
use AllCoinCore\Model\ModelInterface;
use AllCoinCore\Service\SerializerService;
use Test\TestCase;

class AssetMapperTest extends TestCase
{
    private AssetMapper $assetMapper;

    private SerializerService $serializerService;

    public function setUp(): void
    {
        $this->serializerService = $this->createMock(SerializerService::class);

        $this->assetMapper = new AssetMapper(
            $this->serializerService
        );
    }

    public function testMapModelToResponseDtoShouldBeOK(): void
    {
        $model = $this->createMock(ModelInterface::class);

        $data = [];
        $this->serializerService->expects($this->once())
            ->method('normalizeModel')
            ->with($model)
            ->willReturn($data);

        $this->serializerService->expects($this->once())
            ->method('deserializeToResponse')
            ->with($data, AssetResponseDto::class);

        $this->assetMapper->mapModelToResponseDto($model);
    }
}
