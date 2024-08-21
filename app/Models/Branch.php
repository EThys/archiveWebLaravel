<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $primaryKey = "BranchId";
    public $timestamps = false;
    public $table="TBranchs";
    protected $fillable = [
        "BranchName"
    ];

    public function users(){
        return $this->hasMany(User::class,"BranchId");
    }
}
