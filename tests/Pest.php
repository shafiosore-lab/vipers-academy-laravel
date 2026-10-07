<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The public CBO site is served entirely from config/vipers.php, so the test
| suite intentionally does NOT use RefreshDatabase. That keeps the tests
| runnable without a MySQL server.
|
*/

pest()->extend(Tests\TestCase::class)->in('Feature');
