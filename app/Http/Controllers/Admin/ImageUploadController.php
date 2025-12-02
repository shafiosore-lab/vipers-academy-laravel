<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImageUploadController extends Controller
{
    protected $allowedFolders = [
        'logo' => 'Logo Images',
        'players' => 'Player Images',
        'gallery' => 'Gallery Images',
        'news' => 'News Images',
        'programs' => 'Program Images',
        'partners' => 'Partner Images',
        'general' => 'General Images'
    ];

    public function showUploadForm()
    {
        return view('admin.image-upload', [
            'folders' => $this->allowedFolders
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'folder' => 'required|string|in:' . implode(',', array_keys($this->allowedFolders)),
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480'
        ]);

        $folder = $request->input('folder');
        $uploadedFiles = [];
        $errors = [];

        foreach ($request->file('images') as $file) {
            try {
                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Move file to public/assets/img/{folder}/
                $file->move(public_path('assets/img/' . $folder), $filename);

                $uploadedFiles[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'filename' => $filename,
                    'path' => 'assets/img/' . $folder . '/' . $filename,
                    'size' => $file->getSize(),
                    'folder' => $folder
                ];

            } catch (\Exception $e) {
                $errors[] = 'Failed to upload ' . $file->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }

        if (!empty($uploadedFiles)) {
            return back()->with('success', 'Successfully uploaded ' . count($uploadedFiles) . ' image(s).')
                        ->with('uploaded_files', $uploadedFiles);
        }

        return back()->with('error', 'No images were uploaded successfully.')
                    ->with('upload_errors', $errors);
    }

    public function getImages(Request $request)
    {
        $folder = $request->get('folder', 'general');

        if (!array_key_exists($folder, $this->allowedFolders)) {
            return response()->json(['error' => 'Invalid folder'], 400);
        }

        $path = public_path('assets/img/' . $folder);
        $images = [];

        if (File::exists($path)) {
            $files = File::files($path);
            foreach ($files as $file) {
                $images[] = [
                    'name' => $file->getFilename(),
                    'path' => 'assets/img/' . $folder . '/' . $file->getFilename(),
                    'url' => asset('assets/img/' . $folder . '/' . $file->getFilename()),
                    'size' => $file->getSize(),
                    'modified' => $file->getMTime()
                ];
            }
        }

        return response()->json([
            'folder' => $folder,
            'folder_name' => $this->allowedFolders[$folder],
            'images' => $images
        ]);
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'folder' => 'required|string|in:' . implode(',', array_keys($this->allowedFolders)),
            'filename' => 'required|string'
        ]);

        $folder = $request->input('folder');
        $filename = $request->input('filename');
        $filePath = public_path('assets/img/' . $folder . '/' . $filename);

        if (File::exists($filePath)) {
            File::delete($filePath);
            return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
        }

        return response()->json(['error' => 'Image not found'], 404);
    }
}
