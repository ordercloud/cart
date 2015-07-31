<?php namespace Ordercloud\Cart\Entities\Policies;

use Ordercloud\Cart\Entities\CartItem;
use Ordercloud\Cart\Exceptions\MaxMerchantsException;

class LimitedMerchantCartPolicy extends BaseCartPolicy implements CartPolicy
{
    /**
     * @var int
     */
    private $maxNrMerchants;

    /**
     * @param int $maxNrMerchants
     */
    public function __construct($maxNrMerchants)
    {
        $this->maxNrMerchants = $maxNrMerchants;
    }

    public function add(CartItem $newItem, array $items)
    {
        $newItems = parent::add($newItem, $items);

        if ($this->getMerchantCount($newItems) > $this->maxNrMerchants) {
            throw new MaxMerchantsException($newItem, $this->maxNrMerchants);
        }

        return $newItems;
    }

    /**
     * @param array|CartItem[] $items
     *
     * @return int
     */
    protected function getMerchantCount(array $items)
    {
        $merchants = [];

        foreach ($items as $item) {
            $merchants[] = $item->getProduct()->getOrganisation()->getId();
        }

        $uniqueMerchants = array_unique($merchants);

        return count($uniqueMerchants);
    }
}
