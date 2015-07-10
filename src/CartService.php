<?php namespace Ordercloud\Cart;

use Ordercloud\Entities\Organisations\Organisation;
use Ordercloud\Entities\Products\Product;
use Ordercloud\Ordercloud;

class CartService
{
    /**
     * @var CartRepository
     */
    private $repository;

    /**
     * @param Ordercloud     $ordercloud
     * @param CartRepository $repository
     */
    public function __construct(CartRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Cart
     */
    public function createCart()
    {
        $cart = Cart::create(uniqid());

        $this->saveCart($cart);

        return $cart;
    }

    /**
     * @param string $id
     *
     * @return Cart
     *
     * @throws CartNotFoundException
     */
    public function getCartById($id)
    {
        return $this->repository->findById($id);
    }

    /**
     * @param Cart $cart
     */
    public function saveCart(Cart $cart)
    {
        $this->repository->save($cart);
    }

    /**
     * @param int $cartId
     */
    public function destroyCart($cartId)
    {
        $cart = $this->getCartById($cartId);

        $this->repository->destroy($cart);
    }

    /**
     * @param int     $cartId
     * @param Product $product
     * @param int     $quantity
     * @param array   $options array( optionSetId => optionId, ... )
     * @param array   $extras array( extraSetId => extraId, ... )
     */
    public function addItem($cartId, Product $product, $quantity = 1, array $options = [], array $extras = [])
    {
        $cart = $this->getCartById($cartId);

        $cart->addItem($product, $quantity, $options, $extras);

        $this->saveCart($cart);
    }

    /**
     * @param int    $cartId
     * @param string $itemPuid
     */
    public function removeItem($cartId, $itemPuid)
    {
        $cart = $this->getCartById($cartId);

        $cart->removeItem($itemPuid);

        $this->saveCart($cart);
    }

    /**
     * @param int    $cartId
     * @param string $itemPuid
     * @param int    $quantity
     */
    public function updateItemQuantity($cartId, $itemPuid, $quantity)
    {
        $cart = $this->getCartById($cartId);

        $cart->updateItemQuantity($itemPuid, $quantity);

        $this->saveCart($cart);
    }
}
