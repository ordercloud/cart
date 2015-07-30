<?php namespace Ordercloud\Cart\Exceptions;

use Exception;
use Ordercloud\Cart\Entities\CartItem;

class CartItemException extends Exception
{
    /**
     * @var CartItem
     */
    private $item;

    /**
     * @param string   $message
     * @param CartItem $item
     */
    public function __construct($message, CartItem $item)
    {
        parent::__construct($message);
        $this->item = $item;
    }

    /**
     * @return CartItem
     */
    public function getItem()
    {
        return $this->item;
    }
}
