<?php

namespace ChatGPT\Objects;

class Run extends \ChatGPT\ApiObject {
	public Thread $thread;

	public function __construct(\ChatGPT\API $api, string $id, Thread $thread) {
		parent::__construct($api, $id);
		$this->thread = $thread;
	}

	public function isComplete(): bool {
		return $this->api->curlRequest([], ['threads', $this->thread->id, 'runs', $this->id], 'GET')['status'] == 'completed';
	}

	/**
	 * Warte bis der Auftrag abgeschlossen ist
	 * @param int $timeout in Sekunden
	 * @return $this
	 * @throws \Exception
	 */
	public function wait(int $timeout = 20): self {
		while (!$this->isComplete()) {
			if ($timeout < 0)
				throw new \Exception("Timeout");

			$timeout -= 3;
			sleep(3);
		}

		return $this;
	}
}