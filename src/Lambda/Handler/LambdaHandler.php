<?php


namespace AllCoinCore\Lambda\Handler;


use AllCoinCore\Helper\SerializerHelper;
use AllCoinCore\Lambda\LambdaAdapter;

abstract class LambdaHandler
{
    public function __construct(
        protected LambdaAdapter $lambdaAdapter,
        protected SerializerHelper $serializerHelper
    ) {}
}
