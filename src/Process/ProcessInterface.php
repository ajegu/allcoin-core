<?php


namespace AllCoinCore\Process;


use AllCoinCore\Dto\RequestDtoInterface;
use AllCoinCore\Dto\ResponseDtoInterface;

interface ProcessInterface
{
    public function handle(RequestDtoInterface $dto = null, array $params = []): ?ResponseDtoInterface;
}
