<?php namespace Ordercloud\Cart;

use Ordercloud\Entities\Products\Product;
use Ordercloud\Entities\Products\ProductOption;
use Ordercloud\Entities\Products\ProductOptionSet;

class CartItemOption
{
    /** @var ProductOptionSet */
    private $optionSet;
    /** @var ProductOption */
    private $option;

    /**
     * @param $optionSet
     * @param $option
     */
    public function __construct($optionSet, $option)
    {
        $this->optionSet = $optionSet;
        $this->option = $option;
    }

    /**
     * @param array   $selectedOptions
     * @param Product $product
     *
     * @return array|static[]
     */
    public static function createFromArray(array $selectedOptions, Product $product) // TODO
    {
        $options = [];

        foreach ($selectedOptions as $optionSetId => $optionId) {
            $optionSet = $product->getOptionSetByID($optionSetId);

            $option = $optionSet->getOptionByID($optionId);

            $options[] = new static($optionSet, $option);
        }

        return $options;
    }

    /**
     * @return ProductOptionSet
     */
    public function getOptionSet()
    {
        return $this->optionSet;
    }

    /**
     * @return ProductOption
     */
    public function getOption()
    {
        return $this->option;
    }
}
