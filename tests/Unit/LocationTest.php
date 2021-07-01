<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;


class LocationTest extends TestCase
{
    /**
     * @var User|null
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User([
            'id' => 1,
            'name' => 'Joao Paulo',
            'email' => 'joaopinheiro.ti@gmail.com'
        ]);
    }

    /**
     * Testar página home
     *
     * @return void
     */
    public function testHome()
    {
        $this->get('/');

        $this->seeStatusCode(200);
    }

    public function testCreateLocation()
    {
        $this->be($this->user);

        $loc = factory(\App\Models\Location::class)->make()->toArray();

        $data = array_merge($loc, ['user_id' => $this->user->id]);

        $this->post('api/v1/locations', $data);
        $this->seeStatusCode(200);
        $this->seeJson([
            'message' => 'Operação realizada com sucesso.'
        ]);
    }

    public function testListLocationUser()
    {
        $this->be($this->user);

        $data = \App\Models\User::inRandomOrder()->first();

        $this->get('api/v1/locations/'.$data->id);
        $this->seeStatusCode(200);
        $this->seeJson([
            'name' => $data->name,
        ]);
    }
}
