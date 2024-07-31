<?php

namespace Tests\Unit\Services\Api;

use App\Models\Empresa;
use App\Models\User;
use App\Services\Api\EmpresaService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Mockery;

class EmpresaServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EmpresaService $empresaService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->empresaService = new EmpresaService();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        $request = [
            'per_page' => 10,
            'page' => 1,
            'order_by' => ['id', 'asc']
        ];

        $result = $this->empresaService->index($request, $this->user);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function testShow()
    {
        $empresa = $this->user
            ->empresas()
            ->inRandomOrder()
            ->first();

        $result = $this->empresaService->show($empresa);

        $this->assertInstanceOf(Empresa::class, $result);
        $this->assertEquals($empresa->id, $result->id);
    }

    public function testStore()
    {
        $empresaData = Empresa::factory()->make()->toArray();

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $result = $this->empresaService->store($empresaData, $this->user);

        $this->assertInstanceOf(Empresa::class, $result);

        $expectedData = Arr::except($empresaData, ['user_id']);
        $resultData = Arr::except($result->toArray(), ['id', 'created_at', 'updated_at', 'user_id']);

        $this->assertEquals($expectedData, $resultData);
        $this->assertEquals($this->user->id, $result->user_id);
    }

    public function testStoreException()
    {
        $empresaData = Empresa::factory()->make(['status' => 'invalid'])->toArray();

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();

        $this->expectException(Exception::class);

        $this->empresaService->store($empresaData, $this->user);
    }

    public function testUpdate()
    {
        $empresa = $this->user
            ->empresas()
            ->inRandomOrder()
            ->first();
        $updatedData = ['razao_social' => 'Updated Company Name'];

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $result = $this->empresaService->update($updatedData, $empresa);

        $this->assertTrue($result);
        $updatedEmpresa = $empresa->fresh();
        $this->assertEquals('Updated Company Name', $updatedEmpresa->razao_social);
    }

    public function testUpdateException()
    {
        $empresa = $this->user
            ->empresas()
            ->inRandomOrder()
            ->first();
        $updatedData = ['status' => 'invalid'];

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();

        $this->expectException(Exception::class);

        $this->empresaService->update($updatedData, $empresa);
    }

    public function testDestroy()
    {
        $empresa = $this->user
            ->empresas()
            ->inRandomOrder()
            ->first();

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $result = $this->empresaService->destroy($empresa);

        $this->assertTrue($result);
        $this->assertNull(Empresa::find($empresa->id));
    }

    public function testDestroyException()
    {
        $user = User::factory()->create();
        $empresa = $user->empresas()
            ->first();

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();

        $this->expectException(Exception::class);

        $this->empresaService->destroy($empresa);
    }
}
