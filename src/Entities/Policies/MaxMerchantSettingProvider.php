<?php namespace Ordercloud\Cart\Entities\Policies;

interface MaxMerchantSettingProvider
{
    /**
     * @return int
     */
    public function getMax();
}
