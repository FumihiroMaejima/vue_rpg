<?php

namespace App\Services\Game;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

use App\Http\Resources\Game\GameEnemiesServiceResource;
use App\Repositories\GameEnemies\GameEnemiesRepository;
use App\Repositories\GameEnemies\GameEnemiesRepositoryInterface;
use Exception;

class GameEnemiesService
{
    protected $enemiesRepository;

    /**
     * create GameEnemiesService instance
     * @param  \App\Repositories\GameEnemies\GameEnemiesRepositoryInterface  $rolesRepository
     * @return void
     */
    public function __construct(GameEnemiesRepositoryInterface $enemiesRepository)
    {
        $this->enemiesRepository = $enemiesRepository;
    }

    /**
     * get roles data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnemies(Request $request)
    {
        $collection = $this->enemiesRepository->getGameEnemies();
        $resourceCollection = app()->make(GameEnemiesServiceResource::class, ['resource' => $collection]);

        return response()->json($resourceCollection->toArray($request), 200);
    }
}
