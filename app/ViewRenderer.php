<?php

declare(strict_types=1);

namespace App;

class ViewRenderer
{
	private string $viewPath;
	private array $globals = [];

	public function __construct(string $viewPath)
	{
		$this->viewPath = rtrim($viewPath, '/');
	}

	public function getEnvironment(): self
	{
		return $this;
	}

	public function addGlobal(string $key, mixed $value): void
	{
		$this->globals[$key] = $value;
	}

	public function getGlobal(): array
	{
		return $this->globals;
	}

	public function render(string $template, array $data = []): string
	{
		$fullPath = "{$this->viewPath}/{$template}";


		if (!file_exists($fullPath)) {
			throw new \RuntimeException("View FIle '{$template}' not found in {$this->viewPath}");
		}

		$data = array_merge($this->globals, $data);

		if (!empty($data)) {
			extract($data, EXTR_SKIP);
		}

		ob_start();
		include $fullPath;
		return ob_get_clean();
	}
}
