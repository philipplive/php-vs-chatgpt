<?php

namespace ChatGPT;

class Math {
	public static function scalarProduct(array $a, array $b): float {
		if (count($a) !== count($b))
			throw new \Exception("Vektoren müssen die gleiche Länge haben!");

		return array_sum(array_map(fn($x, $y) => $x * $y, $a, $b));
	}
}