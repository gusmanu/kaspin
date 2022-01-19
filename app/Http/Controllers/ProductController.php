<?php

namespace App\Http\Controllers;

use App\DataTables\ProductsDataTable;
use App\Http\Requests\ProductFormRequest;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:create product')->only(['create', 'createSave']);
        $this->middleware('can:edit product')->only(['edit', 'editSave']);
        $this->middleware('can:delete product')->only(['delete']);
    }
    
    /**
     * Display Table View
     */
    public function index(ProductsDataTable $productsDataTable)
    {
        return $productsDataTable->render('index');
    }

    /**
     * Display Create View
     */
    public function create()
    {
        return view('create');
    }

     /**
     * Create save post
     */
    public function createSave(ProductFormRequest $request)
    {
        $data = $request->validated();
        $createProduct = Product::create($data);
        if ($createProduct) {
            return redirect()->route('index')->withMessage('Produk berhasil dibuat');
        }
        return redirect()->route('index')->withErrors('Produk gagal dibuat');
    }

   /**
     * Display Edit View
     */
    public function edit(Request $request)
    {
        $id = request()->route('id');
        if (!is_numeric($id)) {
            return redirect()->route('index')->withErrors('ID Produk Invalid');
        }
        $product = Product::where('id', $id)->first();
        if (!$product) {
            return redirect()->route('index')->withErrors('Produk Tidak Ditemukan');
        }
        return view('edit', compact('product'));
    }

    /**
     * Edit save post
     */
    public function editSave(ProductFormRequest $request)
    {
        $updateData = $request->validated();
        try {
            DB::beginTransaction();
            $product = Product::where('id', $request->id)->lockForUpdate()->first();
            if (!$product) {
                throw new \Exception('Produk tidak ditemukan');
            }
            $product->update($updateData);
            DB::commit();
            return redirect()->route('index')->withMessage('Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withMessage($e->getMessage())->withInput();
        }
    }

    /**
     * Delete product
     */
    public function delete(Request $request)
    {
        $validator = $this->validateIds($request->only('ids'));
        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'message' => $validator->getMessageBag()], 422);
        }
        $products = Product::whereIn('id', $request->ids)->get();
        if (!$products) {
            return response()->json(['status' => 'fail', 'message' => 'not found'], 404);
        }
        $toBeDeletedIds= [];
        foreach ($products as $product) {
            $toBeDeletedIds[] = $product->id;
        }
        Product::whereIn('id', $toBeDeletedIds)->delete();
        return response()->json(['status' => 'ok', 'message' => 'produk terhapus'], 200);
    }

    public function validateIds($ids)
    {
        return Validator::make($ids, [
            "ids"    => "required|array|min:1",
            "ids.*"  => "required|integer|distinct|min:1",
        ]);
    }
}
