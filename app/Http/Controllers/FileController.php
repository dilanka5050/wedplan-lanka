<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $user    = $request->user();
        $wedding = $user->wedding;

        $images    = $wedding->files()->where('type', 'image')->latest()->get();
        $documents = $wedding->files()->where('type', 'document')->latest()->get();

        $uploadCount  = $wedding->files()->count();
        $uploadLimit  = $user->getLimit('uploads');
        $limitReached = $user->reachedLimit('uploads');
        $isPro        = $user->isPro();
        $usedPct      = $uploadLimit ? min(100, round(($uploadCount / $uploadLimit) * 100)) : 0;
        $isArchived   = $wedding->isVaultArchived();

        return view('files.index', compact(
            'images', 'documents', 'wedding', 'isPro',
            'uploadCount', 'uploadLimit', 'limitReached', 'usedPct', 'isArchived'
        ));
    }

    public function store(Request $request)
    {
        $wedding = $request->user()->wedding;

        if ($wedding->isVaultArchived()) {
            return redirect()->back()->with('error', 'Your vault has been archived. New uploads are not allowed.');
        }
        $request->validate([
            'file'  => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx',
            'type'  => 'required|in:image,document',
            'label' => 'nullable|string|max:100',
        ]);

        $uploaded = $request->file('file');

        $path = $uploaded->store("weddings/{$request->user()->wedding->id}/vault", config('filesystems.default'));

        $request->user()->wedding->files()->create([
            'name' => $request->input('label') ?: $uploaded->getClientOriginalName(),
            'path' => $path,
            'type' => $request->input('type'),
            'size' => $uploaded->getSize(),
        ]);

        return redirect()->back()->with('success', 'File uploaded to your Vault!');
    }

    public function destroy(File $file)
    {
        Storage::disk(config('filesystems.default'))->delete($file->path);
        $file->delete();

        return redirect()->back()->with('success', 'File removed from Vault.');
    }

    public function stream(File $file)
    {
        $disk = config('filesystems.default');
        abort_unless(Storage::disk($disk)->exists($file->path), 404);

        $mime = Storage::disk($disk)->mimeType($file->path);
        return response()->stream(function () use ($disk, $file) {
            echo Storage::disk($disk)->get($file->path);
        }, 200, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'inline; filename="' . $file->name . '"',
            'Cache-Control'       => 'private, max-age=3600',
        ]);
    }

    public function download(File $file)
    {
        abort_unless(Storage::disk(config('filesystems.default'))->exists($file->path), 404);
        return Storage::disk(config('filesystems.default'))->download($file->path, $file->name);
    }
}
