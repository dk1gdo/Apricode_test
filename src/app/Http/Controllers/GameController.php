<?php

namespace App\Http\Controllers;


use App\Services\GameService;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Developer;
use App\Models\Genre;

class GameController extends Controller
{
    private $gameService;
    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->gameService->index();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->gameService->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->gameService->show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->gameService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->gameService->destroy($id);
    }




}
