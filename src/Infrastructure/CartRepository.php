<?php namespace Ordercloud\Cart\Infrastructure;

use Ordercloud\Cart\Entities\Cart;
use Ordercloud\Cart\Exceptions\CartNotFoundException;

interface CartRepository
{
    // TODO: add "exists" method

    /**
     * @param int $id
     *
     * @return Cart
     *
     * @throws CartNotFoundException
     */
    public function findById($id);

    /**
     * @param Cart $cart
     */
    public function save(Cart $cart);

    /**
     * @param Cart $cart
     */
    public function destroy(Cart $cart);
}
