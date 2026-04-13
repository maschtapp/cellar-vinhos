<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data['created_by'] = 'admin'; // 🔥 necessário

        return Category::create($data);
    }

    public function show(string $id)
    {
        return Category::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($data);

        return $category;
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->tickets()->exists()) {
            return response()->json([
                'error' => 'Categoria possui chamados vinculados'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'message' => 'Categoria deletada com sucesso'
        ], 200);
    }
}
