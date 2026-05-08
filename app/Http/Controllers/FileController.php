<?php

namespace App\Http\Controllers;

use App\Models\StoredFile;
use App\Services\ImageUploadOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FileController extends Controller
{
    public function __construct(
        private readonly ImageUploadOptimizer $imageUploadOptimizer,
    ) {
    }

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
            $storedFile = $this->storeUploadedFile($uploadedFile, $directory);

            StoredFile::query()->create([
                'original_name' => $uploadedFile->getClientOriginalName(),
                'stored_name' => basename($storedFile['path']),
                'path' => $storedFile['path'],
                'disk' => 'public',
                'mime_type' => $storedFile['mime_type'],
                'extension' => $storedFile['extension'],
                'size' => $storedFile['size'],
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

    /**
     * @return array{path: string, mime_type: string, extension: string, size: int}
     */
    private function storeUploadedFile(UploadedFile $uploadedFile, string $directory): array
    {
        $optimized = $this->imageUploadOptimizer->optimize($uploadedFile);

        if ($optimized !== null) {
            $filename = Str::random(40).'.'.$optimized['extension'];
            $path = $directory.'/'.$filename;

            Storage::disk('public')->put($path, $optimized['contents']);

            return [
                'path' => $path,
                'mime_type' => $optimized['mime_type'],
                'extension' => $optimized['extension'],
                'size' => $optimized['size'],
            ];
        }

        $storedPath = $uploadedFile->store($directory, 'public');

        return [
            'path' => $storedPath,
            'mime_type' => $uploadedFile->getClientMimeType() ?: $uploadedFile->getMimeType() ?: 'application/octet-stream',
            'extension' => strtolower((string) $uploadedFile->getClientOriginalExtension()),
            'size' => (int) $uploadedFile->getSize(),
        ];
    }
}
