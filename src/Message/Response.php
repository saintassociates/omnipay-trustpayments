<?php

namespace Omnipay\TrustPayments\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class Response extends AbstractResponse implements RedirectResponseInterface
{
    protected $baseUrl = 'https://payments.securetrading.net';

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * @return @inherit
     */
    public function getRedirectData()
    {
        // Pull out just these four fields from the data supplied.

        return array_intersect_key(
            $this->getData(),
            array_flip(
                [
                    'sitereference',
                    'currencyiso3a',
                    'mainamount',
                    'version',
                    'stprofile',
                    'sitesecurity',
                    'orderreference',
                    'successfulurlredirect',
                    'declinedurlredirect',
                    'sitesecuritytimestamp',
                    'ruleidentifier',
                ]
            )
        );
    }

    public function getRedirectUrl()
    {
        return $this->baseUrl . '/process/payments/choice';
    }
}
