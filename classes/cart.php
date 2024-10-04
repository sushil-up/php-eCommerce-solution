<?php

class Cart
{
	protected $cartId;
	private $items = [];
	protected $useCookie = false;

	public function __construct($options = [])
	{
		if (!session_id()) session_start();

		if (isset($options['useCookie']) && $options['useCookie']) {
			$this->useCookie = true;
		}

		$this->cartId = md5((isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : 'SimpleCart') . '_cart';

		$this->read();
	}

	public function getCart( $count = false )
	{
        if( $count ) return count(array_filter($this->items));
		return $this->items;
	}

	public function getCartQuantity()
	{
		$quantity = 0;
		foreach ($this->items as $item) {
            $quantity += $item['quantity'];
		}
		return $quantity;
	}

	public function get($id, $hash = null)
	{
		if($hash){
			$key = array_search($hash, array_column($this->items[$id], 'hash'));
			if($key !== false)
				return $this->items[$id];
			return false;
		}
		else
			return reset($this->items[$id]);
	}

	public function add($id, $quantity = 1)
	{
		global $db;

		$quantity = (preg_match('/^\d+$/', $quantity)) ? $quantity : 1;
		$hash = md5(json_encode([$id,time()]));

		if (isset($this->items[$id])) {
            $this->items[$id]['quantity'] += $quantity;
            $this->write();
            return true;
		}

		$productQuery = "SELECT sales_price FROM `products` WHERE `id`=$id";
        $productObj = $db->select($productQuery,true);

		$this->items[$id] = [
			'id'         => $id,
			'quantity'   => $quantity,
			'price'   	 => $productObj['sales_price'],
			'hash'       => $hash,
		];
		$this->write();
		return true;
	}

	public function update($id, $quantity = 1)
	{
		$quantity = (preg_match('/^\d+$/', $quantity)) ? $quantity : 1;
		if ($quantity == 0) {
			$this->remove($id);
			return true;
		}
		if (isset($this->items[$id])) {
            $this->items[$id]['quantity'] = $quantity;
            $this->write();
            return true;
		}
		return false;
	}

	public function remove($id)
	{
		if (!isset($this->items[$id])) return false;
        unset($this->items[$id]);
        $this->write();
        return true;
	}

	public function destroy()
	{
		$this->items = [];
		if ($this->useCookie) {
			setcookie($this->cartId, '', -1);
		} else {
			unset($_SESSION[$this->cartId]);
		}
	}

	private function read()
	{
		$this->items = ($this->useCookie) ? json_decode((isset($_COOKIE[$this->cartId])) ? $_COOKIE[$this->cartId] : '[]', true) : json_decode((isset($_SESSION[$this->cartId])) ? $_SESSION[$this->cartId] : '[]', true);
	}

	private function write()
	{
		if ($this->useCookie) {
			setcookie($this->cartId, json_encode(array_filter($this->items)), time() + 604800, "/");
		} else {
			$_SESSION[$this->cartId] = json_encode(array_filter($this->items));
		}
	}
}

$cart = new Cart();