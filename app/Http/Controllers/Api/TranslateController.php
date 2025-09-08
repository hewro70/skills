<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TranslateController extends Controller
{
    public function batch(Request $r)
    {
        $data = $r->validate([
            'target'        => 'required|in:ar,en',
            'source'        => 'nullable|in:auto,ar,en',
            'items'         => 'required|array|min:1|max:200',
            'items.*.id'    => 'required|string',
            'items.*.text'  => 'required|string',
        ]);

        $target = $data['target'];
        $source = $data['source'] ?? 'auto';

        $out = [];
        $url = rtrim(config('services.translate.url', 'https://libretranslate.com'), '/').'/translate';
        $apiKey = config('services.translate.key');

        foreach ($data['items'] as $item) {
            $payload = [
                'q'       => $item['text'],
                'source'  => $source,
                'target'  => $target,
                'format'  => 'text',
            ];
            if ($apiKey) $payload['api_key'] = $apiKey;

            $cacheKey = 'tr:'.$target.':'.sha1($item['text']);

            $translated = Cache::remember($cacheKey, 86400, function() use ($url, $payload) {
                try {
                    $res = Http::asForm()
                        ->timeout(10)
                        ->retry(2, 500) 
                        ->post($url, $payload);

                    if ($res->failed()) {
                        return null; 
                    }

                    $json = $res->json();
                    return $json['translatedText'] ?? null;
                } catch (\Throwable $e) {
                    return null;
                }
            });

            $out[$item['id']] = $translated ?? $item['text'];
        }

        return response()->json(['data' => $out]);
    }
}
