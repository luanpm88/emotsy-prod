<?php
namespace Acelle\Library;

use Exception;
use Acelle\Library\Contracts\PaymentGatewayInterface;

class BillingManager {
    protected $gateways = [];

    private function validate(PaymentGatewayInterface $gateway)
    {
        foreach ($this->gateways as $gw) {
            if ($gw->getType() == $gateway->getType()) {
                throw new Exception('Gateways type is already registered: ' . $gateway->getType());
            }
        }
    }
    
    public function register(PaymentGatewayInterface $gateway)
    {   
        $this->validate($gateway);
        $this->gateways[] = $gateway;
    }

    public function getGateways() : array
    {
        return $this->gateways;
    }

    public function getGateway(string $type) : PaymentGatewayInterface
    {
        
        foreach ($this->gateways as $gw) {
            if ($gw->getType() == $type) {
                return $gw;
            }
        }
    }
}