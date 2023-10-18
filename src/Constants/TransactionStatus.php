<?php
/**
 * This file contains definitions of the status of the payment transactions.
 *
 * @link  https://docs.unzer.com/
 *
 */

namespace UnzerSDK\Constants;

class TransactionStatus
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_ERROR = 'error';
    public const STATUS_RESUMED = 'resumed';
}
