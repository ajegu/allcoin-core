<?php


namespace Test\Lambda;


use AllCoinCore\Exception\LambdaInvokeException;
use AllCoinCore\Lambda\LambdaAdapter;
use Aws\Lambda\Exception\LambdaException;
use Aws\Lambda\LambdaClient;
use Psr\Log\LoggerInterface;
use Test\TestCase;

class LambdaInvokeTest extends TestCase
{
    private LambdaAdapter $lambdaInvoke;

    private LambdaClient $lambdaClient;
    private LoggerInterface $logger;

    public function setUp(): void
    {
        $this->lambdaClient = $this->createMock(LambdaClient::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->lambdaInvoke = new LambdaAdapter(
            $this->lambdaClient,
            $this->logger
        );
    }

    public function testInvokeWithLambdaErrorShouldThrowException(): void
    {
        $lambdaRequest = $this->createMock(LambdaRequest::class);
        $functionName = 'foo';
        $lambdaRequest->expects($this->exactly(2))->method('getFunctionName')->willReturn($functionName);
        $payload = ['foo' => 'bar'];
        $lambdaRequest->expects($this->exactly(2))->method('getPayload')->willReturn($payload);

        $args = [
            'FunctionName' => $functionName,
            'InvocationType' => LambdaAdapter::RequestResponse,
            'Payload' => json_encode($payload),
        ];

        $this->lambdaClient->expects($this->once())
            ->method('__call')
            ->with('invoke', [$args])
            ->willThrowException($this->createMock(LambdaException::class));

        $this->logger->expects($this->once())->method('error');

        $this->expectException(LambdaInvokeException::class);

        $this->lambdaInvoke->invoke($lambdaRequest);
    }
}
