<?php namespace Ordercloud\Cart\Entities\Policies;

use Ordercloud\Cart\Entities\CartItem;

class BaseCartPolicy implements CartPolicy
{
    public function add(CartItem $newItem, array $items)
    {
        $items[$newItem->getPuid()] = $newItem;

        return $items;
    }

    public function remove(CartItem $removedItem, array $items)
    {
        $puid = $removedItem->getPuid();

        if ( ! isset($this->items[$puid])) {
            return $items; // TODO should we throw exception?
        }

        unset($this->items[$puid]);

        return $items;
    }

    public function update(CartItem $originalItem, CartItem $updatedItem, array $items)
    {
        $items[$originalItem->getPuid()] = $updatedItem;

        return $items;
    }
}
