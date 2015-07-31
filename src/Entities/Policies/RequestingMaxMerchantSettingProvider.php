<?php namespace Ordercloud\Cart\Entities\Policies;

use Ordercloud\Ordercloud;
use Ordercloud\Requests\Settings\GetSettingsByOrganisationRequest;

class RequestingMaxMerchantSettingProvider implements MaxMerchantSettingProvider
{
    /**
     * @var Ordercloud
     */
    private $ordercloud;
    /**
     * @var int
     */
    private $organisationId;

    /**
     * @param Ordercloud $ordercloud
     * @param int        $organisationId
     */
    public function __construct(Ordercloud $ordercloud, $organisationId)
    {
        $this->ordercloud = $ordercloud;
        $this->organisationId = $organisationId;
    }

    /**
     * @return int
     */
    public function getMax()
    {
        $settings = $this->ordercloud->exec(
            new GetSettingsByOrganisationRequest($this->organisationId)
        );

        return $settings->getValueByKeyName('max number of shops');
    }
}
