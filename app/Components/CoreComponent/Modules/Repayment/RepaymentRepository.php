<?php
namespace App\Components\CoreComponent\Modules\Repayment;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class RepaymentRepository
{
    public function get(int $repaymentId) : Repayment
    {
        $repayment = Repayment::active()->find($repaymentId);
        if (!$repayment) {
            throw new ModelNotFoundException(trans("default.repayment_not_found"),404);
        }
        return $repayment;
    }

    public function getLocked(int $repaymentId) : Repayment
    {
        $repayment = Repayment::sharedLock()->active()->find($repaymentId);
        if (!$repayment) {
            throw new ModelNotFoundException(trans("default.repayment_not_found"),404);
        }
        return $repayment;
    }

    public function create(array $data) : void
    {
        $repayment = new Repayment();
        $repayment->fill($data);
        if (!$repayment->save()) {
            throw new \Exception(trans("default.repayment_cannot_generate"),500);
        }
    }

    public function checkRepaymentByDueDate(int $loanId, \DateTime $dueDate) : bool
    {
        return Repayment::where('loan_id', $loanId)->where('due_date', $dueDate)->exists();
    }

    public function update(int $repaymentId, array $data = []) : void
    {
        try {
            $affectedRows = Repayment::where('id',$repaymentId)->update($data);
            if ($affectedRows === 0) {
                throw new \Exception(trans("default.repayment_cannot_update"),500);
            }
        } catch (\Exception $e) {
            throw new \Exception(trans("default.repayment_cannot_update"),500);
        }
    }

    public function save(Repayment $repayment, array $data = []) : void
    {
        try {
            $repayment->fill($data)->save();
        } catch (\Exception $e) {
            dd($e);
            throw new \Exception(trans("default.repayment_cannot_update"),500);
        }
    }
}
