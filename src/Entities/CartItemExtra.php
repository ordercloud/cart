<?php namespace Ordercloud\Cart\Entities;

use Ordercloud\Entities\Products\Product;
use Ordercloud\Entities\Products\ProductExtra;
use Ordercloud\Entities\Products\ProductExtraSet;

class CartItemExtra
{
    /** @var ProductExtraSet */
    private $extraSet;
    /** @var ProductExtra */
    private $extra;

    /**
     * @param $extraSet
     * @param $extra
     */
    public function __construct($extraSet, $extra)
    {
        $this->extraSet = $extraSet;
        $this->extra = $extra;
    }

    /**
     * @param array   $selectedExtras
     * @param Product $product
     *
     * @return array|static[]
     */
    public static function createFromArray(array $selectedExtras, Product $product) // TODO
    {
        $extras = [];

        foreach ($selectedExtras as $setID => $extraID) {
            $extraSet = $product->getExtraSetByID($setID);

            $extra = $extraSet->getExtraByID($extraID);

            $extras[] = new static($extraSet, $extra);
        }

        return $extras;
    }

    /**
     * @return ProductExtraSet
     */
    public function getExtraSet()
    {
        return $this->extraSet;
    }

    /**
     * @return ProductExtra
     */
    public function getExtra()
    {
        return $this->extra;
    }
}
