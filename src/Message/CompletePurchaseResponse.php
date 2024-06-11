<?php

namespace Omnipay\TrustPayments\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    protected function getDataItem($name, $default = null)
    {
        $data = $this->getData();

        return isset($this->data[$name]) ? $this->data[$name] : $default;
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->getDataItem('errorcode') === '0';
    }

    public function getTransactionId()
    {
        return $this->getOrderreference();
    }

    public function getOrderreference()
    {
        return $this->getDataItem('orderreference');
    }

    public function getPaymenttypedescription()
    {
        return $this->getDataItem('paymenttypedescription');
    }

    public function getRequestreference()
    {
        return $this->getDataItem('requestreference');
    }

    public function getSettlestatus()
    {
        return $this->getDataItem('settlestatus');
    }

    public function getSitereference()
    {
        return $this->getDataItem('sitereference');
    }

    public function getTransactionreference()
    {
        return $this->getDataItem('transactionreference');
    }
}
