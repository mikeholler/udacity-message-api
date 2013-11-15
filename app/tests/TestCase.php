<?php

use \Mockery as m;

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}

    /**
     * Create a mock instance and bind it to the parent class in Laravel's IoC container.
     *
     * @param $class
     *
     * @return m\MockInterface|Yay_MockObject
     */
    public function mock($class) {
        $mock = m::mock($class);

        $this->app->instance($class, $mock);

        return $mock;
    }
}
