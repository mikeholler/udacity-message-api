<?php

class ModelTestCase extends TestCase {

    public function setUp() {
        parent::setUp();

        // Create database schema.
        Artisan::call('migrate:refresh');
    }

    protected function seedDatabase(array $seeds) {
        foreach ($seeds as $seed) {
            $this->seed($seed);
        }
    }

}