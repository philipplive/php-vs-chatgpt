<?php

namespace ChatGPT\Objects;

class Image extends \ChatGPT\ApiObject {
	/**
	 * Link zum Download
	 * @var string
	 */
	public string $url = '';

	/**
	 * Beschreibung des erzeugten Bildes
	 * @var string
	 */
	public string $revisedPrompt = '';

	public array $indices = ['url', 'revisedPrompt' => 'revised_prompt'];
}