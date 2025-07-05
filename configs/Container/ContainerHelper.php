<?php

declare(strict_types=1);

if(!function_exists('get')) {
	function get(string $id): Closure {
		return fn ($container) => $container->get($id); 
	}
}