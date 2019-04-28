<?php

namespace core\services;

interface ServiceContainer {

	public function make($key);

	public function register($key, $callback);

	public function singleton($key, $callback);
}