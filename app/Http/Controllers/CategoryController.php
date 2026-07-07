<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('category.index', [
            'title'      => 'Kategori',
            'categories' => Category::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create', [
            'title' => 'Tambah Kategori',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name'        => 'required|unique:categories,name',
            'description' => 'nullable',
        ], [
            'name.required' => 'Nama kategori wajib diisi',
            'name.unique'   => 'Nama kategori sudah ada',
        ]);

        DB::beginTransaction();

        try {
            Category::create($validate);

            DB::commit();
            return to_route('category.index')->withSuccess('Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('category.create')->withError('Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('category.show', [
            'title'    => 'Detail Kategori',
            'category' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('category.edit', [
            'title'    => 'Edit Kategori',
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validate = $request->validate([
            'name'        => 'required|unique:categories,name,' . $category->id,
            'description' => 'nullable',
        ], [
            'name.required' => 'Nama kategori wajib diisi',
            'name.unique'   => 'Nama kategori sudah ada',
        ]);

        DB::beginTransaction();

        try {
            $category->update($validate);

            DB::commit();
            return to_route('category.index')->withSuccess('Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('category.edit', $category)->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        DB::beginTransaction();

        try {
            $category->delete();

            DB::commit();
            return to_route('category.index')->withSuccess('Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('category.index')->withError('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
