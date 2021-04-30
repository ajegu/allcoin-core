<?php


namespace Test\Validation;


use AllCoinCore\Validation\AssetPairValidation;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Validation\Validator;
use Test\TestCase;

class AssetPairValidationTest extends TestCase
{
    private AssetPairValidation $assetPairValidation;

    public function setUp(): void
    {
        $this->assetPairValidation = new AssetPairValidation();
    }

    public function testPostRulesShouldBeOK(): void
    {
        $rules = $this->assetPairValidation->getPostRules();

        $data = [
            'name' => 'foo'
        ];

        $validator = new Validator(
            $this->createMock(Translator::class),
            $data,
            $rules
        );

        $this->assertFalse($validator->fails());
    }

    public function testPostRulesShouldFails(): void
    {
        $rules = $this->assetPairValidation->getPostRules();

        $data = [];

        $validator = new Validator(
            $this->createMock(Translator::class),
            $data,
            $rules
        );

        $this->assertTrue($validator->fails());
    }

    public function testPutRulesShouldBeOK(): void
    {
        $rules = $this->assetPairValidation->getPutRules();

        $data = [
            'name' => 'foo'
        ];

        $validator = new Validator(
            $this->createMock(Translator::class),
            $data,
            $rules
        );

        $this->assertFalse($validator->fails());
    }

    public function testPutRulesShouldFails(): void
    {
        $rules = $this->assetPairValidation->getPutRules();

        $data = [];

        $validator = new Validator(
            $this->createMock(Translator::class),
            $data,
            $rules
        );

        $this->assertTrue($validator->fails());
    }
}
