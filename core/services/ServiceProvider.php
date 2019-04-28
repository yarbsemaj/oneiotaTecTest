<?php

namespace core\services;

use core\App;

/**
 * Just a teeny tiny interface to make sure out service providers come out right.
 * This function should take the $service object and then run any service
 * registrations on it that it needs e.g. $services->register('Something', function($app, $services){ ... });
 *
 * There could be many registrations within this function, this should be a group
 * of registrations that sit nicely together and make sense as a package
 */
interface ServiceProvider
{
	public function registerServices(App $app);
}
