<?php

namespace App\Http\Controllers;
use App\Http\Resources\InvoiceCollection;
use App\Models\TInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TInvoiceController extends Controller
{
    public function index()
    {
        $invoices = TInvoice::with('user.branch','invoicekey','directory',"images")->orderBy('InvoiceId', 'desc')->paginate(5);
        return new InvoiceCollection($invoices);
    }
    public function getInvoicesForCurrentUser($id)
    {
        $invoices =Tinvoice::where('UserFId', $id)->with('user.branch','invoicekey','directory',"images")->orderBy('InvoiceId', 'desc')->paginate(5);
        return new InvoiceCollection($invoices);
    }
    public function store(Request $request){
        $validatedData=Validator::make($request->all(),[
            'InvoiceCode' => 'required|string',
            'InvoiceDesc' => '',
            'InvoiceBarCode'=>'',
            'UserFId'=>'integer',
            'DirectoryFId'=>'integer',
            'BranchFId'=>'integer',
            'InvoiceDate'=>'string',
            'InvoiceKeyFId'=>'',
            'InvoicePath'=>'',
            'AndroidVersion'=>'string',
            'ClientName'=>'',
            'ClientPhone'=>'',
            'ExpiredDate'=>''    
        ]);

        if($validatedData->fails()){
            return response()->json([
                'status'=>401,
                'message'=> "Echec d'enregistrement",
                "errors"=>$validatedData->errors(),
            ]);
        }

        if($request->InvoiceKeyFId != 0){
            $key = $request->InvoiceKeyFId;
        }
        $invoice = Tinvoice::create([
            'InvoiceCode' => $request->InvoiceCode,
            'InvoiceDesc' =>$request->InvoiceDesc,
            'InvoiceBarCode' =>$request->InvoiceBarCode,
            'UserFId'=>$request->UserFId,
            'DirectoryFId'=>$request->DirectoryFId,
            'BranchFId'=>$request->BranchFId,
            'InvoiceDate'=>$request->BranchFId,
            'InvoiceKeyFId'=>$key,
            'InvoicePath'=>$request->InvoicePath,
            'AndroidVersion'=>$request->AndroidVersion,
            'InvoiceUniqueId'=>$request->InvoiceUniqueId,
            'ClientName'=>$request->ClientName,
            'ClientPhone'=>$request->ClientPhone,
            'ExpiredDate'=>$request->ExpiredDate
        ]);
        return response()->json([
            'status'=>201,
            'message'=> "Enregistrement reussie",
            "invoiceId"=>$invoice->InvoiceId
        ]); 
    }

    public function show($id){
        try {
            $invoice = Tinvoice::where("InvoiceId",$id)->with('user.branch', 'invoicekey', 'directory',"images")->get();
            return response($invoice,201);
        }catch (\Throwable $th) {
        }
    }

    public function update(Request $request, $id){
        try {  
            $data = [
                'InvoiceDesc' => $request->InvoiceDesc,
                'InvoiceBarCode' => $request->InvoiceBarCode
            ];
            $invoice = Tinvoice::find($id);
            if (!$invoice) {
                return response(['message' => 'Invoice not found'], 404);
            }
            $invoice->update($data);
            return response(['message' => "Success"], 200);
        } catch (\Throwable $th) {
            return response(['message' => $th->getMessage()], 500);
        }
    }

    public function filterInvoice(Request $request){
        $query =Tinvoice::query()
        ->when(request('InvoiceCode'), function ($q) {
            return $q->where('InvoiceCode', request('InvoiceCode'));
        })
        ->when(request('InvoiceDesc'), function ($q) {
            return $q->where('InvoiceDesc', request('InvoiceDesc'),);
        })
        ->when(request('InvoiceBarCode'), function ($q) {
            return $q->where('InvoiceBarCode', request('InvoiceBarCode'),);
        })
        ->when([request('dateFrom'),request('dateTo')], function ($q) {
            return $q->whereBetween('InvoiceDate', [request('dateFrom'),request('dateTo')],);
        })
        ->when(request('InvoiceDate'), function ($q) {
            return $q->where('InvoiceDate', request('InvoiceDate'),);
        })
        ->when(request('UserFId'), function ($q) {
            return $q->where('UserFId', request('UserFId'),);
        })
        ->when(request('DirectoryFId'), function ($q) {
            return $q->where('DirectoryFId', request('DirectoryFId'),);
        })
        ->when(request('InvoiceKeyFId'), function ($q) {
            return $q->where('InvoiceKeyFId', request('InvoiceKeyFId'),);
        })
        ->when(request('BranchFId'), function ($q) {
            return $q->where('BranchFId', request('BranchFId'),);
        })
        ->with('user.branch', 'invoicekey', 'directory',"images");
        $data = $query->paginate(5);
        $response =  $data;
        return response($response,201);
    }

    // public function destroy($id)
    // {
    //     //
    //     try {
    //         $invoice = Tinvoice::find($id);
    //         if($invoice){
    //         $picture = Tinvoice::where("InvoiceFId",$id)->get();
    //           foreach ($picture  as $item ) {
    //             Storage::disk('gcs')->delete("GombeIT/Archive-Public/".$item['PictureName']);
    //           }
    //           $invoice->delete();
    //           return response(['message'=>"Suppression réussi avec succès"],201);
    //         }
    //     } catch (\Throwable $th) {    
    //     }
    // }

    
}
