<?php

namespace App\Http\Controllers;

use App\Models\StoredFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FileController extends Controller
{
    // Отображает список загруженных файлов.
    public function index(): View
    {
        $files = StoredFile::query()
            ->with('uploader:id,name')
            ->latest()
            ->paginate(20);

        return view('files', [
            'files' => $files,
        ]);
    }

    // Загружает файл в хранилище и создаёт запись в базе.
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'array', 'min:1'],
            'file.*' => ['required', 'file', 'max:10240'],
            'directory' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $directory = trim((string) ($validated['directory'] ?? 'uploads'));
        $directory = $directory !== '' ? trim($directory, '/') : 'uploads';
        $directory = 'uploads/'.$directory;

        foreach ($validated['file'] as $uploadedFile) {
            $storedPath = $uploadedFile->store($directory, 'public');

            StoredFile::query()->create([
                'original_name' => $uploadedFile->getClientOriginalName(),
                'stored_name' => basename($storedPath),
                'path' => $storedPath,
                'disk' => 'public',
                'mime_type' => $uploadedFile->getClientMimeType() ?: $uploadedFile->getMimeType(),
                'extension' => strtolower((string) $uploadedFile->getClientOriginalExtension()),
                'size' => (int) $uploadedFile->getSize(),
                'directory' => $directory,
                'description' => $validated['description'] ?? null,
                'uploaded_by' => $request->user()?->id,
            ]);
        }

        return redirect()->route('files')->with('status', 'file-uploaded');
    }

    // Удаляет файл из хранилища и его запись из базы.
    public function destroy(StoredFile $file): RedirectResponse
    {
        if ($file->path !== '') {
            Storage::disk($file->disk ?: 'public')->delete($file->path);
        }

        $file->delete();

        return redirect()->route('files')->with('status', 'file-deleted');
    }
}
