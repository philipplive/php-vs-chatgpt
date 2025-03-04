<?php

namespace ChatGPT\Objects;

class VectorStore extends \ChatGPT\ApiObject {
	/**
	 * Name
	 * @var string
	 */
	public string $name = '';

	public array $indices = ['name'];

	/**
	 * @return File[]
	 */
	public function getFiles(): array {
		return $this->api->requestObjects(['vector_stores', $this->id, 'files'], new VectorStoreFile());
	}

	public function delete(): void {
		$this->api->curlRequest([], ['vector_stores', $this->id], 'DELETE');
	}

	/**
	 * @param string|File $file Filename oder File
	 * @return void
	 */
	public function add(string|File $file): void {
		if ($file instanceof File)
			$file = $file->id;

		$this->api->curlRequest([], ['vector_stores', $this->id, 'files', $file]);
	}
}