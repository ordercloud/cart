<?php namespace Ordercloud\Cart\Exceptions;

use Exception;
use Ordercloud\Cart\Entities\Cart;

class CartException extends Exception
{
    /**
     * @var Cart|null
     */
    private $cart;

    /**
     * @param Cart|null      $cart
     * @param string         $message
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct(Cart $cart = null, $message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->cart = $cart;
    }

    /**
     * @return Cart|null
     */
    public function getCart()
    {
        return $this->cart;
    }
}
