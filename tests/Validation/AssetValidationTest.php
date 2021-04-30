<?php


namespace Test\Validation;


use AllCoinCore\Validation\AssetValidation;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Validation\Validator;
use Test\TestCase;

class AssetValidationTest extends TestCase
{
    private AssetValidation $assetValidation;

    public function setUp(): void
    {
        $this->assetValidation = new AssetValidation();
    }

    public function testPostRulesShouldBeOK(): void
    {
        $rules = $this->assetValidation->getPostRules();

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
        $rules = $this->assetValidation->getPostRules();

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
        $rules = $this->assetValidation->getPutRules();

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
        $rules = $this->assetValidation->getPutRules();

        $data = [];

        $validator = new Validator(
            $this->createMock(Translator::class),
            $data,
            $rules
        );

        $this->assertTrue($validator->fails());
    }
}
