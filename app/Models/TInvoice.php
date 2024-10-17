<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;
use App\Models\Directory;
use App\Models\User;
use App\Models\Image;
use App\Models\InvoiceKey;

class TInvoice extends Model
{
    use HasFactory;
    protected $primaryKey = "InvoiceId";
    public $timestamps = false;
    public $table="TInvoices";
    protected $fillable = [
        'InvoiceId',
        'RemoteId',
        'InvoiceCode',
        'InvoiceDesc',
        'InvoiceBarCode',
        'UserFId',
        'DirectoryFId',
        'BranchFId',
        'InvoiceDate',
        'InvoiceKeyFId',
        'InvoicePath',
        'AndroidVersion',
        'ClientName',
        'ClientPhone',
        'ExpiredDate',
        'CreatedAt'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserFId','UserId');
    }
    public function directory()
    {
        return $this->belongsTo(Directory::class, 'DirectoryFId','DirectoryId');
    }
    public function subdirectory()
    {
        return $this->belongsTo(Subdirectory::class, 'SubDirectoryFId','SubDirectoryId');
    }
    public function invoicekey()
    {
        return $this->belongsTo(InvoiceKey::class, 'InvoiceKeyFId','InvoiceKeyId');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'BranchFId' , 'BranchId');
    }
    public function images()
    {
        return $this->hasMany(Image::class,"InvoiceFId");
    }
}
