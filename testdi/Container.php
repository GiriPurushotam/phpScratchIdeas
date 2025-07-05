<?php

class Container {
	private array $propertise = [];

	public function setClassName(string $name, Closure $value): void {
		$this->propertise[$name] = $value;
	}

	public function getClassName($className): object {
		if(array_key_exists($className, $this->propertise)) {
			return $this->propertise[$className]();
		}
		$reflector = new ReflectionClass($className);
		$constructor = $reflector->getConstructor();
		
		if($constructor === null) {
			return new $className;
		}

		$dependencies = [];
		foreach($constructor->getParameters() as $parameter) {
			$type = $parameter->getType();
			$dependencies[] = $this->getClassName((string) $type);
		}

		return new $className(...$dependencies);
		
	}
}