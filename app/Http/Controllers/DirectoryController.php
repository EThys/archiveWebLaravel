<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Directory;
use App\Models\TInvoice;
use App\Models\InvoiceKey;
use App\Http\Resources\DirectoryCollection;
use App\Http\Resources\InvoiceCollection;
use Illuminate\Support\Facades\Validator;

class DirectoryController extends Controller
{
    

    public function filter(Request $request)
    {   
        $directory = request('directory');
       
        if (count(Directory::where("DirectoryName", $directory)->get()) >= 1) {
            $query = TInvoice::query()->when((Directory::where("DirectoryName", $directory)->first())->DirectoryId, function ($q) use ($directory) {
                return $q->where('DirectoryFId', (Directory::where("DirectoryName", $directory)->first())->DirectoryId);
            })->with('user.branch', 'invoicekey', 'directory', "images");
            
            $data = $query->paginate(5);
    
            return response()->json([
                'status' => 201,
                'data' => $data,
            ]);
        }
    
        return response()->json([
            'status' => 404,
            'message' => 'Directory not found',
        ]);
    }

    public function index(){
        $directories = Directory::all();
        return new DirectoryCollection($directories);
    }

    public function getAllDirectoryWithInvoicekey() {
        $invoiceKeys = Invoicekey::all();
        $directories = Directory::all(); 
    
        return [
            'invoiceKeys' => new InvoiceCollection($invoiceKeys),
            'directories' => new DirectoryCollection($directories),
        ];
    }
    public function store(Request $request){
        $validatedData=Validator::make($request->all(),[
            'DirectoryName' => 'required|string|unique:TDirectories,DirectoryName',
            'ParentFId'=>'nullable|integer'
        ]);

        if($validatedData->fails()){
            return response()->json([
                'status'=>401,
                'message'=> "Echec d'enregistrement",
                "errors"=>$validatedData->errors(),
            ]);
        }
        $directory = Directory::create($request->all());
        return response()->json([
            'status'=>201,
            'message'=> "Enregistrement reussie",
        ]);
    }
}
