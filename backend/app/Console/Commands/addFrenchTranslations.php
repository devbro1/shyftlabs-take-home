<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Translation;
use App\Actions\TranslateText;

class addFrenchTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-french-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $namespaces = ['leftmenu'];

        foreach ($namespaces as $namespace) {
            foreach (Translation::where('language', 'en')->where('namespace', $namespace)->get() as $eng_translation) {
                $fre_translation = Translation::where('language', 'fr')
                    ->where('namespace', $namespace)
                    ->where('key', $eng_translation->key)->first()
                ;

                if (!$fre_translation) {
                    $fre_translation = new Translation();
                    $fre_translation->namespace = $namespace;
                    $fre_translation->language = 'fr';
                    $fre_translation->key = $eng_translation->key;
                    $fre_translation->translation = TranslateText::run($eng_translation->key);
                    $fre_translation->save();
                }
            }
        }
    }
}
