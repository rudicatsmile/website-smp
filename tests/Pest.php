<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
 |--------------------------------------------------------------------------
 | Pest Test Configuration
 |--------------------------------------------------------------------------
 |
 | This file configures Pest for Laravel testing with RefreshDatabase.
 | Properties are validated with minimum 100 iterations as required by spec.
 |
 */

uses(TestCase::class)
    ->in('Feature', 'Unit');

/*
 * Note: For RefreshDatabase, use the RefreshDatabase trait directly in your test files
 * or use the `database()` helper provided by Pest Laravel plugin:
 * 
 *    uses()->refreshDatabase();
 * 
 * Or in your test file:
 * 
 *    use Illuminate\Foundation\Testing\RefreshDatabase;
 *    uses(RefreshDatabase::class);
 */