<?php

namespace ChatGPT\Objects;

/**
 * @doc https://platform.openai.com/docs/api-reference/assistants/
 */
class Assistant extends \ChatGPT\ApiObject {
	public string $name = '';
	public string $instructions = '';
	public string $description = '';
	public float $topp = 1.0;
	public float $temperature = 1.0;
	public string $model = '';
	public array $indices = ['name', 'instructions', 'topp' => 'top_p', 'temperature', 'model', 'description'];

	public function save(): void {
		$this->api->curlRequest($this->fetchOutApiData(), ['assistants', $this->id]);
	}

	public function create(): void {
		$this->api->curlRequest(['model' => \ChatGPT\API::MODEL_QUALITY, 'name' => $this->name], ['assistants']);
	}

	public function delete(): void {
		$this->api->curlRequest([], ['assistants', $this->id], 'DELETE');
	}

}