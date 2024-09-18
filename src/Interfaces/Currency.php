<?php namespace EasyX\Interfaces;

interface Currency
{
	public function exchange(float $amount, string $from, string $to): float;
}
