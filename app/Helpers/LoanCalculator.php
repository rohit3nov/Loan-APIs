<?php
namespace App\Helpers;

/*
 * Author: Rohit Pandita(rohit3nov@gmail.com)
 */
class LoanCalculator
{
    /**
     * Calculate amount for weekly repayment
     * @param float $loanAmount
     * @param float $monthlyInterestRate
     * @param float $repaymentMonthNumber
     */
    public static function calculateWeeklyRepayment(
        float $loanAmount,
        float $monthlyInterestRate,
        float $repaymentMonthNumber
    ) {
        $realMonthlyInterestRate = $monthlyInterestRate / (100*52);
        $repaymentWeekNumber = $repaymentMonthNumber * 4;
        $result = $loanAmount * $realMonthlyInterestRate * \pow(1 + $realMonthlyInterestRate, $repaymentWeekNumber);
        $result = $result / (\pow(1 + $realMonthlyInterestRate, $repaymentWeekNumber) - 1);

        return $result;
    }
}
