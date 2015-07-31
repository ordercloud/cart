<?php namespace Ordercloud\Cart\Entities;

use Ordercloud\Cart\Entities\Policies\BaseCartPolicy;
use Ordercloud\Cart\Entities\Policies\CartPolicy;
use Ordercloud\Cart\Exceptions\CartItemNotFoundException;
use Ordercloud\Entities\Organisations\OrganisationShort;
use Ordercloud\Entities\Products\Product;

class Cart
{
    /** @var string */
    private $id;
    /** @var array|CartItem[] */
    private $items = [];
    /**
     * @var CartPolicy
     */
    private $policy;

    /**
     * @param string     $id
     * @param CartPolicy $policy
     */
    public function __construct($id, CartPolicy $policy = null)
    {
        $this->id = $id;
        $this->policy = is_null($policy) ? new BaseCartPolicy() : $policy;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Product $product
     * @param int     $quantity
     * @param array   $options
     * @param array   $extras
     */
    public function addItem(Product $product, $quantity = 1, array $options = [], array $extras = [])
    {
        $item = new CartItem($product, $quantity, $options, $extras);

        $this->items = $this->policy->add($item, $this->items);
    }

    /**
     * @param $itemPUID
     */
    public function removeItem($itemPUID)
    {
        $item = $this->getItemByPuid($itemPUID);

        $this->items = $this->policy->remove($item, $this->items);
    }

    /**
     * @param $itemPuid
     * @param $quantity
     */
    public function updateItemQuantity($itemPuid, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeItem($itemPuid);
            return;
        }

        $originalItem = $this->getItemByPuid($itemPuid);
        $updatedItem = $this->getItemByPuid($itemPuid);

        $updatedItem->setQuantity($quantity);

        $this->items = $this->policy->update($originalItem, $updatedItem, $this->items);
    }

    /**
     * @return array|CartItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param string $puid
     *
     * @return CartItem
     *
     * @throws CartItemNotFoundException
     */
    public function getItemByPuid($puid)
    {
        foreach ($this->getItems() as $item) {
            if ($item->getPuid() == $puid) {
                return $item;
            }
        }

        throw new CartItemNotFoundException($puid, $this);
    }

    /**
     * @return int
     */
    public function getItemCount()
    {
        $itemCount = 0;

        foreach ($this->getItems() as $item) {
            $itemCount += $item->getQuantity();
        }

        return $itemCount;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * @return float
     */
    public function getTotalAmount()
    {
        $amount = 0;

        foreach ($this->getItems() as $item) {
            $amount = bcadd($amount, $item->getAmount(), 2);
        }

        return floatval($amount);
    }

    /**
     * Check if there are any items in the cart from the specified merchant
     *
     * @param int $merchantId
     *
     * @return bool
     */
    public function isProductOfMerchantInCart($merchantId)
    {
        return in_array($merchantId, array_keys($this->getMerchantsOfProducts()));
    }

    /**
     * @return array|OrganisationShort[]
     */
    public function getMerchantsOfProducts()
    {
        $productMerchants = [];

        foreach ($this->getItems() as $item) {
            $organisation = $item->getProduct()->getOrganisation();

            $productMerchants[$organisation->getId()] = $organisation;
        }

        return $productMerchants;
    }

    /**
     * @param int $merchantID
     *
     * @return array
     */
    public function getItemsByMerchantID($merchantID)
    {
        return array_filter($this->getItems(), function (CartItem $item) use ($merchantID)
        {
            return $item->getProduct()->getOrganisation()->getId() == $merchantID;
        });
    }
}
