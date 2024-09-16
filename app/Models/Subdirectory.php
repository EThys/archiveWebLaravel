<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdirectory extends Model
{
    use HasFactory;
    
    protected $primaryKey = "SubDirectoryId";
    public $timestamps = false;
    public $table="TSubDirectories";

    protected $fillable = [
        "SubDirectoryName",
        "DirectoryFId"
    ];

    public function directory(){
        return $this->belongsTo(Directory::class);
    }
}
