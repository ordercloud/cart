<?php namespace Ordercloud\Cart\Exceptions;

use Ordercloud\Cart\Entities\CartItem;

class MaxMerchantsException extends CartItemException
{
    /**
     * @var int
     */
    private $max;

    /**
     * @param CartItem $item
     * @param int      $max
     */
    public function __construct(CartItem $item, $max)
    {
        parent::__construct($item, null, "Could not add item. Max merchants [{$max}] reached.");
        $this->max = $max;
    }

    /**
     * @return int
     */
    public function getMax()
    {
        return $this->max;
    }
}
