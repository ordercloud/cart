<?php namespace Ordercloud\Cart\Infrastructure;

use Ordercloud\Cart\Entities\Cart;

interface CartRepository
{
    /**
     * @param int $id
     *
     * @return Cart
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
