<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Application\Query\GetFund;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\Context\Payment\Application\Query\GetFund\GetFundQuery;
use VM\Context\Payment\Application\Query\GetFund\GetFundQueryHandler;
use VM\Context\Payment\Application\Query\GetFund\GetFundQueryResponse;
use VM\Context\Payment\Application\Query\GetFund\GetFundQueryResponseConverter;
use VM\Context\Payment\Domain\Write\Aggregate\Fund;
use VM\Context\Payment\Domain\Write\Repository\FundRepository;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\FundIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\CashItemsStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Repository\FundRepositoryMock;

final class GetFundQueryHandlerTest extends TestCase
{
    private FundRepositoryMock $fundRepository;
    private GetFundQueryHandler $handler;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->fundRepository = new FundRepositoryMock($prophet->prophesize(FundRepository::class));
        $this->handler = new GetFundQueryHandler($this->fundRepository->reveal(), new GetFundQueryResponseConverter());
    }

    public function test_it_should_retrieve_fund(): void
    {
        $fund = $this->givenAFund();
        $query = $this->givenAQuery();
        $this->thenFundShouldBeFound($fund);
        $queryResponse = $this->whenHandlingQuery($query);

        $this->assertIsArray($queryResponse->result());

        $this->assertEquals($fund->id()->value(), $queryResponse->id());
        $this->assertEquals($fund->vendingMachineId()->value(), $queryResponse->vendingMachineId());
        $this->assertCount(count($fund->cashItems()), $queryResponse->cashItems());
    }

    private function givenAFund(): Fund
    {
        return Fund::create(
            FundIdStub::random(),
            VendingMachineIdStub::random(),
            CashItemsStub::random()
        );
    }

    private function givenAQuery(): GetFundQuery
    {
        return GetFundQuery::create();
    }

    private function whenHandlingQuery(GetFundQuery $query): GetFundQueryResponse
    {
        return $this->handler->__invoke($query);
    }

    private function thenFundShouldBeFound(Fund $fund): void
    {
        $this->fundRepository->shouldFindVendingMachine($fund);
    }
}
