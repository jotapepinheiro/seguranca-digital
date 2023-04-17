<?php

namespace Tests\Unit;

use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\{Role, User, Permission};
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;


class AclTest extends TestCase
{
    /**
     * Clean BD Transactions
     */
    use DatabaseTransactions;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::find(2);
    }

    /**
     * Testar página home
     *
     * @return void
     */
    public function testHome(): void
    {
        $this->get('/');

        $this->seeStatusCode(Response::HTTP_OK);
    }

    public function testCreateRole()
    {
        $this->be($this->user);

        $data = Role::factory()->make()->toArray();

        $this->post('api/v1/roles', $data);
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJson([
            'message' => 'Operação realizada com sucesso.'
        ]);
    }

    public function testCreatePermission()
    {
        $this->be($this->user);

        $data = Permission::factory()->make()->toArray();

        $this->post('api/v1/permissions', $data);
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJson([
            'message' => 'Operação realizada com sucesso.'
        ]);
    }

    public function testListUserRolesPermissions()
    {
        $this->be($this->user);

        $data = User::inRandomOrder()->first();

        $this->get('api/v1/users/'.$data->id);
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJson([
            'name' => $data->name,
        ]);
    }
}
