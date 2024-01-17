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
     * @group Translations
     */
    public function index(Request $request)
    {
        return QueryBuilder::for(Translation::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('key'),
                AllowedFilter::partial('translation'),
                AllowedFilter::partial('language'),
                AllowedFilter::partial('namespace'),
            ])
            ->allowedSorts(['id', 'key', 'original', 'translation', 'language', 'namespace'])
            ->jsonPaginate()
        ;
    }

    /**
     * @group Translations
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
     * @group Translations
     */
    public function show(Translation $translation)
    {
        return $translation;
    }

    /**
     * @group Translations
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
     * @group Translations
     */
    public function destroy(Translation $translation)
    {
        $translation->delete();

        return ['message' => 'Translation was deleted successfully'];
    }

    /**
     * @group Translations
     */
    public function getCachedTranslation(Request $request, string $lang, string $namespace = 'translation')
    {
        $allowed_languages = ['en', 'en-US', 'fr', 'es'];
        if (!in_array($lang, $allowed_languages)) {
            return response('translation not found', 404);
        }

        // $lang = substr($lang, 0, 2);
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

    /**
     * @group Translations
     */
    public function getNamespaces()
    {
        $rc = [];

        $rc['leftmenu'] = 'Left Menu';
        $rc['topmenu'] = 'Top Menu';
        $rc['bottommenu'] = 'Bottom Menu';
        $rc['datatable'] = 'Data Table';
        $rc['announcementpages'] = 'Announcement Pages';
        $rc['translation'] = 'Generic';
        $rc['drug'] = 'Drug';

        return $rc;
    }

    public function reportMissingTranslation(Request $request, string $lang, string $namespace = 'translation')
    {
        $key = array_keys($request->all())[0];

        $translation = Translation::where('key', $key)->where('namespace', $namespace)->where('language', $lang)->first();
        if ($translation) {
            return '';
        }

        $translation = Translation::where('key', $key)->where('namespace', $namespace)->where('language', 'en')->first();
        if ($translation) {
            return '';
        }

        $translation = new Translation();
        $translation->language = 'en';
        $translation->namespace = $namespace;
        $translation->key = array_keys($request->all())[0];
        $translation->translation = $translation->key;
        $translation->save();
    }
}
