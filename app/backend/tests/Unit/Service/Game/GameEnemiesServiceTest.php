<?php

namespace Tests\Unit\Service\Game;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameEnemiesServiceTest extends TestCase
{
    protected $initialized = false;

    /**
     * 初期化処理
     *
     * @return array
     */
    protected function init(): array
    {

        // 'testing'
        // $env = Config::get('app.env');

        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');

        $response = $this->json('POST', route('auth.user.login'), [
            'email'    => Config::get('myapp.test.user.login.email'),
            'password' => Config::get('myapp.test.user.login.password')
        ])->json();

        return [
            'token'          => $response['access_token'],
            'user_id'        => $response['user']['id']
        ];
    }

    /**
     * setUpは各テストメソッドが実行される前に実行する
     * 親クラスのsetUpを必ず実行する
     */
    protected function setUp(): void
    {
        parent::setUp();
        $loginUser = [];

        if (!$this->initialized) {
            $loginUser         = $this->init();
            $this->initialized = true;
        }


        $this->withHeaders([
            'X-Auth-ID'        => $loginUser['user_id'],
            'X-Auth-Authority' => $loginUser['user_authority'],
            'Authorization'    => 'Bearer '. $loginUser['token'],
         ]);
    }

    /**
     * enemies get request test.
     *
     * @return void
     */
    public function testGetEnemies(): void
    {
        $response = $this->get(route('service.game.enemies.index'));
        $response->assertStatus(200)
            ->assertJsonCount(12, 'data');
    }
}
