<?php

namespace App\Services;

use App\Models\Developer;
use App\Models\Game;
use App\Models\Genre;
use Illuminate\Http\Request;

class GameService
{
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
    public function store(Request $request) {
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
    public function show(string $id)
    {
        $game = Game::find($id);
        if ($game == null) {
            return abort(404);
        }

        return response($this->prepareGame($game));
    }

    public function update(Request $request, string $id) {
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
        return response($this->prepareGame($game));
    }
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
