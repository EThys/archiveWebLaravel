<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TInvoice;

class InvoiceKey extends Model
{
    use HasFactory;
    protected $primaryKey = "InvoiceKeyId";
    public $timestamps = false;
    public $table="TInvoiceKeys";
    protected $fillable = [
        'Invoicekey',
        'DirectoryFId',
    ];
    public function invoice()
    {
        return $this->hasMany(TInvoice::class);
    }
}
