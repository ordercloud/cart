<?php namespace Ordercloud\Cart\Entities\Policies;

use Ordercloud\Cart\Entities\CartItem;
use Ordercloud\Cart\Exceptions\CartItemNotFoundException;

class BaseCartPolicy implements CartPolicy
{
    public function add(CartItem $newItem, array $items)
    {
        $items[] = $newItem;

        return $items;
    }

    public function remove(CartItem $removedItem, array $items)
    {
        $newItems = [];

        foreach ($items as $item) {
            if ($removedItem->getPuid() != $item->getPuid()) {
                $newItems[] = $item;
            }
        }

        return $newItems;
    }

    public function update(CartItem $originalItem, CartItem $updatedItem, array $items)
    {
        for ($i = 0; $i < sizeof($items); $i++) {
            if ($items[$i]->getPuid() == $originalItem->getPuid()) {
                $items[$i] = $updatedItem;
            }
        }

        return $items;
    }
}
