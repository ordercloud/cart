<?php namespace Ordercloud\Cart;

use Predis\Client;

class RedisCartRepository implements CartRepository
{
    /** @var Client */
    private $redis;

    /**
     * @param Client $redis
     */
    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param int $id
     *
     * @return Cart|null
     */
    public function findById($id)
    {
        if ($this->redis->exists($this->getCartKey($id))) {
            return unserialize($this->redis->get($this->getCartKey($id)));
        }

        return null;
    }

    public function save(Cart $cart)
    {
        $this->redis->set($this->getCartKey($cart->getId()), serialize($cart));
    }

    public function destroy(Cart $cart)
    {
        $this->redis->del($this->getCartKey($cart->getId()));
    }

    private function getCartKey($userID)
    {
        return "carts.$userID";
    }
}
