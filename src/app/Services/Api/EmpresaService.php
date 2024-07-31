<?php

namespace App\Services\Api;

use App\Models\Empresa;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EmpresaService
{
    public function index(array $request, User $user): LengthAwarePaginator
    {
        $perPage = $request['per_page'] ?? 15;
        $page = $request['page'] ?? 1;
        $where = $request['where'] ?? [];
        $orWhere = $request['or_where'] ?? [];
        $order = $request['order_by'] ?? ['id', 'asc'];

        return $user->empresas()
            ->where($where)
            ->orWhere($orWhere)
            ->orderBy(...$order)
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function show(Empresa $empresa): Empresa
    {
        return $empresa;
    }

    public function store(array $request, User $user): Empresa
    {
        try {
            DB::beginTransaction();
            $empresa = $user->empresas()->create($request);
            DB::commit();

            return $empresa;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(array $request, Empresa $empresa): bool
    {
        try {
            DB::beginTransaction();
            $result = $empresa->update($request);
            DB::commit();

            return $result;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy(Empresa $empresa): bool
    {
        try {
            DB::beginTransaction();
            $result = $empresa->delete();
            DB::commit();

            return $result;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
