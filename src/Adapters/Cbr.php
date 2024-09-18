<?php namespace EasyX\Adapters;

use EasyX\Interfaces\Currency;

class Cbr implements Currency
{
	const CBR_CURRENCY_RATES_URL = 'https://www.cbr.ru/scripts/XML_daily.asp';

	private array $rates = [];

	public function __construct()
	{
		if (!$this->rates) {
			$this->resolveCurrencyRates();
		}
	}

	public function exchange(float $amount, string $from, string $to): float
	{

	}

	private function resolveCurrencyRates(): void
	{
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_POST, false);
		curl_setopt($curl, CURLOPT_URL, $this::CBR_CURRENCY_RATES_URL);

		$response = curl_exec($curl);
		$xml = simplexml_load_string($response);
		$this->rates = @json_decode(@json_encode($xml), true);
	}
}
