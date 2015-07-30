<?php namespace Ordercloud\Cart\Entities\Policies;

use Ordercloud\Cart\Entities\CartItem;

interface CartPolicy
{
    /**
     * @param \Ordercloud\Cart\Entities\CartItem         $newItem
     * @param array|\Ordercloud\Cart\Entities\CartItem[] $items
     *
     * @return CartItem[] Resulting collection
     */
    public function add(CartItem $newItem, array $items);

    /**
     * @param CartItem         $removedItem
     * @param array|\Ordercloud\Cart\Entities\CartItem[] $items
     *
     * @return CartItem[] Resulting collection
     */
    public function remove(CartItem $removedItem, array $items);

    /**
     * @param CartItem         $originalItem
     * @param \Ordercloud\Cart\Entities\CartItem         $updatedItem
     * @param array|CartItem[] $items
     *
     * @return \Ordercloud\Cart\Entities\CartItem[] Resulting collection
     */
    public function update(CartItem $originalItem, CartItem $updatedItem, array $items);
}
