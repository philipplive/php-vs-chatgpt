<?php

namespace ChatGPT\Objects;

class VectorStoreFile extends \ChatGPT\ApiObject {
	/**
	 * Name
	 * @var string
	 */
	public string $name = '';

	public string $vectorStoreId = '';

	public array $indices = ['name' => 'object', 'vectorStoreId' => 'vector_store_id'];

	public function delete(): void {
		$this->api->curlRequest([], ['vector_stores', $this->vectorStoreId, 'files', $this->id], 'DELETE');
	}
}