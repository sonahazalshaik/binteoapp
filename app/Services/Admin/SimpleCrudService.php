<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class SimpleCrudService
{
    public function authorize(string $ability): void
    {
        abort_if(Gate::denies($ability), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function create(string $modelClass, array $data)
    {
        return $modelClass::create($data);
    }

    public function update($model, array $data)
    {
        $model->update($data);
        return $model;
    }

    public function delete($model): void
    {
        $model->delete();
    }

    public function massDelete(string $modelClass, array $ids): void
    {
        $modelClass::whereIn('id', $ids)->delete();
    }

    public function handleFileUpload($model, $request, string $field): void
    {
        $path = fileUploader($request->file($field), getFilePath($field));
        $model->update([$field => $path]);
    }
}
