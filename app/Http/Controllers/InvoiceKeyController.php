<?php

namespace App\Http\Controllers;
use App\Models\InvoiceKey;
use App\Http\Resources\InvoiceKeyCollection;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class InvoiceKeyController extends Controller
{
    public function index(){
        $invoiceKeys = InvoiceKey::all();
        return new InvoiceKeyCollection($invoiceKeys);
    }

    public function store(Request $request){
        $validatedData=Validator::make($request->all(),[
            'Invoicekey' => 'required|string|unique:TInvoicekeys,Invoicekey',
            'DirectoryFId'=>'required|integer|exists:TDirectories,DirectoryId'
        ]);

        if($validatedData->fails()){
            return response()->json([
                'status'=>401,
                'message'=> "Echec d'enregistrement",
                "errors"=>$validatedData->errors(),
            ]);
        }
        $directory = InvoiceKey::create($request->all());
        return response()->json([
            'status'=>201,
            'message'=> "Enregistrement reussie",
        ]);
    }


}
