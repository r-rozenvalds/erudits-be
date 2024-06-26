<?php

namespace App\Http\Controllers\Api;

use App\Models\OpenAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use function Psy\debug;


class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->calculatePoints();
        return PlayerResource::collection(Player::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlayerRequest $request)
    {
        $validated = $request->validated();
        return new PlayerResource(Player::create($validated));
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        return new PlayerResource($player);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlayerRequest $request, Player $player)
    {
        $validated = $request->validated();

        $player->update($validated);
        return new PlayerResource($player);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        $player->delete();
        return response()->json();
    }

    public function storeOpenAnswer(Request $request)
    {
        $validated = $request->validate([
            "answer" => "nullable",
            "player_id" => "required",
            "question_id" => "required",
        ]);
        return OpenAnswer::create($validated);
    }

    public function calculatePoints()
    {
        $players = Player::all();

        foreach ($players as $player) {
            $player->points = 0;
            $player->tiebreak_points = 0;

            foreach ($player->player_answers as $playerAnswer) {
                $questionGroup = $playerAnswer->question->question_group;
                $pointsToAdd = $questionGroup->points;

                if ($questionGroup->is_additional) {
                    if ($playerAnswer->answer->is_correct) {
                        $player->tiebreak_points += $pointsToAdd;
                    }
                } else {
                    if ($playerAnswer->answer->is_correct) {
                        $player->points += $pointsToAdd;
                    }
                }
            }

            $player->save();
        }
    }

    public function setPlayerInactive(Player $player)
    {
        $player->update(['is_active' => false]);
        return response()->json();
    }

    public function disqualifyPlayer(Player $player)
    {
        $player->update(['is_disqualified' => true]);
    }

    public function requalifyPlayer(Player $player)
    {
        $player->update(['is_disqualified' => false]);
    }

    public function tiebreakSelectedPlayers(Request $request)
    {
        $validated = $request->validate([
            'player_ids' => 'required|array',
        ]);

        foreach ($validated['player_ids'] as $player_id) {
            $player = Player::find($player_id);
            $player->is_tiebreaking = true;
            $player->save();
        }

        return response()->json();
    }

    public function disqualifySelectedPlayers(Request $request)
    {
        $validated = $request->validate([
            'player_ids' => 'required|array',
        ]);

        foreach ($validated['player_ids'] as $player_id) {
            $player = Player::find($player_id);
            $player->is_disqualified = true;
            $player->save();
        }

        return response()->json();
    }
}
