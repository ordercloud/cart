<?php namespace Ordercloud\Cart\Exceptions;

class CartNotFoundException extends CartException
{
    /**
     * @var int
     */
    private $cartId;

    /**
     * @param int $cartId
     */
    public function __construct($cartId)
    {
        parent::__construct(null, "Could not find cart [id={$cartId}]");
        $this->cartId = $cartId;
    }

    /**
     * @return int
     */
    public function getCartId()
    {
        return $this->cartId;
    }
}
