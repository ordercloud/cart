<?php namespace Ordercloud\Cart;

use Ordercloud\Entities\Organisations\OrganisationShort;
use Ordercloud\Entities\Products\Product;

class Cart
{
    /** @var int */
    private $id;
    /** @var array|CartItem[] */
    private $items = [];

    public function __construct($id, array $items = [])
    {
        $this->id = $id;
        $this->items = $items;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function addItem(Product $product, $quantity = 1, array $options = [], array $extras = [])
    {
        $item = new CartItem($product, $quantity, $options, $extras);

        $this->items[$item->getPuid()] = $item;
    }

    /**
     * @param $itemPUID
     */
    public function removeItem($itemPUID)
    {
        if ( ! isset($this->items[$itemPUID])) {
            return; // TODO should we throw exception?
        }

        unset($this->items[$itemPUID]);
    }

    /**
     * @param $itemPuid
     * @param $quantity
     */
    public function updateItemQuantity($itemPuid, $quantity)
    {
        if ($quantity > 0) {
            $this->items[$itemPuid]->setQuantity($quantity);
        }
        else {
            $this->removeItem($itemPuid);
        }
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
     * @return CartItem|null
     */
    public function getItemByPuid($puid)
    {
        foreach ($this->getItems() as $item) {
            if ($item->getPuid() == $puid) {
                return $item;
            }
        }

        return null;
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
        return in_array($merchantId, array_keys($this->getProductOrganisations()));
    }

    /**
     * @return array|Organisation[]
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
        return array_filter($this->getItems(), function ($item) use ($merchantID)
        {
            return $item->getProduct()->getOrganisation()->getId() == $merchantID;
        });
    }
}
