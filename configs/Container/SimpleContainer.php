<?php

declare(strict_types=1);

namespace Config\Container;

use Closure;
use Exception;

class SimpleContainer implements ContainerInterface {

	private array $entries = [];

	public function set(string $id, $concrete): void {
		$this->entries[$id] = $concrete;
	}

	public function get(string $id)
	{
		if(! $this->has($id)) {
			throw new Exception("Entry '$id' Not Found In Container");
		}

		$entry = $this->entries[$id];

		if($entry instanceof Closure) {
			$this->entries[$id] = $entry($this);
			return $this->entries[$id];
		}

		return $entry;
		
	}

	public function has(string $id): bool
	{
		return array_key_exists($id, $this->entries);
	}

}