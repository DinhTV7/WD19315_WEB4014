<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Tìm kiếm theo mã sản phẩm
        if ($request->filled('ma_san_pham')) {
            $query->where('ma_san_pham', 'LIKE', '%' . $request->ma_san_pham . '%');
        }
        // Thực hiện tìm kiếm theo:
        // Tên sản phẩm, Danh mục, Khoảng giá, Ngày nhập, Trạng thái

        $query->orderBy('id', 'DESC'); // Sắp xếp

        $products = $query->paginate(10);

        // $products = DB::table('products')->where('deleted_at', null)->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        dd($product->ten_san_pham);

        // BTVN:
        // - Hiển thị thông tin chi tiết sản phẩm ra giao diện
        // - Xây dựng giao diện trang thêm, sửa 
    }

    public function create()
    {
        // Lấy ra danh sách danh mục
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // // Lấy ra giá trị
        // $dataNew = [
        //     'ma_san_pham'    => $request->ma_san_pham,
        //     'ten_san_pham'   => $request->ten_san_pham,
        //     'category_id'    => $request->category_id,
        //     'gia'            => $request->gia,
        //     'gia_khuyen_mai' => $request->gia_khuyen_mai,
        //     'so_luong'       => $request->so_luong,
        //     'ngay_nhap'      => $request->ngay_nhap,
        //     'mo_ta'          => $request->mo_ta,
        //     'trang_thai'     => $request->trang_thai,
        // ];

        // Validate
        $dataNew = $request->validate([
            'ma_san_pham'       => 'required|string|max:20|unique:products,ma_san_pham',
            'ten_san_pham'      => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'hinh_anh'          => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'gia'               => 'required|numeric|min:0|max:99999999',
            'gia_khuyen_mai'    => 'nullable|numeric|min:0|lt:gia',
            'so_luong'          => 'required|integer|min:1',
            'ngay_nhap'         => 'required|date',
            'mo_ta'             => 'nullable|string',
            'trang_thai'        => 'required|boolean',
        ]);

        // Xử lý hình ảnh
        if ($request->hasFile('hinh_anh')) {
            $imgPath = $request->file('hinh_anh')->store('images/products', 'public');
            $dataNew['hinh_anh'] = $imgPath;
        }

        Product::create($dataNew);

        return redirect()->route('admin.products.index');
    }

    public function edit ($id) 
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update (Request $request, $id) 
    {
        $product = Product::findOrFail($id);

        // Validate
        $dataNew = $request->validate([
            'ma_san_pham'       => 'required|string|max:20|unique:products,ma_san_pham,' . $id,
            'ten_san_pham'      => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'hinh_anh'          => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'gia'               => 'required|numeric|min:0|max:99999999',
            'gia_khuyen_mai'    => 'nullable|numeric|min:0|lt:gia',
            'so_luong'          => 'required|integer|min:1',
            'ngay_nhap'         => 'required|date',
            'mo_ta'             => 'nullable|string',
            'trang_thai'        => 'required|boolean',
        ]);

        // Xử lý hình ảnh
        if ($request->hasFile('hinh_anh')) {
            $imgPath = $request->file('hinh_anh')->store('images/products', 'public');
            $dataNew['hinh_anh'] = $imgPath;

            // Nếu có hình ảnh mới thì phải xóa ảnh cũ đi
            if ($product->hinh_anh) {
                // use Illuminate\Support\Facades\Storage;
                Storage::disk('public')->delete($product->hinh_anh);
            }
        }

        $product->update($dataNew);

        return redirect()->route('admin.products.index');
    }

    public function destroy ($id) 
    {
        $product = Product::findOrFail($id);

        if ($product->hinh_anh) {
            // use Illuminate\Support\Facades\Storage;
            Storage::disk('public')->delete($product->hinh_anh);
        }

        $product->delete();
        return redirect()->route('admin.products.index')
                            ->with('success', 'Xóa sản phẩm thành công');
    }
}
