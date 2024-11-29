<?php

namespace ChatGPT\Objects;

class ThreadMessage {
	public string $message;

	/**
	 * @var int \ChatGPT\Role::USER|\ChatGPT\Role::ASSISTANT
	 */
	private int $role;

	public function __construct(string $message = '', int $role = \ChatGPT\Role::USER) {
		$this->message = $message;
		$this->setRole($role);
	}

	public function setRole(int $role): self {
		$this->role = $role;
		return $this;
	}

	public function getRole(): int {
		return $this->role;
	}

	public function setRoleFromString(string $role): void {
		$this->role = $role == 'user' ? \ChatGPT\Role::USER : \ChatGPT\Role::ASSISTANT;
	}

	public function addMessage(string $message): self {
		$this->message .= $message;
		return $this;
	}
}