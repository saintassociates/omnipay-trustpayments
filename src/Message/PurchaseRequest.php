<?php

namespace Omnipay\TrustPayments\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\TrustPayments\Traits\GatewayParamsTrait;

class PurchaseRequest extends AbstractRequest
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
            'sitereference',
            'encryptionKey',
            'currencyiso3a',
            'mainamount',
            'card'
        );

        $data = [
            'sitereference' => $this->getSitereference(),
            'encryptionKey' => $this->getEncryptionKey(),
            'currencyiso3a' => $this->getCurrencyiso3a(),
            'mainamount' => $this->getMainamount(),
            'sitesecurity' => $this->generateSiteSecurity($this->getSiteSecurityData()),
            'sitesecuritytimestamp' => date('Y-m-d H:i:s'),
            'version' => $this->getVersion(),
            'stprofile' => $this->getStprofile(),
            'orderreference' => $this->getTransactionId(),
            'successfulurlredirect' => $this->getReturnUrl(),
            'declinedurlredirect' => $this->getCancelUrl(),
            'ruleidentifier' => 'STR-6',
        ];

        $data = array_merge($data, $this->getBillingAddressData($data));
        $data = array_merge($data, $this->getShippingAddressData($data));

        return $data;
    }

    public function getSiteSecurityData()
    {
        $data = [];

        $data[] = $this->getCurrencyiso3a();
        $data[] = $this->getMainamount();
        $data[] = $this->getSitereference();
        $data[] = date('Y-m-d H:i:s');
        $data[] = $this->getEncryptionKey();

        return $data;
    }

    public function generateSiteSecurity(array $data)
    {
        $hashString = implode('', $data);

        return 'h' . hash('sha256', $hashString);
    }

    public function setCurrencyiso3a($value)
    {
        return $this->setParameter('currencyiso3a', $value);
    }

    public function getCurrencyiso3a()
    {
        return $this->getParameter('currencyiso3a');
    }

    public function setMainamount($value)
    {
        return $this->setParameter('mainamount', $value);
    }

    public function getMainamount()
    {
        return $this->getParameter('mainamount');
    }

    /**
     * Send the request with specified data
     *
     * @param mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        return $this->createResponse($data);
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }

    protected function getBillingAddressData(array $data)
    {
        $address = $this->getAddressData('Billing');

        $billing = [
            'billingfirstname' => $address['FirstName'],
            'billinglastname' => $address['LastName'],
            'billingemail' => $address['Email'],
            'billingtelephone' => $address['Phone'],
            'billingpremise' => $address['Address1'],
            'billingstreet' => $address['Address2'],
            'billingtown' => $address['City'],
            'billingpostcode' => $address['Postcode'],
            'billingcountryiso2a' => $address['Country'],
        ];

        return array_merge($data, $billing);
    }

    protected function getShippingAddressData(array $data)
    {
        $address = $this->getAddressData(
            $this->getBillingForShipping() ? 'Billing' : 'Shipping'
        );

        $shipping = [
            'customerfirstname' => $address['FirstName'],
            'customerlastname' => $address['LastName'],
            'customeremail' => $address['Email'],
            'customerpremise' => $address['Address1'],
            'customerstreet' => $address['Address2'],
            'customertown' => $address['City'],
            'customerpostcode' => $address['Postcode'],
        ];

        return array_merge($data, $shipping);
    }

    protected function getAddressData($type = 'Billing')
    {
        $card = $this->getCard();

        $data = [];

        $keys = [
            'FirstName',
            'LastName',
            'Address1',
            'Address2',
            'City',
            'Postcode',
            'Country',
            'Email',
            'Phone',
        ];

        foreach ($keys as $attribute) {
            if ($attribute === 'Email') {
                $data[$attribute] = call_user_func([$card, 'get' . $attribute]);
                continue;
            }

            $data[$attribute] = call_user_func([$card, 'get' . $type . $attribute]);
        }

        return $data;
    }
}
