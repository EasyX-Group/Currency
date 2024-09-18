<?php namespace EasyX\Adapters;

use Cache;
use Carbon\Carbon;

class Valute
{
	const CBR_CURRENCY_RATES_URL = 'https://www.cbr.ru/scripts/XML_daily.asp';

	const CURRENCY_SYMBOLS = [
		'RUB' => '₽',
		'AUD' => '$A',
		'AZN' => '₼',
		'GBP' => '£',
		'AMD' => '֏',
		'BYN' => 'Br',
		'BGN' => 'лв',
		'BRL' => 'R$',
		'HUF' => 'ƒ',
		'VND' => '₫',
		'HKD' => 'HK$',
		'GEL' => '₾',
		'DKK' => 'kr',
		'AED' => 'Dh',
		'USD' => '$',
		'EUR' => '€',
		'EGP' => 'LE',
		'INR' => '₹',
		'IDR' => 'Rp',
		'KZT' => '₸',
		'CAD' => 'C$',
		'QAR' => 'QR',
		'KGS' => 'с',
		'CNY' => '¥',
		'MDL' => 'L',
		'NZD' => 'NZ$',
		'NOK' => 'kr',
		'PLN' => 'zł',
		'RON' => 'L',
		'XDR' => 'SDR',
		'SGD' => 'S$',
		'TJS' => 'SM',
		'THB' => '฿',
		'TRY' => '₺',
		'TMT' => 'TMT',
		'UZS' => 'UZS',
		'UAH' => '₴',
		'CZK' => 'Kč',
		'SEK' => 'kr',
		'CHF' => 'Fr',
		'RSD' => 'din',
		'ZAR' => 'R',
		'KRW' => '₩',
		'JPY' => '¥',
	];

	public string $name;

	public float $nominal;

	public string $code;

	public float $amount;

	public string $symbol;

	public string $human;

	public string $symbolPosition;

	private array $rates = [];

	private array $valute = [];

	public function __construct($valute)
	{
		if (!$this->rates) {
			$this->resolveCurrencyRates();
		}

		if (!$this->valute) {
			$this->valute = $this->valute($valute['code']);
		}

		$this->name = $valute['name'] ?? $this->valute['name'];
		$this->nominal = $valute['nominal'] ?? $this->valute['nominal'];
		$this->code = $valute['code'];
		$this->amount = $valute['amount'];
		$this->symbol = $this->getSymbol();
		$this->symbolPosition = $this->getSymbolPosition();
	}

	public function valute(string $code): array|null {
		if ($code == 'RUB') {
			return [
				'name' => 'Российский рубль',
				'code' => $code,
				'nominal' => 1.0,
				'amount' => 1.0,
			];
		}

		foreach ($this->rates['Valute'] as $valute) {
			if ($valute['CharCode'] == $code) {
				return [
					'name' => $valute['Name'],
					'code' => $code,
					'nominal' => 1.0,
					'amount' => (float)$valute['VunitRate'],
				];
			}
		}

		return null;
	}

	private function resolveCurrencyRates(): void
	{
		$this->rates = Cache::remember('easyx_currency_cbr_rates', Carbon::now()->addMinutes(30), function () {
			$curl = curl_init();

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_POST, false);
			curl_setopt($curl, CURLOPT_URL, $this::CBR_CURRENCY_RATES_URL);

			$response = curl_exec($curl);
			$xml = simplexml_load_string($response);

			return @json_decode(@json_encode($xml), true);
		});
	}

	public function convert(string $to): self|null
	{
		if ($to == 'RUB' && $this->code != 'RUB') {
			if (!$valute = $this->valute($this->code)) {
				return null;
			}

			$amount = round($valute['amount'] * $this->amount / $valute['nominal'], 4);

			return new self([
				'code' => 'RUB',
				'amount' => $amount,
				'nominal' => $amount,
			]);
		} else {
			if (!$valute = $this->valute($to)) {
				return null;
			}

			$amount = round($this->amount / $valute['amount'] / $valute['nominal'], 4);

			return new self([
				'code' => $to,
				'amount' => $amount,
				'nominal' => $amount,
			]);
		}
	}

	private function getSymbol(): string|null {
		return $this::CURRENCY_SYMBOLS[$this->code] ?? null;
	}

	private function getSymbolPosition(): string {
		if (in_array($this->symbol, [
			'AUD',
			'GBP',
			'GBP',
			'BRL',
			'HKD',
			'DKK',
			'USD',
			'EUR',
			'EGP',
			'INR',
			'IDR',
			'CAD',
			'QAR',
			'CNY',
			'NZD',
			'NOK',
			'XDR',
			'SGD',
			'CHF',
			'ZAR',
			'KRW',
			'JPY',
		])) {
			return 'left';
		}

		return 'right';
	}

	public function __toString()
	{
		return $this->amount;
	}
}
