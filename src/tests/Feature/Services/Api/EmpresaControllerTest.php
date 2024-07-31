<?php

namespace Tests\Feature\Services\Api;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\JWTAuth;
use Tests\TestCase;

class EmpresaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');

        $this->user = User::factory()->create([
            'password' => Hash::make('password')
        ]);

        $this->actingAs($this->user, 'api');
    }

    public function test_it_can_list_all_enterprises()
    {
        $response = $this->getJson('/api/empresa');

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure([
                     'current_page',
                     'data' => [
                         '*' => [
                             'id',
                             'user_id',
                             'razao_social',
                             'nome_fantasia',
                             'cnpj',
                             'status',
                             'created_at',
                             'updated_at',
                         ]
                     ],
                     'first_page_url',
                     'last_page',
                     'last_page_url',
                     'path',
                     'per_page',
                     'total'
                 ]);
    }

    public function test_it_can_show_a_specific_enterprise()
    {
        $empresa = $this->user->empresas()->first();
        $response = $this->getJson("/api/empresa/{$empresa->id}");

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'id' => $empresa->id,
                     'user_id' => $empresa->user_id,
                     'razao_social' => $empresa->razao_social,
                     'nome_fantasia' => $empresa->nome_fantasia,
                     'cnpj' => $empresa->cnpj,
                     'status' => $empresa->status,
                     'created_at' => $empresa->created_at->toISOString(),
                     'updated_at' => $empresa->updated_at->toISOString(),
                 ]);
    }

    public function test_it_can_create_an_enterprise()
    {
        $data = Empresa::factory()->make()->toArray();

        $response = $this->postJson('/api/empresa', $data);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_it_can_update_an_enterprise()
    {
        $empresa = $this->user->empresas()->first();
        $data = Empresa::factory()->make()->toArray();

        $response = $this->putJson("/api/empresa/{$empresa->id}", $data);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'message' => 'Enterprise updated successfully.',
                 ]);
    }

    public function test_it_can_delete_an_enterprise()
    {
        $empresa = $this->user->empresas()->first();

        $response = $this->deleteJson("/api/empresa/{$empresa->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('empresas', ['id' => $empresa->id]);
    }
}
