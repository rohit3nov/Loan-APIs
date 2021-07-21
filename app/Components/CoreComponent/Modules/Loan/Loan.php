<?php
namespace App\Components\CoreComponent\Modules\Loan;

use App\User;
use App\Components\CoreComponent\Modules\Repayment\Repayment;
use App\Components\CoreComponent\Modules\Repayment\RepaymentFrequency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * Author:  Rohit Pandita(rohit3nov@gmail.com)
 */
class Loan extends Model
{
    use SoftDeletes;
    protected $table = 'loans';
    protected $primaryKey = 'id';
    protected $fillable = [
        'active',
        'user_id',
        'amount',
        'duration',
        'repayment_frequency',
        'interest_rate',
        'remarks',
        'date_contract_start',
        'date_contract_end',
    ];
    protected $attributes = [
        'active' => true,
    ];
    protected $casts = [
        'active' => 'boolean',
        'date_contract_start' => 'datetime',
        'date_contract_end' => 'datetime',
    ];
    protected $hidden = [
        'id',
        'user_id',
        'active',
        'deleted_at',
    ];

    public function setRepaymentFrequencyAttribute($value)
    {
        if (!RepaymentFrequency::isValidType($value)) {
            throw new \Exception(trans('default.repayment_frequency_type_invalid'));
        }
        $this->attributes['repayment_frequency'] = $value;
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
    public function scopeOrderDesc($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

    /**
     * Association many to one, many loans can have same one client.
     */
    public function User()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function activate($isActive)
    {
        foreach ($this->repayments as $repayment) {
            $repayment->activate($isActive);
        }
        $this->active = $isActive;
        return $this->save();
    }
    public function delete()
    {
        $success = true;
        foreach ($this->repayments as $repayment) {
            if (!$repayment->delete()) {
                $success = false;
            }
        }
        return parent::delete() && $success;
    }
    public function forceRestoreThis()
    {
        $success = $this->restore();
        $repayments = Repayment::withTrashed()->where('loan_id', $this->id)->get();
        foreach ($repayments as $repayment) {
            if (!$repayment->restore()) {
                $success = false;
            }
        }
        return $success;
    }
    public function forceDeleteThis()
    {
        $success = true;
        $repayments = Repayment::withTrashed()->where('loan_id', $this->id)->get();
        foreach ($repayments as $repayment) {
            if (!$repayment->forceDelete()) {
                $success = false;
            }
        }
        return $this->forceDelete() && $success;
    }
}
