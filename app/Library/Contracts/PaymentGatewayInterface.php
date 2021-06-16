<?php

namespace Acelle\Library\Contracts;

interface PaymentGatewayInterface
{
    public function getName() : string;
    public function getType() : string;
    public function getDescription() : string;
    public function isActive() : bool;
    public function getSettingsUrl() : string;
    public function getCheckoutUrl($invoice) : string;

    public function supportsAutoBilling() : bool;
    public function autoCharge($invoice);
    public function getAutoBillingDataUpdateUrl() : string;
}
