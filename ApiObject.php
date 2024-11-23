<?php

namespace ChatGPT;

class ApiObject {
	public API|null $api = null;

	/**
	 * @var string
	 */
	public string $id;

	/**
	 * @var array Indices die zum speichern und auslesen verwendet werden
	 */
	public array $indices = [];

	public function __construct(API $api = null, string $id = '') {
		$this->api = $api;
		$this->id = $id;
	}

	public function fetchInApiData(array $data, array|null $indices = null): void {
		$indices = $indices ?? $this->indices;

		foreach ($indices as $object => $api) {
			if(is_int($object))
				$object = $api;

			$value = $data[$api];

			if($value === null){
				if(is_string($this->$object)) {
					$this->$object = '';
					continue;
				}
				else
					throw new \Exception('Noch nicht implementiert');
			}

			$this->$object = $value;
		}
	}

	public function fetchOutApiData(array|null $indices = null): array{
		$indices = $indices ?? $this->indices;
		$data = [];

		foreach ($indices as $object => $api) {
			if(is_int($object))
				$object = $api;

			$data[$api] = $this->$object;
		}

		return $data;
	}
}