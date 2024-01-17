<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @group File Managements
 */
class FileController extends Controller
{
    public function index()
    {
        return QueryBuilder::for(File::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('filename'),
                AllowedFilter::partial('extension'),
            ])
            ->allowedSorts(['id', 'filename', 'extension'])
            ->jsonPaginate()
        ;
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'file' => 'required|file|mimes:doc,docx,pdf,txt,csv,xls,xlsx,jpg,png,gif,jpeg|max:51200',
            ],
            [
                'file.max' => 'File cannot be larger than 50MB',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 403);
        }

        $saved_path = $request->file->store('public');

        $file = new File();
        $file->filename = $request->file('file')->getClientOriginalName();
        $file->mimetype = $request->file('file')->getMimeType();
        $file->extension = $request->file('file')->extension();
        $file->size = $request->file('file')->getSize();
        $file->path = $saved_path;
        $file->save();

        return ['file' => $file];
    }

    public function show(Request $request, File $file)
    {
        $infoOnly = $request->get('info_only', false);

        if ($infoOnly) {
            return Response()->json($file);
        }

        $local_path = storage_path('app/'.$file->path);

        $headers = [
            'Content-Type: '.$file->mimetype,
        ];

        return Response()->download($local_path, $file->filename, $headers);
    }

    public function destroy(File $file)
    {
        Storage::delete($file->path);
        $file->delete();

        return ['status' => 'OK', 'message' => 'file was deleted successfully'];
    }
}
