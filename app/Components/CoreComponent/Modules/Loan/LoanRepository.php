<?php
namespace App\Components\CoreComponent\Modules\Loan;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class LoanRepository
{
    public function create(array $data = []) : Loan
    {
        return Loan::create($data);
    }

    public function get(int $loanId) : Loan
    {
        $loan = Loan::active()->find($loanId);
        if (!$loan) {
            throw new ModelNotFoundException(trans("default.loan_not_found"),404);
        }
        return $loan;
    }

    public function getAllByUserId(int $userId, int $perPage) : LengthAwarePaginator
    {
        return Loan::active()->orderDesc()->where('user_id', $userId)->paginate($perPage);
    }


    public function update(int $loanId, array $data = []) : void
    {
        try {
            $affectedRows = Loan::where('id',$loanId)->update($data);
            if ($affectedRows === 0) {
                throw new Exception(trans("default.loan_cannot_update"),500);
            }
        } catch (\Exception $e) {
            throw new Exception(trans("default.loan_cannot_update"),500);
        }
    }

}
