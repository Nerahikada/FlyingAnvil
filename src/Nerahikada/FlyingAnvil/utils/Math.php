<?php

declare(strict_types=1);

namespace Nerahikada\FlyingAnvil\utils;

use function mt_getrandmax;
use function mt_rand;

class Math{

	/**
	 * @param float|int $min
	 * @param float|int $max
	 */
	public static function randomFloat($min = 0, $max = 1) : float{
		return $min + mt_rand() / mt_getrandmax() * ($max - $min);
	}
}