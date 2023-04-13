<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return QueryBuilder::for(Translation::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('original'),
                AllowedFilter::partial('translated'),
                AllowedFilter::partial('language'),
            ])
            ->allowedSorts(['id', 'original', 'translated', 'language'])
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
        $translation = new Translation(Translation::validate($request));
        $translation->save();

        $translation_file = 'public/translation/'.$translation->namespace.'-'.$translation->language.'.json';
        Storage::delete($translation_file);

        return ['message' => 'Translation was created successfully', 'data' => $translation];
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Translation $translation)
    {
        return $translation;
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Translation $translation)
    {
        $validated_data = Translation::validate($request, $translation);
        $translation->update($validated_data);

        $translation_file = 'public/translation/'.$translation->namespace.'-'.$translation->language.'.json';
        Storage::delete($translation_file);

        return ['message' => 'Translation was updated successfully', 'data' => $translation];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Translation $translation)
    {
        $translation->delete();

        return ['message' => 'Translation was deleted successfully'];
    }

    /**
     * @OA\Get(
     *     path="/api/v1/cached-translations/{lang}",
     *     summary="get list of all users",
     *     tags={"Translation"},
     *     @OA\Parameter(
     *         description="Langunage",
     *         in="path",
     *         name="lang",
     *         required=true, @OA\Schema(
     *             type="string"
     *         ),
     *         @OA\Examples(
     *             example="string",
     *             value="en",
     *             summary="English language."
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function getCachedTranslation(Request $request, string $lang, string $namespace = 'translation')
    {
        $allowed_languages = ['en', 'en-US', 'fr', 'es'];
        if (!in_array($lang, $allowed_languages)) {
            return response('translation not found', 404);
        }

        $lang = substr($lang, 0, 2);
        $translation_file = "public/translation/{$namespace}-{$lang}.json";

        if (!Storage::exists($translation_file)) {
            $all = Translation::where('language', $lang)->where('namespace', $namespace)->get();
            $trans = [];
            foreach ($all as $translation) {
                $trans[$translation->key] = $translation->translation;
            }

            Storage::put($translation_file, json_encode($trans));
        }

        return response()->file(storage_path('app/'.$translation_file));

        return ['' => ''];
    }

    public function getNamespaces()
    {
        $rc = [];

        $rc['leftmenu'] = 'Left Menu';
        $rc['topmenu'] = 'Top Menu';
        $rc['bottommenu'] = 'Bottom Menu';
        $rc['datatable'] = 'Data Table';
        $rc['announcementpages'] = 'Announcement Pages';
        $rc['translation'] = 'Generic';

        return $rc;
    }
}
