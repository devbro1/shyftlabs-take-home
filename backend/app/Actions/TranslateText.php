<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

class TranslateText
{
    use AsAction;

    public function handle(string $text, $language = 'fr')
    {
        $client = \OpenAI::client(env('OPEN_AI_KEY', 'false'));

        $result = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                // ['role' => 'system' ,'content' => 'You are a translation engine from English to French, and will translate whate'],
                ['role' => 'user', 'content' => 'translate to french: '.$text],
            ],
        ]);

        return $result->choices[0]->message->content;
    }
}
