<?php

namespace ChatGPT\Objects;

class ThreadMessage{
	public string $msg;
	public int $role;

	public function __construct(string $msg = '', int $role = \ChatGPT\Role::USER) {
		$this->msg = $msg;
		$this->role = $role;
	}

	public function setRoleFromString(string $role): void {
		if ($role == 'user')
			$this->role = \ChatGPT\Role::USER;
		else
			$this->role = \ChatGPT\Role::ASSISTANT;
	}

	public function addContent(string $content): void {
		$this->msg .= $content;
	}
}