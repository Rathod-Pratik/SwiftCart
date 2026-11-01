<?php

namespace App\Http\Controllers;

use App\Services\S3Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class S3Controller extends Controller
{
    public function __construct(protected S3Service $s3Service) {}

    /**
     * Return a signed (presigned) URL for a given S3 key.
     * This lets the frontend securely access private S3 objects.
     *
     * GET /api/s3/sign?key=profile-images/1/uuid.jpg
     * GET /api/s3/sign?key[]=profile-images/1/a.jpg&key[]=reviews/1/b.jpg
     */
    public function sign(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required_without:keys',
            'keys' => 'required_without:key|array',
            'keys.*' => 'string',
            'expires' => 'nullable|integer|min:1|max:1440',
        ]);

        $expiryMinutes = (int) $request->input('expires', 60);

        // Support both single key and batch keys
        if ($request->filled('keys')) {
            $keys = $request->input('keys');
            $urls = [];

            foreach ($keys as $key) {
                $urls[$key] = $this->s3Service->signedUrl($key, $expiryMinutes);
            }

            return response()->json(['urls' => $urls]);
        }

        $key = $request->input('key');
        $url = $this->s3Service->signedUrl($key, $expiryMinutes);

        if (! $url) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        return response()->json(['url' => $url, 'key' => $key]);
    }
}
