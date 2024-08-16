<?php

namespace UnzerSDK\test\integration\Resources;

use UnzerSDK\Resources\EmbeddedResources\Paypage\PaymentMethodConfig;
use UnzerSDK\Resources\EmbeddedResources\Paypage\PaymentMethodsConfigs;
use UnzerSDK\Resources\EmbeddedResources\Paypage\Resources;
use UnzerSDK\Resources\EmbeddedResources\Paypage\Style;
use UnzerSDK\Resources\EmbeddedResources\Paypage\Urls;
use UnzerSDK\Resources\EmbeddedResources\RiskData;
use UnzerSDK\Resources\Metadata;
use UnzerSDK\Resources\V2\Paypage;
use UnzerSDK\test\BaseIntegrationTest;

class PaypageV2Test extends BaseIntegrationTest
{
    /**
     * @test
     */
    public function createMinimumPaypageTest()
    {
        $paypage = new Paypage(9.99, 'EUR', 'charge');

        $this->assertNull($paypage->getId());
        $this->assertNull($paypage->getRedirectUrl());

        $this->getUnzerObject()->createPaypage($paypage);

        $this->assertCreatedPaypage($paypage);

        $this->assertNull($paypage->getType());
        $this->assertNull($paypage->getRecurrenceType());
        $this->assertNull($paypage->getLogoImage());
        $this->assertNull($paypage->getShopName());
        $this->assertNull($paypage->getOrderId());
        $this->assertNull($paypage->getInvoiceId());
        $this->assertNull($paypage->getPaymentReference());

        $this->assertNull($paypage->getUrls());
        $this->assertNull($paypage->getStyle());
        $this->assertNull($paypage->getResources());
        $this->assertNull($paypage->getPaymentMethodsConfigs());
        $this->assertNull($paypage->getRisk());
    }

    /**
     * @test
     */
    public function fetchPaypage()
    {
        $paypage = new Paypage(9.99, 'EUR', 'charge');
        $unzer = $this->getUnzerObject();
        $unzer->createPaypage($paypage);
        $this->assertCreatedPaypage($paypage);

        $unzer->fetchPaypageV2($paypage);
        $this->assertEquals($paypage->getPayments(), []);
        $this->assertEquals($paypage->getTotal(), 0);
    }

    /**
     * @test
     */
    public function JwtTokenShouldBeReusedForMultipleRequests()
    {
        $unzer = $this->getUnzerObject();

        $paypageFirst = new Paypage(9.99, 'EUR', 'charge');
        $paypageSecond = new Paypage(9.99, 'EUR', 'charge');

        $this->assertNull($paypageFirst->getId());
        $this->assertNull($paypageSecond->getRedirectUrl());

        // Create Firt paypage
        $this->assertNull($unzer->getJwtToken());
        $unzer->createPaypage($paypageFirst);
        $InitialJwtToken = $unzer->getJwtToken();
        $this->assertNotNull($InitialJwtToken);

        // Create Second paypage
        $unzer->createPaypage($paypageSecond);

        // JWT token has not changed.
        $this->assertEquals($InitialJwtToken, $unzer->getJwtToken());

        $this->assertCreatedPaypage($paypageFirst);
        $this->assertCreatedPaypage($paypageSecond);
    }

    /**
     * @test
     */
    public function createPaypageWithOptionalStringProperties()
    {
        $paypage = new Paypage(9.99, 'EUR', 'charge');
        $paypage->setType('hosted');
        $paypage->setRecurrenceType('unscheduled');
        $paypage->setLogoImage('logoImage');
        $paypage->setShopName('shopName');
        $paypage->setOrderId('orderId');
        $paypage->setInvoiceId('invoiceId');
        $paypage->setPaymentReference('paymentReference');

        $this->getUnzerObject()->createPaypage($paypage);

        $this->assertCreatedPaypage($paypage);
        //TODO: fetch paypage and compare properties.
    }

    /**
     * @test
     */
    public function createPaypageWithUrls()
    {
        $urls = new Urls();
        $urls->setTermsAndCondition('https://termsandcondition.com');
        $urls->setPrivacyPolicy('https://privacypolicy.com');
        $urls->setImprint('https://imprint.com');
        $urls->setHelp('https://help.com');
        $urls->setContact('https://contact.com');
        $urls->setFavicon('https://favicon.com');
        $urls->setReturnSuccess('https://returnsuccess.com');
        $urls->setReturnPending('https://returnpending.com');
        $urls->setReturnFailure('https://returnfailure.com');
        $urls->setReturnCancel('https://returncancel.com');

        $paypage = new Paypage(9.99, 'EUR', 'charge');
        $paypage->setUrls($urls);

        $this->getUnzerObject()->createPaypage($paypage);

        $this->assertCreatedPaypage($paypage);
    }

    /**
     * @test
     */
    public function createPaypageWithStyle()
    {
        $style = new Style();
        $style->setFontFamily('comic sans');
        $style->setButtonColor('red');
        $style->setPrimaryTextColor('green');
        $style->setLinkColor('blue');
        $style->setBackgroundColor('black');
        $style->setCornerRadius('5px');
        $style->setShadows(true);
        $style->setHideUnzerLogo(true);

        $paypage = new Paypage(9.99, 'EUR', 'charge');
        $paypage->setStyle($style);

        $this->getUnzerObject()->createPaypage($paypage);
        $this->assertCreatedPaypage($paypage);
    }

    /** @test
     * @dataProvider paymentMethodsConfigsDataProvider
     **/
    public function createPaypageWithMethodConfigs(PaymentMethodsConfigs $configs)
    {
        $paypage = new Paypage(9.99, 'EUR', 'charge');
        $paypage->setPaymentMethodsConfigs($configs);
        $this->getUnzerObject()->createPaypage($paypage);

        $this->assertCreatedPaypage($paypage);
    }

    /** @test * */
    public function createPaypageWithMethodResourceIds()
    {
        $unzer = $this->getUnzerObject();
        $customer = $unzer->createCustomer($this->getMinimalCustomer());
        $basket = $this->createV2Basket();
        $metadata = $unzer->createMetadata((new Metadata())->setShopType('unitTests'));


        $resources = new Resources($customer->getId(), $basket->getId(), $metadata->getId());
        $paypage = new Paypage(9.99, 'EUR', 'charge');
        $paypage->setResources($resources);
        $unzer->createPaypage($paypage);

        $this->assertCreatedPaypage($paypage);
    }

    /** @test * */
    public function createPaypageWithRiskData()
    {
        $unzer = $this->getUnzerObject();
        $risk = new RiskData();

        $risk->setThreatMetrixId('f544if49wo4f74ef1x')
            ->setCustomerGroup('TOP')
            ->setCustomerId('C-122345')
            ->setConfirmedAmount('1234')
            ->setConfirmedOrders('42')
            ->setRegistrationLevel('1')
            ->setRegistrationDate('20160412');

        $paypage = new Paypage(9.99, 'EUR', 'charge');
        $paypage->setRisk($risk);
        $unzer->createPaypage($paypage);

        $this->assertCreatedPaypage($paypage);
    }

    /**
     * @param Paypage $paypage
     * @return void
     */
    public function assertCreatedPaypage(Paypage $paypage): void
    {
        $this->assertNotNull($paypage->getId());
        $this->assertNotNull($paypage->getRedirectUrl());
        $this->assertStringContainsString($paypage->getId(), $paypage->getRedirectUrl());
    }

    public function paymentMethodsConfigsDataProvider()
    {
        $paypalEnabledConfig = new PaymentMethodConfig(true, 1);

        $withDefaultEnabled = (new PaymentMethodsConfigs())
            ->setDefault(
                (new PaymentMethodConfig())->setEnabled(true)
            );

        $withDefaultDisabled = (new PaymentMethodsConfigs())
            ->setDefault(
                (new PaymentMethodConfig())->setEnabled(false)
            )->setMethodConfigs(['paypal' => $paypalEnabledConfig]);

        $withMethodConfigs = new PaymentMethodsConfigs();
        $withMethodConfigs->setMethodConfigs([
            'paypal' => $paypalEnabledConfig
        ]);
        $paymentMethodsConfigs = (new PaymentMethodsConfigs())->setPreselectedMethod('cards');

        return [
            'empty' => [new PaymentMethodsConfigs()],
            'withDefaultEnabled' => [$withDefaultEnabled],
            'withDefaultDisabled' => [$withDefaultDisabled],
            'withPreselectedMethod' => [$paymentMethodsConfigs],
            'withMethodConfigs' => [$withMethodConfigs]
        ];
    }
}
