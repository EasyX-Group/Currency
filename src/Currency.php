<?php namespace EasyX;

use EasyX\Adapters\Cbr;

class Currency
{
	public $name;

	public function __construct(string $name = 'RUB')
	{
		$this->name = $name;
	}

	public function exchange(float $amount, string $to): float
	{
		return Cbr::exchange($this->name, $amount, $to);
	}

	public function __toString()
	{
		return $this->name;
	}
}
