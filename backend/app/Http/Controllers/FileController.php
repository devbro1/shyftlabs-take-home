<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // 'file' => 'required|mimes:doc,docx,pdf,txt,jpg,png|max:2048',
                'file' => 'required|file|mimes:doc,docx,pdf,txt,csv,xls,xlsx,jpg,png,gif,jpeg|max:5120',
            ],
            [
                'file.max' => 'File cannot be larger than 5MB',
                // 'file.mimes' => 'Valid file types are documents, excel, pdf, text, csv, and images'
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

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, File $file)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        $local_path = storage_path('app/'.$file->path);
        Storage::delete($file->path);

        $file->delete();

        return ['status' => 'OK', 'message' => 'file was deleted successfully'];
    }
}
