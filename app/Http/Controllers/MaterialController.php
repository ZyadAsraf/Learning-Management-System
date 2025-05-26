<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        return Material::with('course')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,mp4,mov,avi,wmv|max:51200', // max 50MB
        ]);

        $filePath = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            $fileType = in_array($extension, ['pdf']) ? 'pdf' : 'video';
            $filePath = $file->store('materials', 'public');
        }

        $material = Material::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'content' => $request->content,
            'file_path' => $filePath,
            'file_type' => $fileType,
        ]);

        return response()->json($material, 201);
    }

    public function show(Material $material)
    {
        return $material->load('course');
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,mp4,mov,avi,wmv|max:51200',
        ]);

        if ($request->hasFile('file')) {
            // Delete old file
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileType = in_array($extension, ['pdf']) ? 'pdf' : 'video';
            $filePath = $file->store('materials', 'public');

            $material->update([
                'file_path' => $filePath,
                'file_type' => $fileType,
            ]);
        }

        $material->update($request->only(['title', 'content']));

        return response()->json($material);
    }

    public function destroy(Material $material)
    {
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return response()->json(['message' => 'Material deleted successfully']);
    }
}
