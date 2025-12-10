<?php

namespace App\Services;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function create(array $data, User $vendor): Product
    {
        return DB::transaction(function () use ($data, $vendor) {
            // Determine status from data or default to DRAFT
            $status = ProductStatus::DRAFT;
            if (!empty($data['status'])) {
                $status = $data['status'] === 'pending' ? ProductStatus::PENDING : ProductStatus::DRAFT;
            }
            // Generate unique slug from Korean title
            $koTitle = $data['translations']['ko']['title'] ?? 'product';
            $baseSlug = Str::slug($koTitle) ?: 'product-' . time();
            $slug = $baseSlug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $product = Product::create([
                'vendor_id' => $vendor->vendor->id,
                'slug' => $slug,
                'type' => $data['type'],
                'region' => $data['region'],
                'duration' => $data['duration'] ?? null,
                'min_persons' => $data['min_persons'] ?? 1,
                'max_persons' => $data['max_persons'] ?? 100,
                'booking_type' => $data['booking_type'],
                'meeting_point' => $data['meeting_point'] ?? null,
                'meeting_point_detail' => $data['meeting_point_detail'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'status' => $status,
            ]);

            // Handle translations - support both keyed (ko, en) and array format
            if (!empty($data['translations'])) {
                foreach ($data['translations'] as $locale => $translation) {
                    // Check if it's keyed by locale (ko, en) or has locale in data
                    if (is_string($locale) && is_array($translation)) {
                        // Keyed format: translations[ko][title]
                        $product->translations()->create([
                            'locale' => $locale,
                            'name' => $translation['title'] ?? null,
                            'short_description' => $translation['short_description'] ?? null,
                            'description' => $translation['description'] ?? null,
                            'included' => $translation['includes'] ?? null,
                            'excluded' => $translation['excludes'] ?? null,
                        ]);
                    } elseif (isset($translation['locale'])) {
                        // Array format: translations[0][locale]
                        $product->translations()->create($translation);
                    }
                }
            }

            // Handle prices - support both keyed (adult, child) and array format
            if (!empty($data['prices'])) {
                foreach ($data['prices'] as $type => $price) {
                    if (is_string($type) && in_array($type, ['adult', 'child', 'infant'])) {
                        // Keyed format: prices[adult] = 50000
                        if (!empty($price) || $price === 0 || $price === '0') {
                            $product->prices()->create([
                                'type' => $type,
                                'label' => $type === 'adult' ? '성인' : ($type === 'child' ? '아동' : '유아'),
                                'price' => (int) $price,
                                'is_active' => true,
                            ]);
                        }
                    } elseif (is_array($price) && isset($price['type'])) {
                        // Array format: prices[0][type]
                        $product->prices()->create($price);
                    }
                }
            }

            // Handle image uploads
            if (!empty($data['images'])) {
                $this->uploadImages($product, $data['images']);
            }

            return $product->load(['translations', 'prices', 'vendor', 'images']);
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $updateData = array_filter([
                'type' => $data['type'] ?? null,
                'region' => $data['region'] ?? null,
                'duration' => $data['duration'] ?? null,
                'min_persons' => $data['min_persons'] ?? null,
                'max_persons' => $data['max_persons'] ?? null,
                'booking_type' => $data['booking_type'] ?? null,
                'meeting_point' => $data['meeting_point'] ?? null,
                'meeting_point_detail' => $data['meeting_point_detail'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
            ], fn($value) => $value !== null);

            // Handle status separately
            if (!empty($data['status'])) {
                $updateData['status'] = $data['status'] === 'pending' ? ProductStatus::PENDING :
                    ($data['status'] === 'draft' ? ProductStatus::DRAFT : $data['status']);
            }

            $product->update($updateData);

            // Handle translations - support both keyed (ko, en) and array format
            if (!empty($data['translations'])) {
                foreach ($data['translations'] as $locale => $translation) {
                    if (is_string($locale) && is_array($translation)) {
                        // Keyed format: translations[ko][title]
                        $product->translations()->updateOrCreate(
                            ['locale' => $locale],
                            [
                                'name' => $translation['title'] ?? null,
                                'short_description' => $translation['short_description'] ?? null,
                                'description' => $translation['description'] ?? null,
                                'included' => $translation['includes'] ?? null,
                                'excluded' => $translation['excludes'] ?? null,
                            ]
                        );
                    } elseif (isset($translation['locale'])) {
                        // Array format: translations[0][locale]
                        $product->translations()->updateOrCreate(
                            ['locale' => $translation['locale']],
                            $translation
                        );
                    }
                }
            }

            // Handle prices - support both keyed (adult, child) and array format
            if (isset($data['prices'])) {
                foreach ($data['prices'] as $type => $price) {
                    if (is_string($type) && in_array($type, ['adult', 'child', 'infant'])) {
                        // Keyed format: prices[adult] = 50000
                        if (!empty($price) || $price === 0 || $price === '0') {
                            $product->prices()->updateOrCreate(
                                ['type' => $type],
                                [
                                    'label' => $type === 'adult' ? '성인' : ($type === 'child' ? '아동' : '유아'),
                                    'price' => (int) $price,
                                    'is_active' => true,
                                ]
                            );
                        }
                    } elseif (is_array($price) && isset($price['type'])) {
                        // Array format: prices[0][type]
                        $product->prices()->updateOrCreate(
                            ['type' => $price['type']],
                            $price
                        );
                    }
                }
            }

            // Handle image uploads
            if (!empty($data['images'])) {
                $this->uploadImages($product, $data['images']);
            }

            return $product->fresh(['translations', 'prices', 'vendor', 'images']);
        });
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function uploadImages(Product $product, array $files): array
    {
        $uploadedImages = [];
        $currentMaxOrder = $product->images()->max('sort_order') ?? 0;

        foreach ($files as $index => $file) {
            /** @var UploadedFile $file */
            $path = $this->storeImage($file, $product);

            $image = $product->images()->create([
                'path' => $path,
                'sort_order' => $currentMaxOrder + $index + 1,
            ]);

            $uploadedImages[] = $image;
        }

        return $uploadedImages;
    }

    private function storeImage(UploadedFile $file, Product $product): string
    {
        $filename = uniqid('product_') . '.' . $file->getClientOriginalExtension();
        $directory = "products/{$product->id}";

        return $file->storeAs($directory, $filename, 'public');
    }

    public function reorderImages(Product $product, array $order): void
    {
        DB::transaction(function () use ($product, $order) {
            foreach ($order as $index => $imageId) {
                $product->images()
                    ->where('id', $imageId)
                    ->update(['sort_order' => $index + 1]);
            }
        });
    }

    public function deleteImage(ProductImage $image): bool
    {
        Storage::disk('public')->delete($image->path);
        return $image->delete();
    }

    public function search(array $filters): LengthAwarePaginator
    {
        $query = Product::query()
            ->with(['translations', 'prices', 'images', 'vendor.user'])
            ->active();

        if (!empty($filters['region'])) {
            $query->byRegion($filters['region']);
        }

        if (!empty($filters['type'])) {
            $query->byType($filters['type']);
        }

        if (!empty($filters['date'])) {
            $query->whereHas('schedules', function ($q) use ($filters) {
                $q->where('date', $filters['date'])
                    ->where('available_count', '>', 0)
                    ->where('is_active', true);
            });
        }

        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->whereHas('translations', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if (!empty($filters['min_price'])) {
            $query->whereHas('prices', function ($q) use ($filters) {
                $q->where('type', 'adult')
                    ->where('price', '>=', $filters['min_price']);
            });
        }

        if (!empty($filters['max_price'])) {
            $query->whereHas('prices', function ($q) use ($filters) {
                $q->where('type', 'adult')
                    ->where('price', '<=', $filters['max_price']);
            });
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $perPage = min($filters['per_page'] ?? 20, 100);

        return $query->paginate($perPage);
    }

    public function getVendorProducts(User $vendor, array $filters = []): LengthAwarePaginator
    {
        $query = Product::query()
            ->with(['translations', 'prices', 'images'])
            ->where('vendor_id', $vendor->vendor->id);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->byType($filters['type']);
        }

        $query->orderBy('created_at', 'desc');

        $perPage = min($filters['per_page'] ?? 20, 100);

        return $query->paginate($perPage);
    }

    public function submitForReview(Product $product): Product
    {
        $product->update(['status' => ProductStatus::PENDING]);
        return $product;
    }

    public function activate(Product $product): Product
    {
        $product->update(['status' => ProductStatus::ACTIVE]);
        return $product;
    }

    public function deactivate(Product $product): Product
    {
        $product->update(['status' => ProductStatus::INACTIVE]);
        return $product;
    }
}
