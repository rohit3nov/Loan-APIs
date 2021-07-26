<?php

namespace App\Components\CoreComponent\Modules\Repayment;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RepaymentController extends Controller
{
    private $repaymentService;

    public function __construct(RepaymentService $repaymentService)
    {
        $this->repaymentService = $repaymentService;
    }

    public function apiGetRepayment($id) : JsonResponse
    {
        try {
            $repayment = $this->repaymentService->getRepaymentDetails($id);
            return response()->json(["status" => "success","repayment" => new RepaymentResource($repayment)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status'=>'failure','message' => $e->getMessage()],$e->getCode());
        }
    }

    public function apiUpdateRepayment(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // check if repayment exists and is not already paid
            $repayment = $this->repaymentService->checkRepaymentStatus($id);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status'=>'failure','message' => $e->getMessage()],$e->getCode());
        }

        try {
            // mark repayment as paid
            $this->repaymentService->saveRepayment($repayment,['payment_status'  => RepaymentStatus::PAID["id"],'date_of_payment' => \Carbon\Carbon::now(),'remarks' => $request->input("remarks")]);
            DB::commit();
            return response()->json(["status" => "success","repayment" => new RepaymentResource($repayment->refresh())], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__FUNCTION__.': '.$e);
           return response()->json(['status'=>'failure','message' => $e->getMessage()],$e->getCode());
        }
    }
}
