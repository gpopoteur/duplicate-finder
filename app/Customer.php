<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * Attributes that can be mass-assigned
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'gender', 'last_ip'];

    /**
     * Defines the rules to create/update a customer
     * @var array
     */
    public static $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'email|required',
        'gender' => 'required'
    ];

    /**
     * Defines the Gender options for a customer
     * @var array
     */
    public static $genderOptions = [
        'Gender 1' => 'Gender 1',
        'Gender 2' => 'Gender 2',
        'Gender 3' => 'Gender 3',
        'Gender 4' => 'Gender 4',
    ];

    /**
     * Gets the Local Part from the customer's email address
     * @return string
     */
    public function getEmailLocalPartAttribute()
    {
        $parts = explode('@', $this->email);
        return $parts[0];
    }

    /**
     * Gets the Full Name of the customer
     * @return string 
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
