<?php


namespace Test\Helper;


use AllCoinCore\Helper\UuidHelper;
use Test\TestCase;

class UuidHelperTest extends TestCase
{
    private UuidHelper $uuidService;

    public function setUp(): void
    {
        $this->uuidService = new UuidHelper();
    }

    public function testGenerateUuidShouldBeOK(): void
    {
        $this->assertNotEmpty(
            $this->uuidService->generateUuid()
        );

        $this->assertNotEquals(
            $this->uuidService->generateUuid(),
            $this->uuidService->generateUuid()
        );
    }
}
