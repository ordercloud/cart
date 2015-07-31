<?php namespace Ordercloud\Cart;

use Ordercloud\Cart\Entities\Cart;
use Ordercloud\Cart\Entities\Policies\BaseCartPolicy;
use Ordercloud\Cart\Entities\Policies\CartPolicy;
use Ordercloud\Cart\Exceptions\CartItemNotFoundException;
use Ordercloud\Cart\Exceptions\CartNotFoundException;
use Ordercloud\Cart\Infrastructure\CartRepository;
use Ordercloud\Entities\Products\Product;

class CartService
{
    /**
     * @var CartRepository
     */
    private $repository;
    /**
     * @var CartPolicy
     */
    private $cartPolicy;

    /**
     * @param CartRepository $repository
     * @param CartPolicy     $defaultCartPolicy
     */
    public function __construct(CartRepository $repository, CartPolicy $defaultCartPolicy = null)
    {
        $this->repository = $repository;
        $this->cartPolicy = is_null($defaultCartPolicy) ? new BaseCartPolicy() : $defaultCartPolicy;
    }

    /**
     * @return Cart
     */
    public function createCart()
    {
        $cart = new Cart(uniqid(), $this->cartPolicy);

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
     *
     * @throws CartNotFoundException
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
     *
     * @throws CartNotFoundException
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
     *
     * @throws CartNotFoundException
     * @throws CartItemNotFoundException
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
     *
     * @throws CartNotFoundException
     * @throws CartItemNotFoundException
     */
    public function updateItemQuantity($cartId, $itemPuid, $quantity)
    {
        $cart = $this->getCartById($cartId);

        $cart->updateItemQuantity($itemPuid, $quantity);

        $this->saveCart($cart);
    }
}
