<?php


namespace AllCoinCore\Validation;


interface ValidationInterface
{
    public function getPostRules(): array;

    public function getPutRules(): array;
}
