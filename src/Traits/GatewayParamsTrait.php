<?php

namespace Omnipay\TrustPayments\Traits;

trait GatewayParamsTrait
{
    public function setSitereference($value)
    {
        return $this->setParameter('sitereference', $value);
    }

    public function getSitereference()
    {
        if ($this->getTestMode()) {
            return 'test_' . $this->getParameter('sitereference');
        }

        return $this->getParameter('sitereference');
    }

    public function setEncryptionKey($value)
    {
        return $this->setParameter('encryptionKey', $value);
    }

    public function getEncryptionKey()
    {
        return $this->getParameter('encryptionKey');
    }

    public function setStprofile($value)
    {
        return $this->setParameter('stprofile', $value);
    }

    public function getStprofile()
    {
        return $this->getParameter('stprofile');
    }

    public function setVersion($value)
    {
        return $this->setParameter('version', $value);
    }

    public function getVersion()
    {
        return $this->getParameter('version');
    }

    public function setBillingForShipping($value)
    {
        return $this->setParameter('billingForShipping', $value);
    }

    public function getBillingForShipping()
    {
        return (bool) $this->getParameter('billingForShipping');
    }
}
