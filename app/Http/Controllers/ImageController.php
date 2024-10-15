<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use Carbon\Carbon;

class ImageController extends Controller
{

    public function index(){
        $images = Image::all();
        return new ImageCollection($images);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:4048',
            'InvoiceFId' => 'required|integer',
        ], [
            'image.required' => 'Une image est requise.',
            'image.mimes' => 'Le type de fichier doit être une image (jpeg, png, jpg, gif) ou un document (pdf, doc, docx).',
            'image.max' => 'La taille du fichier ne doit pas dépasser 4 Mo.',
            'InvoiceFId.required' => 'L\'ID de la facture est requis.',
            'InvoiceFId.integer' => 'L\'ID de la facture doit être un entier.',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $imageFile = $request->file('image');
        $originalName = $imageFile->getClientOriginalName();
        $fileExtension = $imageFile->getClientOriginalExtension();
        $publicUrl = '';
        $path = '';
    
        // Vérifiez le type de fichier et attribuez l'URL par défaut si nécessaire
        if (in_array($fileExtension, ['doc', 'docx'])) {
            $path = 'images/doc.png'; // Chemin par défaut pour les fichiers Word
            $publicUrl = Storage::url($path); // URL par défaut pour les fichiers Word
        } elseif ($fileExtension === 'pdf') {
            $path = 'images/pdf.png'; // Chemin par défaut pour les fichiers PDF
            $publicUrl = Storage::url($path); // URL par défaut pour les fichiers PDF
        } else {
            $path = $imageFile->store('images', 'public'); // Stockez l'image normalement
            $publicUrl = Storage::url($path);
        }
    
        $image = Image::create([
            'InvoiceFId' => $request->InvoiceFId,
            'ImageName' => pathinfo($originalName, PATHINFO_FILENAME),
            'ImagePath' => $path, // Utilisez le chemin généré
            'PublicUrl' => $publicUrl,
            'ImageOriginalName' => $originalName,
            'InsertedTime' => Carbon::now()->toDateTimeString(),
        ]);
    
        return response()->json([
            'message' => "Images enregistrées avec succès",
            'public_url' => $publicUrl,
        ], 200);
    }

    public function update(Request $request, $id){
    $validator = Validator::make($request->all(), [
        'image' => 'sometimes|mimes:jpeg,png,jpg,gif,pdf,doc|max:20048',
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
