<?php

namespace App\Http\Controllers;

use App\Services\GenreService;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Handle the incoming request.
     */

    private $genreService;

    public function __construct(GenreService $genreService)
    {
        $this->genreService = $genreService;
    }

    public function __invoke(Request $request, string $id = null)
    {
        return $this->genreService->invoke($request, $id);
    }
}
