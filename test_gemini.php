<?php

use App\Services\GeminiService;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$gemini = new GeminiService();
$response = $gemini->generateResponse("Hãy chào tôi bằng tiếng Việt và nói tên bạn là EduAgent AI", "Bạn là EduAgent AI");

if ($response) {
    echo "SUCCESS: Gemini API Response received!\n";
    echo "Response: " . $response . "\n";
} else {
    echo "FAILURE: Gemini API did not return a response. Check your API key and logs.\n";
}
