<?php namespace Ordercloud\Cart\Exceptions;

use Exception;
use Ordercloud\Cart\Entities\Cart;
use Ordercloud\Cart\Entities\CartItem;

class CartItemException extends CartException
{
    /**
     * @var CartItem|null
     */
    private $item;

    /**
     * @param CartItem|null  $item
     * @param Cart|null      $cart
     * @param string         $message
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct(CartItem $item = null, Cart $cart = null, $message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($cart, $message, $code, $previous);
        $this->item = $item;
    }

    /**
     * @return CartItem|null
     */
    public function getItem()
    {
        return $this->item;
    }
}
