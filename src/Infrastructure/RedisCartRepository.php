<?php namespace Ordercloud\Cart\Infrastructure;

use Ordercloud\Cart\Entities\Cart;
use Ordercloud\Cart\Exceptions\CartNotFoundException;
use Predis\Client;

class RedisCartRepository implements CartRepository
{
    /** @var Client */
    private $redis;
    /**
     * @var string|null
     */
    private $keyPrefix;

    /**
     * @param Client      $redis
     * @param string|null $keyPrefix The prefix to use when storing carts in redis
     */
    public function __construct(Client $redis, $keyPrefix = null)
    {
        $this->redis = $redis;
        $this->setKeyPrefix($keyPrefix);
    }

    public function findById($id)
    {
        if ( ! $this->redis->exists($this->getCartKey($id))) {
            throw new CartNotFoundException($id);
        }

        return unserialize($this->redis->get($this->getCartKey($id)));
    }

    public function save(Cart $cart)
    {
        $this->redis->set($this->getCartKey($cart->getId()), serialize($cart));
    }

    public function destroy(Cart $cart)
    {
        $this->redis->del($this->getCartKey($cart->getId()));
    }

    /**
     * @param string $keyPrefix
     */
    protected function setKeyPrefix($keyPrefix)
    {
        if ($keyPrefix) {
            $keyPrefix = rtrim($keyPrefix, '.') . '.';
        }

        $this->keyPrefix = $keyPrefix;
    }

    /**
     * @param string $cartId
     *
     * @return string
     */
    private function getCartKey($cartId)
    {
        return "{$this->keyPrefix}carts.$cartId";
    }
}
