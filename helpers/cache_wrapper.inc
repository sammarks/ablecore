<?php

function ablecore_cache_wrapper($cache_name, callable $callback)
{
	$cache = &drupal_static($cache_name);
	if (!isset($cache)) {
		$cache_cache = cache_get($cache_name);
		if (isset($cache_cache->data)) {
			$cache = $cache_cache->data;
		} else {
			$cache = $callback();
			cache_set($cache_name, $cache);
		}
	}

	return $cache;
}
