<?php

namespace ChatGPT\Objects;

/**
 * @doc https://platform.openai.com/docs/api-reference/assistants/
 */
class Assistant extends \ChatGPT\ApiObject {
	/**
	 * Name
	 * @var string
	 */
	public string $name = '';

	/**
	 * Instruktionen
	 * @var string
	 */
	public string $instructions = '';

	/**
	 * Beschreibung
	 * @var string
	 */
	public string $description = '';

	/**
	 * Begrenzt die Auswahl auf die wahrscheinlichsten Wörter, bis eine bestimmte Gesamtwahrscheinlichkeit erreicht ist, um Antworten kontrollierter zu machen.
	 * @var float  0 - 1
	 */
	public float $topp = 1.0;

	/**
	 * Bestimmt, wie kreativ die Antworten sind – hohe Werte machen sie freier, niedrige Werte genauer.
	 * @var float 0 - 1
	 */
	public float $temperature = 1.0;

	/**
	 * Verwendetes Modell
	 * @var string
	 */
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