<?php namespace spec\Ordercloud\Cart\Entities\Policies;

use Ordercloud\Cart\Entities\CartItem;
use Ordercloud\Cart\Entities\Policies\MaxMerchantSettingProvider;
use Ordercloud\Entities\Organisations\OrganisationShort;
use Ordercloud\Entities\Products\Product;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LimitedMerchantCartPolicySpec extends ObjectBehavior
{
    function let()
    {
        $maxNrMerchants = 1;

        $this->beConstructedWith($maxNrMerchants);
    }

    function it_can_add_a_product(CartItem $item, Product $product, OrganisationShort $merchant)
    {
        $item->getProduct()->willReturn($product);
        $product->getOrganisation()->willReturn($merchant);

        $itemsBefore = [];
        $itemsAfter = [$item];

        $this->add($item, $itemsBefore)->shouldReturn($itemsAfter);
    }

    function it_throws_an_exception_when_adding_a_product_over_merchant_max(
        CartItem $item1,
        Product $product1,
        OrganisationShort $merchant1,
        CartItem $item2,
        Product $product2,
        OrganisationShort $merchant2
    ) {
        $item1->getProduct()->willReturn($product1);
        $product1->getOrganisation()->willReturn($merchant1);
        $merchant1->getId()->willReturn(1);

        $item2->getProduct()->willReturn($product2);
        $product2->getOrganisation()->willReturn($merchant2);
        $merchant2->getId()->willReturn(2);

        $itemsBefore = [$item1];

        $this->shouldThrow('Ordercloud\Cart\Exceptions\MaxMerchantsException')
            ->during('add', [$item2, $itemsBefore]);
    }

    function it_can_add_a_product_of_a_merchant_already_in_cart(
        CartItem $item1,
        Product $product1,
        OrganisationShort $merchant1,
        CartItem $item2,
        Product $product2,
        OrganisationShort $merchant2
    ) {
        $item1->getProduct()->willReturn($product1);
        $product1->getOrganisation()->willReturn($merchant1);
        $merchant1->getId()->willReturn(1);

        $item2->getProduct()->willReturn($product2);
        $product2->getOrganisation()->willReturn($merchant2);
        $merchant2->getId()->willReturn(1);

        $itemsBefore = [$item1];
        $itemsAfter = [$item1, $item2];

        $this->add($item2, $itemsBefore)->shouldReturn($itemsAfter);
    }
}
