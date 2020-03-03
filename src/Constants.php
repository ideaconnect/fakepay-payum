<?php

namespace IDCT\Payum\Fakepay;

abstract class Constants
{
    const FIELD_PAID = 'paid';
    const FIELD_STATUS = 'status';
    const STATUS_NEW = 'new';
    const STATUS_CAPTURED = 'captured';
    const STATUS_PENDING = 'pending';
    const STATUS_AUTHORIZED = 'authorized';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELED = 'canceled';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_COMPLAINT = 'complaint';

    final private function __construct()
    {
    }

    /**
     * @return array
     */
    public static function getSupportedCurrencies()
    {
        return [
            'PLN',
            'EUR',
            'USD',
            'GBP',
            'JPY',
            'CZK',
            'SEK',
            'DKK'
        ];
    }
}
