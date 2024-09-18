<?php namespace EasyX;

use EasyX\Adapters\Valute;

class Currency
{
	private $adapter;

	public function __construct(float $amount, string $code = 'RUB')
	{
		$this->adapter = new Valute([
			'amount' => $amount,
			'code' => $code,
			'nominal' => $amount,
		]);
	}

	public function convert(string $to): Valute|null
	{
		if ($this->adapter->code != 'RUB') {
			$this->adapter = $this->adapter->convert('RUB');
		}

		return $this->adapter->convert($to);
	}
}
