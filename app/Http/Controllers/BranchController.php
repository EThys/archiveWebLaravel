<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Resources\BranchCollection;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    public function index(){
        $branches = Branch::all();
        return new BranchCollection($branches);
    }
    public function store(Request $request){
        $validatedData=Validator::make($request->all(),[
            "BranchName"=>'required|unique:TBranchs'
        ]);

        if($validatedData->fails()){
            return response()->json([
                'status'=>401,
                'message'=> "Echec d'enregistrement",
                "errors"=>$validatedData->errors(),
            ]);
        }
        $branch = Branch::create($request->all());
        return response()->json([
            'status'=>201,
            'message'=> "Enregistrement reussie",
        ]);
    }

    public function destroy(string $id){
        $branche=Branch::find($id);
        $branche->delete();
        return response()->json([
            'status'=>201,
            'message'=>'Suppression reussie'
        ]);
    }
}
