<?php
namespace App\Components\CoreComponent\Modules\Loan;

use App\Components\CoreComponent\Modules\Repayment\RepaymentFrequency;
use App\Components\CoreComponent\Modules\Repayment\RepaymentService;
use App\Components\CoreComponent\Modules\Loan\LoanStatus;
use App\Helpers\LoanCalculator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class LoanService
{
    private $loanRepository;
    private $repaymentService;

    public function __construct(LoanRepository $loanRepository,RepaymentService $repaymentService)
    {
        $this->loanRepository = $loanRepository;
        $this->repaymentService = $repaymentService;
    }

    public function createLoan(array $data = []) : Loan
    {
        $dateContractStart = new Carbon($data['date_contract_start']);
        $dateContractEnd = $dateContractStart->copy()->addMonth($data['duration']);
        $loanData = array(
            'user_id' => $data['user_id'],
            'amount' => $data['amount'],
            'duration' => $data['duration'],
            'repayment_frequency' => RepaymentFrequency::WEEKLY['id'],
            'interest_rate' => $data['interest_rate'],
            'remarks' => $data['remarks'],
            'date_contract_start' => $dateContractStart . '',
            'date_contract_end' => $dateContractEnd . '',
        );
        return $this->loanRepository->create($loanData);
    }

    public function checkLoanStatus(int $loanId) : Loan
    {
        try {
            $loan = $this->getLoanDetails($loanId);
            if ($loan->status !== 0) {
                $status = LoanStatus::isApproved($loan->status) ? LoanStatus::APPROVED['name']: LoanStatus::REJECTED['name'];
                throw new \Exception("Loan already $status",422);
            }
            return $loan;
        } catch (ModelNotFoundException $e) {
            throw $e;
        }
    }

    public function getLoanDetails(int $loanId) : Loan
    {
        return $this->loanRepository->get($loanId);
    }

    public function updateLoan(int $loanId,array $data = []) : void
    {
        $this->loanRepository->update($loanId,$data);
    }

    public function generateLoanRepayments(Loan $loan) : void
    {
        // generate repayments only if loan is approved
        if (LoanStatus::isApproved($loan->status)) {
            // get weekly repayment amount
            $amount = LoanCalculator::calculateWeeklyRepayment(...[$loan->amount, $loan->interest_rate, $loan->duration,]);
            $dueDate = $loan->date_contract_start->copy();

            while (true) {
                $dueDate = $dueDate->copy()->addDay(7); // add 7 days for weekly repayment
                if ($dueDate->greaterThan($loan->date_contract_end)) {
                    break;
                }
                $this->repaymentService->createRepayment(['loan_id' => $loan->id,'amount' => $amount,'due_date' => $dueDate.'']);
            }
        }
    }

    public function getUserLoans(int $userId, int $perPage) : LengthAwarePaginator
    {
        return $this->loanRepository->getAllByUserId($userId,$perPage);
    }
}
