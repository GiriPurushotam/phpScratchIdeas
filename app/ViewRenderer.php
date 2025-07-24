<?php

declare(strict_types=1);

namespace App;

class ViewRenderer
{
	private string $viewPath;

	public function __construct(string $viewPath)
	{
		$this->viewPath = rtrim($viewPath, '/');
	}

	public function render(string $template, array $data = []): string
	{
		$fullPath = "{$this->viewPath}/{$template}";


		if (! $fullPath) {
			throw new \RuntimeException("View FIle '{$template}' not found in {$this->viewPath}");
		}

		if (!empty($data)) {
			extract($data);
		}

		ob_start();
		include $fullPath;
		return ob_get_clean();
	}
}
