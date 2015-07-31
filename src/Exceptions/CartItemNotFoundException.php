<?php namespace Ordercloud\Cart\Exceptions;

use Ordercloud\Cart\Entities\Cart;

class CartItemNotFoundException extends CartItemException
{
    /**
     * @var int
     */
    private $cartItemId;

    /**
     * @param int  $cartItemId
     * @param Cart $cart
     */
    public function __construct($cartItemId, Cart $cart)
    {
        parent::__construct(null, $cart, "Could not find cart item [id={$cartItemId}]");
        $this->cartItemId = $cartItemId;
    }

    /**
     * @return int
     */
    public function getCartItemId()
    {
        return $this->cartItemId;
    }
}
