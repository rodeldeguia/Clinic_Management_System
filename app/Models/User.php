<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users'; // link to your table

    protected $primaryKey = 'user_id'; // since your PK is user_id

    public $timestamps = false; // you’re using custom timestamps

    protected $fillable = [
        'username', 'password_hashed', 'role', 'is_active',
        'firstname', 'lastname', 'contact_number', 'email_address',
        'address', 'gender', 'specialization'
    ];
}
?>