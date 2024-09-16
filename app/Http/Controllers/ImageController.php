<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use Carbon\Carbon;

class ImageController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|mimes:jpeg,png,jpg,gif,pdf,doc|max:4048',
            'InvoiceFId' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $imageFile = $request->file('image');
        $originalName = $imageFile->getClientOriginalName();
        $path = $imageFile->store('images', 'public');
        $publicUrl = Storage::url($path);

        $image = Image::create([
            'InvoiceFId' => $request->InvoiceFId,
            'ImageName' => pathinfo($originalName, PATHINFO_FILENAME),
            'ImagePath' => $path,
            'PublicUrl' => $publicUrl,
            'ImageOriginalName' => $originalName,
            'InsertedTime' => Carbon::now()->toDateTimeString(),
        ]);

        return response()->json([
            'message' => "images enregitrees"
        ], 200);
    }

    public function update(Request $request, $id){
    $validator = Validator::make($request->all(), [
        'image' => 'sometimes|mimes:jpeg,png,jpg,gif,pdf,doc|max:4048',
        'InvoiceFId' => 'required|integer',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $image = Image::findOrFail($id);
    
    if ($request->hasFile('image')) {
        // Supprimer l'ancienne image si nécessaire
        Storage::disk('public')->delete($image->ImagePath);
        
        $imageFile = $request->file('image');
        $originalName = $imageFile->getClientOriginalName();
        $path = $imageFile->store('images', 'public');
        $publicUrl = Storage::url($path);

        $image->update([
            'ImageName' => pathinfo($originalName, PATHINFO_FILENAME),
            'ImagePath' => $path,
            'PublicUrl' => $publicUrl,
            'ImageOriginalName' => $originalName,
        ]);
    }

    $image->update([
        'InvoiceFId' => $request->InvoiceFId,
        'InsertedTime' => Carbon::now()->toDateTimeString(),
    ]);

    return response()->json([
        'message' => "Image modifiée avec succès"
    ], 200);
}

public function destroy($id){
    $image = Image::findOrFail($id);
    Storage::disk('public')->delete($image->ImagePath);
    $image->delete();

    return response()->json([
        'message' => "Image supprimée avec succès"
    ], 200);
}
}
