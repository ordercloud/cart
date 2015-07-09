<?php namespace Ordercloud\Cart;

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
