<?php

namespace Omnipay\TrustPayments\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class AuthoriseResponse extends Response
{
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
                    'authmethod',
                    'settlestatus',
                ]
            )
        );
    }
}
