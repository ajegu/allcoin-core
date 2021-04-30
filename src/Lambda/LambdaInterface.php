<?php


namespace AllCoinCore\Lambda;


interface LambdaInterface
{
    public function __invoke(array $event): void;
}
