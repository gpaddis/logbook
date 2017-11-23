<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VisitsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Return structured information about the year's visits.
     *
     * @param int $year
     * @return void
     */
    public function year($year)
    {
        $data = ['year' => $year];

        $validator = Validator::make($data, [
            'year' => 'int|max:' . date('Y')
        ]);

        if ($validator->fails()) {
            return new JsonResponse('Your request cannot be processed', 422);
        }

        return [
            'Year' => $year
        ];
    }
}
