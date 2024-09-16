<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TInvoice;

class Directory extends Model
{
    use HasFactory;
    protected $primaryKey = "DirectoryId";
    public $timestamps = false;
    public $table="TDirectories";

    protected $fillable = [
        'DirectoryName',
        "ParentFId",
        "Available",
        "ForClient"
    ];

    public function invoice()
    {
        return $this->hasMany(TInvoice::class);
    }

    public function subDirectories(){
        return $this->hasMany(Subdirectory::class,"DirectoryFId",);

    }

    public function InvoiceKeys(){
        return $this->hasMany(InvoiceKey::class);
    }
}
