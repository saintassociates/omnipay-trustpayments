<?php

namespace Omnipay\TrustPayments\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\TrustPayments\Traits\GatewayParamsTrait;

class CompletePurchaseRequest extends AbstractRequest
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
        $data = $this->httpRequest->query->all();

        $encryptData = [];

        $requiredKeys = [
            'errorcode',
            'orderreference',
            'paymenttypedescription',
            'requestreference',
            'settlestatus',
            'sitereference',
            'transactionreference',
            'responsesitesecurity',
        ];

        foreach($requiredKeys as $requiredKey) {
            if (!isset($data[$requiredKey])) {
                throw new InvalidResponseException('Missing or invalid "'. $requiredKey .'" parameter');
            }
        }

        $responsesitesecurityKey = array_search('responsesitesecurity', $requiredKeys);
        if ($responsesitesecurityKey !== false) {
            unset($requiredKeys[$responsesitesecurityKey]);
        }

        foreach($requiredKeys as $requiredKey) {
            $encryptData[] = $data[$requiredKey];
        }

        $encryptData[] = $this->getEncryptionKey();

        $hashString = implode('', $encryptData);
        $validHash = hash('sha256', $hashString);

        if ($validHash !== $data['responsesitesecurity']) {
            throw new InvalidResponseException('The response site security code does not match the re-calculated hash');
        }

        return $data;

    }

    /**
     * Send the request with specified data
     *
     * @param mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $this->response = new CompletePurchaseResponse($this, $data);

        $originalTransactionId = $this->getTransactionId();
        $returnedTransactionId = $this->response->getTransactionId();

        if (empty($originalTransactionId)) {
            throw new InvalidRequestException('Missing expected transactionId parameter');
        }

        if ($originalTransactionId !== $returnedTransactionId) {
            throw new InvalidResponseException(
                sprintf(
                    'Unexpected transactionId; expected "%s" received "%s"',
                    $originalTransactionId,
                    $returnedTransactionId
                )
            );
        }

        return $this->response;
    }
}
