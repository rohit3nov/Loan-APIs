<?php

namespace App\Components\CoreComponent\Modules\Loan;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LoanController extends Controller
{
    private $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function apiCreateLoan(Requests\CreateLoanApiRequest $request) : JsonResponse
    {
        try {
            $loan = $this->loanService->createLoan(array_add($request->all(),'user_id',$request->user()->id));
        } catch (\Exception $e) {
            Log::error(__FUNCTION__.': '.$e);
            return response()->json(['status'=>'failure','message' => 'Unable to raise loan request due to some technical issue. Please try again after some time!'],500);
        }
        return response()->json(["status" => "success","loan" => new LoanResource($loan->refresh()),], 200);
    }

    public function apiUpdateLoan(Requests\UpdateLoanApiRequest $request) : JsonResponse
    {
        // check if loan exists and is not already approved/rejected
        try {
            $loan = $this->loanService->checkLoanStatus($request->loan_id);
        } catch (\Exception $e) {
            return response()->json(['status'=>'failure','message' => $e->getMessage()],$e->getCode());
        }

        // update loan status and generate repayments if approved
        try {
            DB::beginTransaction();
            $this->loanService->updateLoan($request->loan_id,['status'=>$request->status]);
            $this->loanService->generateLoanRepayments($loan->refresh());
            DB::commit();
            return response()->json(["status" => "success","loan" => new LoanResource($loan->refresh())], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__FUNCTION__.': '.$e);
            return response()->json(['status'=>'failure','message' => $e->getMessage()],$e->getCode());
        }
    }

    public function apiGetLoan(Request $request, $id = null)
    {
        if (\is_null($id)) {
            $loans = $this->loanService->getUserLoans($request->user()->id, $request->get("perPage") ?? 20);
            return new LoanCollection($loans);
        }
        try {
            $loan = $this->loanService->getLoanDetails($id);
            return response()->json(["status" => "success","loan" => new LoanResource($loan)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status'=>'failure','message' => $e->getMessage()],$e->getCode());
        }
    }
}
