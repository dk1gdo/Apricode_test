<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Developer;
use App\Models\Genre;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = array();
        $games = Game::all();
        if ($games == null) {
            return response("{\n\t\"message\" : \"database is empty!\"\n}");
        }
        foreach ($games as $game) {
            $result[] = $this->prepareGame($game);
        }
        return response($result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $developer = Developer::where("name", "=" ,$request->get("developer"))->first();
        if(is_null($developer)) {
            $developer = Developer::create([
                "name" => $request->get("developer")
            ]);
        }
        $game = Game::create([
            "name" => $request->get("name"),
            "developer_id" => $developer->id
        ]);
        $requestGenres = $request->get("genres");
        foreach ($requestGenres as $requestGenre) {
            $genre = Genre::where("name", "=", $requestGenre)->first();
            if (is_null($genre))
                $genre = Genre::create([
                    "name" => $requestGenre
                ]);
            $game->genres()->attach([
                $genre->id
            ]);
        }
        $game->push();
        return response($this->prepareGame($game));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $game = Game::find($id);
        if ($game == null) {
            return abort(404);
        }

        return response($this->prepareGame($game));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $game = Game::find($id);
        if ($game == null) {
            return abort(404);
        }
        if ($request->get("name")) {
            $game->name = $request->get("name");
        }
        if ($request->get("developer")){
            $developer = Developer::where("name", "=" ,$request->get("developer"))->first();
            if(is_null($developer)) {
                $developer = Developer::create([
                    "name" => $request->get("developer")
                ]);
            }
            $game->developer_id = $developer->id;
        }
        if ($request->get("genres")) {
            $requestGenres = $request->get("genres");
            $game->genres()->detach();
            foreach ($requestGenres as $requestGenre) {
                $genre = Genre::where("name", "=", $requestGenre)->first();
                if (is_null($genre))
                    $genre = Genre::create([
                        "name" => $requestGenre
                    ]);
                $game->genres()->attach([
                    $genre->id
                ]);
            }
            $game->push();
        }
        return response($this->show($game->id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $game = Game::find($id);
        if ($game == null) {
            return abort(404);
        }
        $game->genres()->detach();
        $game->delete();
        return response("{\n\t\"message\" : \"This entry deleted!\"\n}");
    }

    public function prepareGame(Game $game) {
        return [
            "id" => $game->id,
            "name" => $game->name,
            "genres" => $game->genres->pluck(['name'])->all(),
            "developer" => $game->developer->name
        ];
    }


}
