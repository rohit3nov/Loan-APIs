<?php
namespace App\Components\CoreComponent\Modules\Loan;

use App\Components\CoreComponent\Modules\Client\Client;
use App\Components\CoreComponent\Modules\Repayment\RepaymentFrequency;
use Carbon\Carbon;

/*
 * Author: Rohit Pandita(rohit3nov@gmail.com)
 */
class LoanRepository
{
    /**
     * Filter Loan as pagination
     *
     * @param array $data
     * @return \Illuminate\Support\Collection
     */
    public function filterLoan($data = [])
    {
        $result = Loan::active()->orderDesc();
        if (isset($data["user_id"])) {
            $result = $result->where('user_id', $data['user_id']);
        }
        $loans = $result->paginate($data['perPage']);
        return $loans;
    }

    /**
     * Create Loan
     *
     * @param array $bag
     * @param \App\Components\CoreComponent\Modules\Client\Client $client
     * @param array $data
     * @return \App\Components\CoreComponent\Modules\Loan\Loan|null
     */
    public function createLoan(&$bag, $data = [])
    {
        // if (!RepaymentFrequency::isValidType($data['repayment_frequency'])) {
        //     $bag = ['message' => trans('default.repayment_frequency_type_invalid')];
        //     return null;
        // }
        $loan = new Loan();
        $dateContractStart = new Carbon($data['date_contract_start']);
        $dateContractEnd = $dateContractStart->copy()->addMonth($data['duration']);
        $loan->fill([
            'user_id' => $data['user_id'],
            'amount' => $data['amount'],
            'duration' => $data['duration'],
            // 'repayment_frequency' => $data['repayment_frequency'],
            'repayment_frequency' => RepaymentFrequency::WEEKLY['id'],
            'interest_rate' => $data['interest_rate'],
            'remarks' => $data['remarks'],
            'date_contract_start' => $dateContractStart . '',
            'date_contract_end' => $dateContractEnd . '',
        ]);
        if (!$loan->save()) {
            $bag = ['message' => trans("default.loan_cannot_save")];
            return null;
        }
        return $loan;
    }
}
