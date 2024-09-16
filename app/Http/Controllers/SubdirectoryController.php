<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubDirectoryCollection;
use App\Models\Subdirectory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubdirectoryController extends Controller
{
    public function index(){
        $subDirectories = Subdirectory::all();
        return new SubDirectoryCollection($subDirectories);
    }
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'SubDirectoryName' => 'required|string|unique:TSubDirectories,SubDirectoryName',
            'DirectoryFId' => 'required|integer|exists:TDirectories,DirectoryId'
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 401,
                'message' => "Echec d'enregistrement",
                'errors' => $validatedData->errors(),
            ]);
        }

        $subDirectory = Subdirectory::create($request->all());

        return response()->json([
            'status' => 201,
            'message' => "Enregistrement réussi",
            'data' => $subDirectory,
        ]);
    }
}
