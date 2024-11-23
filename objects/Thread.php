<?php

namespace ChatGPT\Objects;

/**
 * @doc https://platform.openai.com/docs/api-reference/threads
 */
class Thread extends \ChatGPT\ApiObject {
	private string $assistantId = '';

	public function setAssistantId(string $assistantId): self {
		$this->assistantId = $assistantId;
		return $this;
	}

	public function addMessage(ThreadMessage $message): self {
		$this->api->curlRequest(['role' => $message->getRole() == \ChatGPT\Role::USER ? 'user' : 'assistant', 'content' => $message->message], ['threads', $this->id, 'messages']);
		return $this;
	}

	public function addTextMessage(string $text, int $role): self {
		$this->addMessage(new ThreadMessage($text, $role));
		return $this;
	}

	public function run(): Run {
		if (empty($this->assistantId))
			throw new \Exception('Kein Assistent gesetzt');

		$data = $this->api->curlRequest([
			'assistant_id' => $this->assistantId
		], ['threads', $this->id, 'runs']);
		return $this->getRunById($data['id']);
	}

	/**
	 * @return ThreadMessage[]
	 */
	public function getMessages(): array {
		$response = $this->api->curlRequest([], ['threads', $this->id, 'messages'], 'GET');
		$msgs = [];

		foreach ($response['data'] as $responseMsg) {
			$msg = new ThreadMessage();
			$msg->setRoleFromString($responseMsg['role']);

			foreach ($responseMsg['content'] as $content) {
				if ($content['type'] == 'text') {
					$text = explode('【', $content['text']['value'])[0]; // Quellenangaben entfernen
					$msg->addMessage($text);
				}
				else
					throw new \Exception(sprintf('Unbekannter Type %s', $content['type']));
			}

			$msgs[] = $msg;
		}

		return $msgs;
	}

	public function getLastMessage(): ThreadMessage {
		$messages = $this->getMessages();
		return array_shift($messages);
	}

	public function delete(): void {
		$this->api->curlRequest([], ['threads', $this->id], 'DELETE');
	}

	public function getRunById(string $id): Run {
		return new Run($this->api, $id, $this);
	}
}