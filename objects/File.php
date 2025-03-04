<?php

namespace ChatGPT\Objects;

class File extends \ChatGPT\ApiObject {

	/**
	 * @var string
	 */
	public string $name = '';

	/**
	 * @var string
	 */
	public string $purpose = '';

	/**
	 * Grösse in Byte
	 * @var int
	 */
	public int $size = 0;

	public \DateTime|null $created = null;

	public array $indices = ['name' => 'filename', 'purpose', 'size' => 'bytes'];

	public function delete(): void {
		$this->api->curlRequest([], ['files', $this->id], 'DELETE');
	}

	public static function createFromData(\ChatGPT\API $api, array $data): self {
		$item = new self($api, $data['id']);

		$item->fetchInApiData($data);

		try {
			$item->created = new \DateTime($data['created_at']);
		} catch (\Exception $ex) {

		}

		return $item;
	}
}