<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return (CategoryResource::collection($categories))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return (CategoryResource::make($category))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $data = $request->processedTransactions();

        $category = Category::create($data);
        return CategoryResource::make($category)
            ->additional(['message' => 'Categoria adicionada com sucesso!'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
    public function update(UpdateCategoryRequest $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return (CategoryResource::make($category))->response()->setStatusCode(Response::HTTP_OK);
    }

//    TODO - Ao deletar uma categoria, as trasações pertencentes a ela devem ir para caregoria 'Sem categoria'
}
