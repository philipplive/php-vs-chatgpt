<?php

namespace ChatGPT;

use ChatGPT\Objects\Assistant;
use ChatGPT\Objects\Model;
use ChatGPT\Objects\Run;
use ChatGPT\Objects\Thread;

/**
 * @link https://platform.openai.com/
 * @doc https://platform.openai.com/docs/api-reference
 */
class API {
	/**
	 * ChatGPT Schlüssel
	 * @var string
	 */
	public string $apiKey = '';

	/**
	 * Defaultmodel
	 * @var string
	 */
	private string $model = 'gpt-4o'; // gpt-4o-mini, gpt-4o, gpt-3.5-turbo, gpt-4o and gpt-4-turbo, eigenes...

	/**
	 * Standardmodel für hohe Performance
	 */
	const MODEL_PERFORMANCE = 'gpt-4o-mini';

	/**
	 * Standardmodel für hohe Antwortqualität
	 */
	const MODEL_QUALITY = 'gpt-4o';

	/**
	 * @param string $apiKey
	 */
	public function __construct(string $apiKey) {
		$this->apiKey = $apiKey;
	}

	/**
	 * Standardmodel in diesem Kontext setzen
	 * @param string $model
	 * @return self
	 */
	public function setModel(string $model): self {
		$this->model = $model;
		return $this;
	}

	public function curlRequest(array $param, string|array $endpoint = 'chat/completions', string $method = 'POST'): array {
		if (is_array($endpoint))
			$endpoint = implode('/', $endpoint);

		$ch = curl_init();

		// Header setzen
		$headers = [
			'Authorization: Bearer '.$this->apiKey,
			'OpenAI-Beta: assistants=v2',
			'Content-Type: application/json'
		];

		// Optionen setzen
		curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/'.$endpoint);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if ($method === 'POST' || $method === 'PUT')
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));

		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (curl_errno($ch))
			throw new \Exception('cURL error: '.curl_error($ch));

		curl_close($ch);

		$json = json_decode($response, true);

		if ($httpCode !== 200) {
			$errorMessage = $json['error']['message'] ?? 'Unknown error';
			throw new \Exception($errorMessage);
		}

		return $json;
	}

	/**
	 * Objektliste abfragen
	 * @param string|array $endpoint
	 * @param ApiObject $prototype
	 * @return ApiObject[]
	 */
	public function requestObjects(string|array $endpoint, ApiObject $prototype): array {
		$items = [];

		foreach ($this->curlRequest([], $endpoint, 'GET')['data'] as $data) {
			$items[] = $item = new $prototype($this, $data['id']);
			$item->fetchInApiData($data);
		}

		return $items;
	}

	/**
	 * Objektliste abfragen
	 * @param string|array $endpoint
	 * @param ApiObject $prototype
	 * @return ApiObject
	 */
	public function requestObject(string|array $endpoint, ApiObject $prototype): ApiObject {
		$data = $this->curlRequest([], $endpoint, 'GET');
		$item = new $prototype($this, $data['id']);
		$item->fetchInApiData($data);

		return $item;
	}

	/**
	 * Regulärer Textrequest
	 * @param string $systemText
	 * @param string $userText
	 * @param string|null $assistantId
	 * @return string
	 */
	public function complexeRequest(string $systemText, string $userText, string|null $assistantId = null): string {
		$thread = $this->createThread();

		$thread->addTextMessage($systemText, Role::ASSISTANT);
		$thread->addTextMessage($userText, Role::USER);

		if ($assistantId)
			$thread->setAssistantId($assistantId);

		$run = $thread->run();

		while (!$run->isComplete())
			sleep(1);

		return $thread->getLastMessage()->msg;
	}

	/**
	 * Einfacher Textrequest
	 * @param string $systemText
	 * @param string $userText
	 * @param string|null $model
	 * @return string
	 */
	public function simpleRequest(string $systemText, string $userText, string|null $model = null): string {
		$bodyParameters = array(
			"model" => $model ?? $this->model,
			"messages" => array(
				array(
					"role" => "system",
					"content" => $systemText
				),
				array(
					"role" => "user",
					"content" => $userText
				)
			)
		);

		$response = $this->curlRequest($bodyParameters);

		foreach ($response['choices'] as $choice)
			return $choice['message']['content'];

		return '';
	}

	/**
	 * Neuen Thread erstellen
	 * @return Thread
	 */
	public function createThread(): Thread {
		$data = $this->curlRequest([], 'threads');

		return $this->getThreadById($data['id']);
	}

	public function getThreadById(string $id): Thread {
		return new Thread($this, $id);
	}

	/**
	 * @return Objects\Assistant[]
	 */
	public function getAssistants(): array {
		return $this->requestObjects('assistants', new Assistant());
	}

	public function getAssistantById(string $id): Assistant {
		return $this->requestObject(['assistants', $id], new Assistant());
	}

	/**
	 * @return Objects\Model[]
	 */
	public function getModels(): array {
		return $this->requestObjects('models', new Model());
	}
}