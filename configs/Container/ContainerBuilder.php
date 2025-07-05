<?php

declare(strict_types=1);
namespace Config\Container;

class ContainerBuilder {
	private array $definitions = [];

	public function addDefinitions(array $definitions): void {
		$this->definitions = array_merge($this->definitions, $definitions);

		}

		public function build(): DiContainer {
			return new DiContainer($this->definitions);

		}
	
}