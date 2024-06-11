<?php

namespace Omnipay\TrustPayments\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\TrustPayments\Traits\GatewayParamsTrait;

class AuthoriseRequest extends PurchaseRequest
{
    use GatewayParamsTrait;

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate(
            'authmethod',
            'settlestatus'
        );

        $data = parent::getData();
        $data['authmethod'] = $this->getAuthmethod();
        $data['settlestatus'] = $this->getSettlestatus();

        $data = array_merge($data, $this->getBillingAddressData($data));
        $data = array_merge($data, $this->getShippingAddressData($data));

        return $data;
    }

    public function getAuthMethod()
    {
        return $this->getParameter('authmethod');
    }

    public function getSettleStatus()
    {
        return $this->getParameter('settlestatus');
    }

    public function setAuthMethod($value)
    {
        return $this->setParameter('authmethod', $value);
    }

    public function setSettleStatus($value)
    {
        return $this->setParameter('settlestatus', $value);
    }

    public function createResponse($data): AuthoriseResponse
    {
        return $this->response = new AuthoriseResponse($this, $data);
    }
}
