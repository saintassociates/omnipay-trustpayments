<?php

namespace Omnipay\TrustPayments;

use Omnipay\Common\AbstractGateway;
use Omnipay\TrustPayments\Message\AuthoriseRequest;
use Omnipay\TrustPayments\Message\CompletePurchaseRequest;
use Omnipay\TrustPayments\Message\PurchaseRequest;
use Omnipay\TrustPayments\Traits\GatewayParamsTrait;

/**
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{

    use GatewayParamsTrait;

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     * @return string
     */
    public function getName()
    {
        return 'TrustPayments';
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest|\Omnipay\Common\Message\RequestInterface
     */
    public function purchase(array $parameters = [])
    {
        $request = $this->createRequest(PurchaseRequest::class, $parameters);

        return $request;
    }

    public function authorise(array $parameters = [])
    {
        $request = $this->createRequest(AuthoriseRequest::class, $parameters);

        return $request;
    }

    public function completePurchase(array $parameters = [])
    {
        $request = $this->createRequest(CompletePurchaseRequest::class, $parameters);

        return $request;
    }

    public function getDefaultParameters()
    {
        return [
            'stprofile' => 'default',
            'version' => '2',
        ];
    }
}
