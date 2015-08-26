<?php namespace Ordercloud\Cart\Entities;

use Ordercloud\Entities\Products\Product;

class CartItem
{
    /** @var string */
    private $puid;
    /** @var Product */
    private $product;
    /** @var int */
    private $quantity;
    /** @var array|CartItemOption[] */
    private $options;
    /** @var array|CartItemExtra[] */
    private $extras;
    /**
     * @var string
     */
    private $note;

    /**
     * @param Product                $product
     * @param int                    $quantity
     * @param array|CartItemOption[] $options
     * @param array|CartItemExtra[]  $extras
     * @param string                 $note
     */
    public function __construct(Product $product, $quantity = 1, array $options = [], array $extras = [], $note = null)
    {
        $this->puid = uniqid(); //TODO create based on selected product+extras+options
        $this->product = $product;
        $this->quantity = $quantity;
        $this->options = CartItemOption::createFromArray($options, $product);
        $this->extras = CartItemExtra::createFromArray($extras, $product);
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getPuid()
    {
        return $this->puid;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return array|CartItemOption[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return bool
     */
    public function hasOptions()
    {
        $options = $this->getOptions();

        return ! empty($options);
    }

    /**
     * @return array|CartItemExtra[]
     */
    public function getExtras()
    {
        return $this->extras;
    }

    /**
     * @return bool
     */
    public function hasExtras()
    {
        $extras = $this->getExtras();

        return ! empty($extras);
    }

    /**
     * The fully calculated item total. This includes
     * the options, extras & quantity.
     *
     * @return float
     */
    public function getAmount()
    {
        $amount = $this->getPerUnitAmount();

        $totalAmount = bcmul($amount, $this->getQuantity(), 2);

        return floatval($totalAmount);
    }

    /**
     * The calculated item total. This includes the
     * options & extras, but excludes quantity.
     *
     * @return float
     */
    public function getPerUnitAmount()
    {
        $amount = $this->getProduct()->getPrice();

        foreach ($this->getOptions() as $cartOption) {
            $amount = bcadd($amount, $cartOption->getOption()->getPrice(), 2);
        }

        foreach ($this->getExtras() as $cartExtra) {
            $amount = bcadd($amount, $cartExtra->getExtra()->getPrice(), 2);
        }

        return floatval($amount);
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }
}
