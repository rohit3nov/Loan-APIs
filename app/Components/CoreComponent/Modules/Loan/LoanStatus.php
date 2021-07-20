<?php
namespace App\Components\CoreComponent\Modules\Loan;

/*
 * Author: Rohit Pandita(rohit3nov@gmail.com)
 */
class LoanStatus
{
    const INREVIEW = [
        'id' => 0,
        'name' => 'inreview',
        'description' => 'Loan request in review',
    ];
    const REJECTED = [
        'id' => -1,
        'name' => 'rejected',
        'description' => 'Loan request rejected',
    ];
    const APPROVED = [
        'id' => 1,
        'name' => 'approved',
        'description' => 'Loan request approved',
    ];

    public static function isValidStatus($statusId)
    {
        return \in_array($statusId, [
            self::INREVIEW['id'],
            self::REJECTED['id'],
            self::APPROVED['id'],
        ]);
    }
    public static function isApproved($statusId)
    {
        return $statusId == self::APPROVED['id'];
    }
    public static function isRejected($statusId)
    {
        return $statusId == self::REJECTED['id'];
    }
}
