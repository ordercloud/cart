<?php namespace Ordercloud\Cart\Entities\Policies;

use Ordercloud\Cart\Entities\CartItem;
use Ordercloud\Cart\Exceptions\MaxMerchantsException;
use Ordercloud\Entities\Organisations\OrganisationShort;

class LimitedMerchantCartPolicy extends BaseCartPolicy implements CartPolicy
{
    /**
     * @var MaxMerchantSettingProvider
     */
    private $maxMerchantSettingProvider;

    public function __construct(MaxMerchantSettingProvider $maxMerchantSettingProvider)
    {
        $this->maxMerchantSettingProvider = $maxMerchantSettingProvider;
    }

    public function add(CartItem $newItem, array $items)
    {
        $this->varifyCanAddProduct($newItem, $items); // TODO: This feels wrong

        return parent::add($newItem, $items);
    }

    /**
     * @param CartItem         $item
     * @param array|CartItem[] $items
     *
     * @throws MaxMerchantsException
     */
    protected function varifyCanAddProduct(CartItem $item, array $items)
    {
        if ($this->isItemMerchantInCart($item->getProduct()->getOrganisation(), $items)) {
            return;
        }

        $merchantMax = $this->maxMerchantSettingProvider->getMax();
        $newMerchantCount = sizeof($this->getMerchants($items)) + 1;

        if ($newMerchantCount > $merchantMax) {
            throw new MaxMerchantsException($item, $merchantMax);
        }
    }

    /**
     * @param OrganisationShort $newItemMerchant
     * @param array|CartItem[] $items
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
     * @param array|CartItem[] $items
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
}
