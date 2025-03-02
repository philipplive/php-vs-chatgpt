<?php

namespace ChatGPT\Objects;

/**
 * @doc https://platform.openai.com/docs/api-reference/embeddings
 */
class Embedding extends \ChatGPT\ApiObject {
	/**
	 * Modell
	 * @var string
	 */
	public string $model = 'text-embedding-3-small';

	/**
	 * Berechnete Vektoren
	 * @var array
	 */
	public array $vectors = [];
}