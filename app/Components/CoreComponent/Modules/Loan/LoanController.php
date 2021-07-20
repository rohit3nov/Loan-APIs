<?php

namespace App\Components\CoreComponent\Modules\Loan;

use App\Components\CoreComponent\Modules\Client\Client;
use App\Components\CoreComponent\Modules\Repayment\RepaymentFrequency;
use App\Components\CoreComponent\Modules\Repayment\RepaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Validator;

/*
 * Author: Rohit Pandita(rohit3nov@gmail.com)
 */
class LoanController extends Controller
{
    private $repository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->repository = $loanRepository;
    }

    /**
     * Create loan via api
     *
     * @param \Illuminate\Http\Request $request
     */
    public function apiCreateLoan(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        $validator = Validator::make($data, LoanRequest::staticRules(), LoanRequest::staticMessages());
        if ($validator->fails()) {
            return response()->json(["status" => "error","error_type" => trans("default.validation_error"),"error_msg" => $validator->errors()->first(),], 400);
        }
        $loan = $this->repository->createLoan($bag, $data);
        if (!$loan) {
            return response()->json(["status" => "error","message" => $bag['message'],], 500);
        }
        return response()->json(["status" => "success","loan" => new LoanResource($loan->refresh()),], 200);
    }

    public function apiUpdateLoan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "loan_id" => "required|numeric",
            "status"  => ["required","numeric",new \App\Rules\LoanStatusRule()],
        ], []);

        if ($validator->fails()) {
            return response()->json([
                "status" => "failed",
                "error_type" => trans("default.validation_error"),
                "error_msg" => $validator->errors()->first(),
            ], 400);
        }

        DB::beginTransaction();
        $loan = Loan::active()->find($request->loan_id);
        if (!$loan) {
            return response()->json(['message' => trans("default.loan_not_found")], 404);
        }
        // don't proceed if loan already approved or rejected
        if ($loan->status !== 0) {
            $status = LoanStatus::isApproved($loan->status) ? LoanStatus::APPROVED['name']: LoanStatus::REJECTED['name'];
            return response()->json(['message' => "Loan already $status."], 404);
        }
        // update loan status
        $loan->status = $request->status;
        if (!$loan->save()) {
            DB::rollBack();
            return response()->json(['message' => trans("default.loan_cannot_save")], 404);
        }
        // use repayment repository to generate repayment base on loan
        $repaymentRepository = new RepaymentRepository();
        if (!$repaymentRepository->generateRepayments($bag, $loan)) {
            DB::rollBack();
            return response()->json(["status" => "error","message" => $bag['message'],], 500);
        }
        DB::commit();
        return response()->json(["status" => "success","loan" => new LoanResource($loan->refresh()),], 200);
    }

    /**
     * Get loan list
     * Get loan if loan"s id is specified
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Components\CoreComponent\Modules\Loan\Loan:id $id
     */
    public function apiGetLoan(Request $request, $id = null)
    {
        if (\is_null($id)) {
            $data = [
                "perPage" => $request->get("perPage") ?? 20,
                "user_id" => $request->user()->id
            ];
            return new LoanCollection($this->repository->filterLoan($data));
        }
        $loan = Loan::active()->find($id);
        if (!$loan) {
            return response()->json(['message' => trans("default.loan_not_found")], 404);
        }
        if ($loan) {
            return new LoanResource($loan);
        }
    }
}
