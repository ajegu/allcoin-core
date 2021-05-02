<?php


namespace AllCoinCore\Helper;


use Ramsey\Uuid\Uuid;

class UuidHelper
{
    public function generateUuid(): string
    {
        return Uuid::uuid4();
    }
}
