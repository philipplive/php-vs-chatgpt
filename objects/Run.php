<?php

namespace ChatGPT\Objects;

class Run extends \ChatGPT\ApiObject {
	public Thread $thread;

	public function __construct(\ChatGPT\API $api, string $id, Thread $thread) {
		parent::__construct($api, $id);
		$this->thread = $thread;
	}

	public function isComplete(): bool {
		$data = $this->api->curlRequest([], ['threads', $this->thread->id, 'runs', $this->id], 'GET');
		return $data['status'] == 'completed';
	}

	public function wait(int $timeout = 60): self {
		while (!$this->isComplete()) {
			if ($timeout-- == 1)
				throw new \Exception("Timeout");

			sleep(1);
		}

		return $this;
	}
}