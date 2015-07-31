<?php namespace spec\Ordercloud\Cart\Entities\Policies;

use Ordercloud\Cart\Entities\CartItem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BaseCartPolicySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ordercloud\Cart\Entities\Policies\BaseCartPolicy');
    }

    function it_can_add_an_item(CartItem $cartItem1, CartItem $cartItem2)
    {
        $itemsBefore = [$cartItem1];
        $itemsAfter = [$cartItem1, $cartItem2];

        $this->add($cartItem2, $itemsBefore)->shouldReturn($itemsAfter);
    }

    function it_can_remove_an_item(CartItem $cartItem1, CartItem $cartItem2)
    {
        $cartItem1->getPuid()->willReturn('123');
        $cartItem2->getPuid()->willReturn('456');

        $itemsBefore = [$cartItem1, $cartItem2];
        $itemsAfter = [$cartItem2];

        $this->remove($cartItem1, $itemsBefore)->shouldReturn($itemsAfter);
    }

    function it_can_update_an_item(CartItem $originalItem, CartItem $updatedItem)
    {
        $originalItem->getPuid()->willReturn('123');
        $updatedItem->getPuid()->willReturn('456');

        $itemsBefore = [$originalItem];
        $itemsAfter = [$updatedItem];

        $this->update($originalItem, $updatedItem, $itemsBefore)->shouldReturn($itemsAfter);
    }
}
