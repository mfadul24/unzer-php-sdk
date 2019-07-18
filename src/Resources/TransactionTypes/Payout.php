<?php
/**
 * This represents the payout transaction.
 *
 * Copyright (C) 2019 heidelpay GmbH
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @link  https://docs.heidelpay.com/
 *
 * @author  Simon Gabriel <development@heidelpay.com>
 *
 * @package  heidelpayPHP/transaction_types
 */
namespace heidelpayPHP\Resources\TransactionTypes;

class Payout extends AbstractTransactionType
{
    /** @var float $amount */
    protected $amount;

    /** @var string $currency */
    protected $currency;

    /** @var string $returnUrl */
    protected $returnUrl;

    /**
     * Payout constructor.
     *
     * @param float  $amount
     * @param string $currency
     * @param null   $returnUrl
     */
    public function __construct($amount = null, $currency = null, $returnUrl = null)
    {
        $this->setAmount($amount);
        $this->setCurrency($currency);
        $this->setReturnUrl($returnUrl);

        parent::__construct();
    }

    //<editor-fold desc="Setters/Getters">

    /**
     * @return float|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return self
     */
    public function setAmount($amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return self
     */
    public function setCurrency($currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    /**
     * @param string $returnUrl
     *
     * @return Payout
     */
    public function setReturnUrl(string $returnUrl): Payout
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    //</editor-fold>

    //<editor-fold desc="Overridable Methods">

    /**
     * {@inheritDoc}
     */
    protected function getResourcePath(): string
    {
        return 'payouts';
    }

    //</editor-fold>
}
