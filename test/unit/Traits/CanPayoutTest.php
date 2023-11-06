<?php

/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */
/**
 * This class defines unit tests to verify functionality of the CanPayout trait.
 *
 * @link  https://docs.unzer.com/
 *
 */

namespace UnzerSDK\test\unit\Traits;

use UnzerSDK\Unzer;
use UnzerSDK\Resources\Customer;
use UnzerSDK\Resources\Metadata;
use UnzerSDK\Resources\TransactionTypes\Payout;
use UnzerSDK\test\BasePaymentTest;
use RuntimeException;

class CanPayoutTest extends BasePaymentTest
{
    /**
     * Verify payout method throws exception if the class does not implement the UnzerParentInterface.
     *
     * @test
     */
    public function payoutShouldThrowExceptionIfTheClassDoesNotImplementParentInterface(): void
    {
        $dummy = new TraitDummyWithoutCustomerWithoutParentIF();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('TraitDummyWithoutCustomerWithoutParentIF');

        $dummy->payout(1.0, 'MyCurrency', 'https://return.url');
    }

    /**
     * Verify payout method propagates payout method to Unzer object.
     *
     * @test
     */
    public function payoutShouldPropagatePayoutToUnzer(): void
    {
        $unzerMock = $this->getMockBuilder(Unzer::class)->setMethods(['payout'])->disableOriginalConstructor()->getMock();
        $dummyMock     = $this->getMockBuilder(TraitDummyWithoutCustomerWithParentIF::class)->setMethods(['getUnzerObject'])->getMock();

        $payout = new Payout();
        $customer  = (new Customer())->setId('123');
        $metadata  = new Metadata();
        $dummyMock->expects($this->exactly(4))->method('getUnzerObject')->willReturn($unzerMock);
        $unzerMock->expects($this->exactly(4))->method('payout')
            ->withConsecutive(
                [1.1, 'MyCurrency', $dummyMock, 'https://return.url', null, null],
                [1.2, 'MyCurrency2', $dummyMock, 'https://return.url2', $customer, null],
                [1.3, 'MyCurrency3', $dummyMock, 'https://return.url3', $customer, 'orderId'],
                [1.4, 'MyCurrency3', $dummyMock, 'https://return.url3', $customer, 'orderId', $metadata]
            )->willReturn($payout);


        /** @var TraitDummyWithoutCustomerWithParentIF $dummyMock */
        $returnedPayout = $dummyMock->payout(1.1, 'MyCurrency', 'https://return.url');
        $this->assertSame($payout, $returnedPayout);
        $returnedPayout = $dummyMock->payout(1.2, 'MyCurrency2', 'https://return.url2', $customer);
        $this->assertSame($payout, $returnedPayout);
        $returnedPayout = $dummyMock->payout(1.3, 'MyCurrency3', 'https://return.url3', $customer, 'orderId');
        $this->assertSame($payout, $returnedPayout);
        $returnedPayout = $dummyMock->payout(1.4, 'MyCurrency3', 'https://return.url3', $customer, 'orderId', $metadata);
        $this->assertSame($payout, $returnedPayout);
    }
}
