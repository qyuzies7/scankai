<?php

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/{any}', function (Request $request, $any) {
    $target = 'http://127.0.0.1:8000/api/' . $any;

    if ($request->isMethod('get')) {
        $response = Http::get($target, $request->query());
    } else {
        $files = $request->allFiles();

        if ($files) {
            $http = Http::timeout(60)
                ->acceptJson();

            foreach (flattenUploadedFilesForProxy($files) as [$fieldName, $uploadedFile]) {
                $http = $http->attach(
                    $fieldName,
                    file_get_contents($uploadedFile->getRealPath()),
                    $uploadedFile->getClientOriginalName()
                );
            }

            $response = $http->post(
                $target,
                $request->except(array_keys($files))
            );
        } else {
            $response = Http::acceptJson()->post($target, $request->all());
        }
    }

    return response($response->body(), $response->status())
        ->withHeaders(collect($response->headers())->mapWithKeys(fn ($value, $key) => [$key => is_array($value) ? implode(', ', $value) : $value])->all());
})->where('any', '.*');

/**
 * Convert Laravel's uploaded file structure into attachable multipart fields.
 *
 * Single upload:
 *   foto => UploadedFile        becomes foto
 * Multiple upload:
 *   foto => [UploadedFile, ...]  becomes foto[]
 *   foto_perbaikan => [...]      becomes foto_perbaikan[]
 */
function flattenUploadedFilesForProxy(array $files, string $prefix = ''): array
{
    $result = [];

    foreach ($files as $key => $value) {
        $fieldName = $prefix === '' ? (string) $key : $prefix . '[' . $key . ']';

        if ($value instanceof UploadedFile) {
            $result[] = [$fieldName, $value];
            continue;
        }

        if (is_array($value)) {
            foreach (flattenUploadedFilesForProxy($value, $fieldName) as [$nestedName, $uploadedFile]) {
                // Numeric array keys from foto[] become foto[0], foto[1].
                // Backend validation/storage expects foto[] / foto_perbaikan[].
                $normalizedName = preg_replace('/\[\d+\]$/', '[]', $nestedName);
                $result[] = [$normalizedName, $uploadedFile];
            }
        }
    }

    return $result;
}
