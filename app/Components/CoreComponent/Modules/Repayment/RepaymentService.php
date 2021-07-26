<?php
namespace App\Components\CoreComponent\Modules\Repayment;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class RepaymentService
{
    private $repaymentRepository;

    public function __construct(RepaymentRepository $repaymentRepository)
    {
        $this->repaymentRepository = $repaymentRepository;
    }

    public function getRepaymentDetails(int $repaymentId, bool $locked = false) : Repayment
    {
        return $locked ? $this->repaymentRepository->getLocked($repaymentId) : $this->repaymentRepository->get($repaymentId);
    }

    public function createRepayment(array $data = []) : void
    {
        // ensure no duplicate repayments generated
        if ($this->repaymentRepository->checkRepaymentByDueDate($data['loan_id'],$data['due_date'])) {
            throw new \Exception(trans("default.loan_repayment_exist"),422);
        }

        $this->repaymentRepository->create(array_add($data,'payment_status',RepaymentStatus::UNPAID["id"]));
    }

    public function checkRepaymentStatus(int $repaymentId) : Repayment
    {
        try {
            $repayment = $this->getRepaymentDetails($repaymentId,true);
            if (RepaymentStatus::isPaid($repayment->payment_status)) {
                throw new \Exception(trans("default.repayment_already_paid"),422);
            }
            return $repayment;
        } catch (ModelNotFoundException $e) {
            throw $e;
        }
    }

    public function updateRepayment(int $repaymentId, array $data = []) : void
    {
        $this->repaymentRepository->update($repaymentId,$data);
    }

    public function saveRepayment(Repayment $repayment, array $data = []): void
    {
        $this->repaymentRepository->save($repayment,$data);
    }
}
