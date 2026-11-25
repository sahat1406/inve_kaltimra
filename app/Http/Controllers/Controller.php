<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Parse comma-separated or array `ids` query input into unique positive integers.
     */
    protected function parseSelectedIds(Request $request): array
    {
        $raw = $request->input('ids');

        if (is_string($raw)) {
            $raw = explode(',', $raw);
        }

        if (!is_array($raw)) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map('intval', $raw), fn (int $id) => $id > 0)));
    }
}
