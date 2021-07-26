<?php

namespace Tests\Feature;

use App\Components\CoreComponent\Modules\Loan\Loan;
use App\Components\CoreComponent\Modules\Repayment\RepaymentRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;

/*
 * Author:  Rohit Pandita(rohit3nov@gmail.com)
 */
class HttpTest extends TestCase
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }

    public function testLoanAPIs()
    {
        DB::beginTransaction();

        // Test user signup - success
        $signUpInput = array(
            'name' => 'test_user',
            'email' => 'testemail@gmail.com',
            'password' => 'test123',
            'password_confirmation' => 'test123',
        );
        $signUpResponse = $this->post('/api/v1/users/register',$signUpInput);

        $signUpResponse->assertStatus(200);
        $this->assertNotNull($signUpResponse->json('user'));
        $this->assertNotNull($signUpResponse->json('access_token'));

        // Test user login - failure
        $failLoginResponse = $this->post('/api/v1/users/login', [
            'email' => $signUpInput['email'],
            'password' => $signUpInput['password'].'4',
        ]);
        $failLoginResponse->assertStatus(401);
        $this->assertNotNull($failLoginResponse->json('message'));
        $this->assertEquals('Unable to login. Invalid Credentials.',$failLoginResponse->json('message'));


        // Test user login - success
        $successLoginResponse = $this->post('/api/v1/users/login', [
            'email' => $signUpInput['email'],
            'password' => $signUpInput['password'],
        ]);
        $successLoginResponse->assertStatus(200);
        $this->assertNotNull($successLoginResponse->json('user'));
        $this->assertNotNull($successLoginResponse->json('access_token'));
        $header = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$successLoginResponse->json('access_token'),
            'X-Auth' => 'Hash:random'
        ];
        // Test post to create loan of user
        $date = Carbon::now();
        $i = 0;
        $date->addMonth($i);
        $duration = 12;
        $loanData = [
            'user_id' => $successLoginResponse->json('user')['id'],
            'amount' => 1000,
            'duration' => $duration,
            'interest_rate' => 0.1,
            'remarks' => null,
            'date_contract_start' => $date . '',
        ];
        $createLoanApiResponse = $this->post('/api/v1/loans/create', $loanData,$header);
        $createLoanApiResponse->assertStatus(200);
        $this->assertTrue($successLoginResponse->original['user']->refresh()->loans->count() == $i + 1);

        $createLoanResponseData = $createLoanApiResponse->baseResponse->getData(true);
        $loanId = $createLoanResponseData['loan']['loan_id'];
        $loan = Loan::active()->find($loanId);
        $this->assertTrue($loan->date_contract_end->diffInMonths($loan->date_contract_start) == $duration);
        $this->assertNotNull($loan);

        // Test approve loan api(this would generate repayments if approved)
        $approveLoanData = [
            'loan_id' => $loanId,
            'status' => '1' // 1 -> approve, -1 -> reject
        ];
        $approveLoanApiResponse = $this->post('/api/v1/loans/update', $approveLoanData,$header);
        $approveLoanApiResponse->assertStatus(200);

        // Ensure no duplicate repayments
        $repaymentRepository = new RepaymentRepository();
        $failure = $repaymentRepository->generateRepayments($bag, $loan->refresh());
        $this->assertFalse($failure);
        $this->assertNotNull($bag);
        $this->assertEquals('Repayment exists',$bag['message']);


        // Test post to get loans of user
        $perPage = 2;
        $getLoanResponse = $this->post('/api/v1/loans/get', ['perPage' => $perPage,],$header);
        $getLoanResponse->assertStatus(200);
        $getLoanResponseData = $getLoanResponse->baseResponse->getData(true);
        $data = $getLoanResponseData['data'];
        $loanCount = \count($data);
        $this->assertTrue($loanCount <= $perPage);
        $this->assertTrue($getLoanResponseData['meta']['total'] == $i + 1);

        // Test loan repayment apis
        $getRepaymentResponse = $this->post('/api/v1/repayments/get/'.$loan->repayments[0]->id,[],$header);
        $getRepaymentResponse->assertStatus(200);

        $payRepaymentResponse = $this->post('/api/v1/repayments/pay/' . $loan->repayments[0]->id,['remarks'=>'Payment done'],$header);
        $payRepaymentResponse->assertStatus(200);

        // Assert not allow repay for paid repayment
        $payRepaymentResponse = $this->post('/api/v1/repayments/pay/' . $loan->repayments[0]->id,['remarks'=>'Payment done'],$header);
        $payRepaymentResponse->assertStatus(400);

        // rollback test records
        DB::rollBack();
    }
}
