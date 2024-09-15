<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'emp_id',
        'salutation',
        'profile',
        'designation',
        'department',
        'section',
        'jobfunction',
        'user_type',
        'homestation',
        'is_adt',
        'name',
        'email',
        'password',
        'mobile',
        'kin_name',
        'kin_phone',
        'kin_relation',
        'aadhaar_number',
        'pan_number',
        'doj',
        'joining_type',
        'pre_contract_renewal_date',
        'pre_valid_up_to',
        'contract_renewal_date',
        'valid_up_to',
        'aep_type',
        'aep_number',
        'aep_expiring_on',
        'police_verification',
        'passport_number',
        'passport_validity',
        'aircraft_authorisation_no',
        'per_state',
        'per_city',
        'per_pincode',
        'tem_state',
        'tem_city',
        'tem_pincode',
        'qualification',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'department'=>'array',
        'section'=>'array',
        'jobfunction'=>'array',
        'qualification'=>'array',
    ];
    public function hasUserType(string $user_type): bool
    {
        return $this->getAttribute('user_type') === $user_type;
    }
    public function designation()
    {
        return $this->belongsTo(Master::class,'designation','id');
    }
    public function fullName()
    {
        return $this->salutation.' '.$this->name;
    }

    public function setDojAttribute($value)
    {
        $this->attributes['doj'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getDojAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }

    public function setAepExpiringOnAttribute($value)
    {
        $this->attributes['aep_expiring_on'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getAepExpiringOnAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }
    public function setPassportValidityAttribute($value)
    {
        $this->attributes['passport_validity'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getPassportValidityAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }
    public function setAadhaarNumberAttribute($value)
    {
        $this->attributes['aadhaar_number'] = !empty($value)?encrypter('encrypt', $value):null;
    }
    public function getAadhaarNumberAttribute($value)
    {
        return !empty($value)?encrypter('decrypt', $value):'';
    }
    public function setPanNumberAttribute($value)
    {
        $this->attributes['pan_number'] = !empty($value)?encrypter('encrypt', $value):null;
    }
    public function getPanNumberAttribute($value)
    {
        $decryptedValue = !empty($value) ? encrypter('decrypt', $value) : '';
        return strtoupper($decryptedValue);
    }
    public function setPreContractRenewalDateAttribute($value)
    {
        $this->attributes['pre_contract_renewal_date'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getPreContractRenewalDateAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }
    public function setPreValidUpToAttribute($value)
    {
        $this->attributes['pre_valid_up_to'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getPreValidUpToAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }
    public function setContractRenewalDateAttribute($value)
    {
        $this->attributes['contract_renewal_date'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getContractRenewalDateAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }
    public function setValidUpToAttribute($value)
    {
        $this->attributes['valid_up_to'] = !empty($value)?date('Y-m-d', strtotime($value)):null;
    }
    public function getValidUpToAttribute($value)
    {
        return !empty($value)?date('d-m-Y', strtotime($value)):'';
    }

    public function certificates()
    {
        return $this->belongsToMany(Master::class, 'user_certificates', 'user_id', 'master_id')
                    ->withPivot('certificate_type', 'is_lifetime','is_mandatory','id_current_for_flying')
                    ->withTimestamps();
    }

}
