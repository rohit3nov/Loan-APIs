<?php
namespace App\Components\CoreComponent\Modules\Repayment;

use App\Components\CoreComponent\Modules\Loan\Loan;
use App\Helpers\LoanCalculator;
use Validator;

/*
 * Author:  Rohit Pandita(rohit3nov@gmail.com)
 */
class RepaymentRepository
{
    /**
     * Create Repayment
     *
     * @param array $data
     * @return \App\Components\CoreComponent\Modules\Repayment\Repayment|null
     */
    public function createRepayment(&$bag, $data = [])
    {
        $validator = Validator::make($data, RepaymentRequest::staticRules(),RepaymentRequest::staticMessages());
        if ($validator->fails()) {
            $bag = [
                "message" => trans("default.validation_error"),
                "errors" => $validator->errors()->first(),
            ];
            return null;
        }

        // ensure no duplicate repayment
        if (Repayment::where('loan_id', $data['loan_id'])->where('due_date', $data['due_date'])->exists()) {
            $bag = ["message" => trans("default.repayment_exist"),];
            return null;
        }

        $repayment = new Repayment();
        $repayment->fill([
            'loan_id' => $data['loan_id'],
            'amount' => $data['amount'],
            'payment_status' => $data['payment_status'],
            'due_date' => $data['due_date'],
            'date_of_payment' => $data['date_of_payment'],
            'remarks' => $data['remarks'],
        ]);
        if ($repayment->save()) {
            return $repayment;
        }
        $bag = ["message" => trans("default.saving_fail"),];
        return null;
    }

    /**
     * Generate Repayments
     *
     * @param array $bag
     * @param \App\Components\CoreComponent\Modules\Loan\Loan $loan
     * @return boolean
     */
    public function generateRepayments(&$bag, Loan $loan)
    {
        $calData = [$loan->amount, $loan->interest_rate, $loan->duration,];
        $amount = LoanCalculator::calculateWeeklyRepayment(...$calData); // logic to calculate weekly amount
        $startDate = $loan->date_contract_start;
        $endDate = $loan->date_contract_end;
        $dueDate = $startDate->copy();
        while (true) {
            $dueDate = $dueDate->copy()->addDay(7); // add 7 days for weekly repayment
            if ($dueDate->greaterThan($endDate)) {
                break;
            }
            $data = array(
                'loan_id' => $loan->id,
                'amount' => $amount,
                'payment_status' => RepaymentStatus::UNPAID["id"],
                'due_date' => $dueDate . '',
                'date_of_payment' => null,
                'remarks' => null,
            );
            if (!$this->createRepayment($bag, $data)) {
                return false;
            }
        }
        return true;
    }
}
