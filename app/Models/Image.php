<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TInvoice;

class Image extends Model
{
    use HasFactory;
    protected $primaryKey = "ImageId";
    public $timestamps = false;
    public $table="TImages";
    protected $fillable = [
        'InvoiceFId',
        'ImageName',
        'ImagePath',
        'PublicUrl',
        'ImageOriginalName'
    ];
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'last_update';

    public function invoice()
    {
        return $this->belongsTo(TInvoice::class, 'InvoiceFId','InvoiceId');
    }
}
