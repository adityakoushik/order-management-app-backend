<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Spatie\Image\Enums\ImageDriver;
use Spatie\Image\Enums\ImageFormat;
use Spatie\Image\Enums\Fit;

class ProductController extends Controller
{
	// list with pagination
	public function index(Request $request)
	{
		$loggedInUser = $request->user();

		$perPage = (int) $request->query('per_page', 20);

		// If customer -> fetch only parent admin's products
		if ($loggedInUser->role === 'customer' && $loggedInUser->parent_admin_id) {
			$products = Product::where('admin_id', $loggedInUser->parent_admin_id)
				->latest()
				->paginate($perPage);
		} elseif ($loggedInUser->role === 'admin') {
			// If admin -> fetch only their products
			$products = Product::where('admin_id', $loggedInUser->id)
				->latest()
				->paginate($perPage);
		} else {
			// Fallback: fetch all products (for super-admin or undefined roles)
			$products = Product::latest()->paginate($perPage);
			// $products = Product::whereNull('id')->paginate($perPage);
		}

		// Transform each product with URLs
		$products->getCollection()->transform(function ($product) {
			return $this->withUrls($product);
		});

		return response()->json($products);

	}

	// store product (single image)
	public function store(StoreProductRequest $request)
	{
		$file = $request->file('image');
		$paths = $this->processAndStoreImage($file);

		$product = Product::create([
			'name' => $request->name,
			'desc' => $request->desc,
			'image' => $paths['image'],
			'thumb' => $paths['thumb'],
			'status' => $request->boolean('status', true),
			'admin_id' => $request->user()->id, //current logged in admin
		]);

		return response()->json([
			'message' => 'Product created',
			'product' => $this->withUrls($product),
		], 201);
	}

	// show one product
	public function show(Product $product)
	{
		return response()->json($this->withUrls($product));
	}

	// update product (optional new image)
	public function update(UpdateProductRequest $request, Product $product)
	{
		$data = $request->only(['name', 'desc']);
		if ($request->has('status'))
			$data['status'] = $request->boolean('status');

		if ($request->hasFile('image')) {
			$this->deleteImages($product); // remove old
			$paths = $this->processAndStoreImage($request->file('image'));
			$data['image'] = $paths['image'];
			$data['thumb'] = $paths['thumb'];
		}

		$product->update($data);

		return response()->json([
			'message' => 'Product updated',
			'product' => $this->withUrls($product),
		]);
	}

	// delete product
	public function destroy(Product $product)
	{
		$this->deleteImages($product);
		$product->delete();

		return response()->json(['message' => 'Product deleted']);
	}

	// --------------- helpers ----------------
	private function processAndStoreImage($file): array
	{
		// create directories
		Storage::disk('public')->makeDirectory('products');
		Storage::disk('public')->makeDirectory('products/thumbs');

		$baseName = uniqid('prod_');
		$imageRel = "products/{$baseName}.webp";
		$thumbRel = "products/thumbs/{$baseName}.webp";

		$imageFull = storage_path("app/public/{$imageRel}");
		$thumbFull = storage_path("app/public/{$thumbRel}");

		// main image: max width 1080, encode webp quality 80
		Image::useImageDriver(ImageDriver::Gd)
			->loadFile($file->getPathname())
			->format('webp')
			->quality(80)
			->fit(Fit::Contain, 1080, null) // keep aspect ratio, max width 1080
			->save($imageFull);

		// thumbnail: width 360, quality 70
		Image::useImageDriver(ImageDriver::Gd)
			->loadFile($file->getPathname())
			->format('webp')
			->quality(70)
			->fit(Fit::Contain, 360, null)
			->save($thumbFull);

		return ['image' => $imageRel, 'thumb' => $thumbRel];
	}

	private function deleteImages(Product $product): void
	{
		if ($product->image)
			Storage::disk('public')->delete($product->image);
		if ($product->thumb)
			Storage::disk('public')->delete($product->thumb);
	}

	private function withUrls(Product $p)
	{
		$p->image_url = $p->image ? asset("storage/{$p->image}") : null;
		$p->thumb_url = $p->thumb ? asset("storage/{$p->thumb}") : null;
		return $p;
	}
}
