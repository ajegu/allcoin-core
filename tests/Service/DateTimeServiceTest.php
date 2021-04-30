<?php


namespace Test\Service;


use AllCoinCore\Exception\DateTimeServiceException;
use AllCoinCore\Service\DateTimeService;
use DateTime;
use Psr\Log\LoggerInterface;
use Test\TestCase;

class DateTimeServiceTest extends TestCase
{
    private DateTimeService $dateTimeService;

    public function setUp(): void
    {
        $logger = $this->createMock(LoggerInterface::class);

        $this->dateTimeService = new DateTimeService(
            $logger
        );
    }

    public function testNowShouldBeOK(): void
    {
        $this->assertNotEmpty(
            $this->dateTimeService->now()
        );
    }

    public function testSubWithErrorShouldThrowException(): void
    {
        $dateTime = new DateTime();
        $duration = 'foo';

        $this->expectException(DateTimeServiceException::class);

        $this->dateTimeService->sub($dateTime, $duration);
    }

    public function testSubShouldBeOK(): void
    {
        $dateTime = new DateTime();
        $duration = 'P1D';

        $dateTimeSub = $this->dateTimeService->sub($dateTime, $duration);

        $this->assertLessThan(
            $dateTime->getTimestamp(),
            $dateTimeSub->getTimestamp()
        );
    }
}
