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

        foreach ($selectedExtras as $extra) {
            list($setId, $extraId) = explode('_', $extra);

            $extraSet = $product->getExtraSetByID($setId);

            $extras[] = new static($extraSet, $extraSet->getExtraByID($extraId));
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
