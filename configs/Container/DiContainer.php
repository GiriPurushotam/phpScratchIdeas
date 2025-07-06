<?php

declare(strict_types=1);

namespace Config\Container;

use Closure;
use Exception;

class DiContainer implements ContainerInterface
{
	private array $definitions;
	private array $instances = [];

	public function __construct(array $definitions)
	{
		$this->definitions = $definitions;
	}

	public function has(string $id): bool
	{
		if (isset($this->definitions[$id])) {
			return true;
		}

		if (!class_exists($id)) {
			return false;
		}

		try {
			$reflection = new \ReflectionClass($id);
			$constructor = $reflection->getConstructor();
			if (!$constructor) {
				return true;
			}

			foreach ($constructor->getParameters() as $param) {
				$type = $param->getType();

				if (!$type || $type->isBuiltin()) {
					return false;
				}

				if (! $this->has($type->getName())) {
					return false;
				}
			}

			return true;
		} catch (\ReflectionException) {
			return false;
		}
	}

	public function get(string $id)
	{
		if (isset($this->instances[$id])) {
			return $this->instances[$id];
		}

		if (isset($this->definitions[$id])) {
			$definitions = $this->definitions[$id];

			if ($definitions instanceof Closure) {
				$instance = $definitions($this);
			} else {
				$instance = $definitions;
			}

			$this->instances[$id] = $instance;
			return $instance;
		}

		// auto wire if class exists and not define manually

		if (class_exists($id)) {
			$reflection = new \ReflectionClass($id);
			$constructor = $reflection->getConstructor();
			if (! $constructor) {
				return $this->instances[$id] = new $id();
			}

			$params = [];
			foreach ($constructor->getParameters() as $param) {
				$type = $param->getType();
				if ($type && ! $type->isBuiltin()) {
					$params[] = $this->get($type->getName());
				} else {
					throw new Exception("Cannot Resolve Parameter {$param->getName()}");
				}
			}

			$instance = $reflection->newInstanceArgs($params);
			$this->instances[$id] = $instance;
			return $instance;
		}

		throw new Exception("Service {$id} Not found");
	}
}
