<?php

namespace Tests\Unit;

use App\Helpers\LoanCalculator;
use App\Http\Middleware\AuthenticateAPIOnce;
use Illuminate\Foundation\Testing\TestCase;

/*
 * Author:  Rohit Pandita(rohit3nov@gmail.com)
 */
class LoanTest extends TestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';
        return $app;
    }

    public function testWeeklyRepayment()
    {
        $amount = LoanCalculator::calculateWeeklyRepayment(2000, 9, 1);
        $this->assertEquals((int) $amount, 502);

    }
}
