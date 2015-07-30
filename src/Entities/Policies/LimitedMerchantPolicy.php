<?php namespace Ordercloud\Cart\Entities\Policies;

use Ordercloud\Cart\Entities\CartItem;
use Ordercloud\Cart\Exceptions\CartItemException;
use Ordercloud\Entities\Organisations\OrganisationShort;
use Ordercloud\Ordercloud;
use Ordercloud\Requests\Settings\GetSettingsByOrganisationRequest;

class LimitedMerchantCartPolicy extends BaseCartPolicy implements CartPolicy
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

    public function add(CartItem $newItem, array $items)
    {
        $this->varifyCanAddProduct($newItem, $items); // TODO: This feels wrong

        return parent::add($newItem, $items);
    }

    /**
     * @param \Ordercloud\Cart\Entities\CartItem         $item
     * @param array|\Ordercloud\Cart\Entities\CartItem[] $items
     *
     * @return bool
     *
     * @throws \Ordercloud\Cart\Exceptions\CartItemException
     */
    protected function varifyCanAddProduct(CartItem $item, array $items)
    {
        if ($this->isItemMerchantInCart($item->getProduct()->getOrganisation(), $items)) {
            return;
        }

        $merchantMax = $this->getMerchantMax();
        $newMerchantCount = sizeof($this->getMerchants($items)) + 1;

        if ($newMerchantCount > $merchantMax) {
            throw CartItemException::maxMerchants($merchantMax, $item);
        }
    }

    /**
     * @param OrganisationShort $newItemMerchant
     * @param array|\Ordercloud\Cart\Entities\CartItem[]  $items
     *
     * @return bool
     */
    protected function isItemMerchantInCart(OrganisationShort $newItemMerchant, array $items)
    {
        foreach ($this->getMerchants($items) as $merchant) {
            if ($merchant->getId() == $newItemMerchant->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array|\Ordercloud\Cart\Entities\CartItem[] $items
     *
     * @return array|OrganisationShort[]
     */
    protected function getMerchants(array $items)
    {
        $merchants = [];

        foreach ($items as $item) {
            $merchants[] = $item->getProduct()->getOrganisation();
        }

        return $merchants;
    }

    /**
     * @return int
     */
    protected function getMerchantMax()
    {
        $settings = $this->ordercloud->exec(
            new GetSettingsByOrganisationRequest($this->organisationId)
        );

        return $settings->getValueByKeyName('max number of shops');
    }
}
