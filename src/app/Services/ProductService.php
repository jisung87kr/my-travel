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

class ProductService
{
    /**
     * 상품 생성
     * @param array $data 상품 데이터
     * @param User|null $vendor Vendor 사용자 (Admin은 null, data['vendor_id'] 사용)
     * @param array $images 업로드 이미지 파일 배열
     */
    public function create(array $data, ?User $vendor = null, array $images = []): Product
    {
        return DB::transaction(function () use ($data, $vendor, $images) {
            // Vendor: draft/pending만 허용, Admin: 모든 status 허용
            $status = $vendor
                ? ($data['status'] === 'pending' ? ProductStatus::PENDING : ProductStatus::DRAFT)
                : ($data['status'] ?? ProductStatus::DRAFT);

            $product = new Product([
                'vendor_id' => $vendor?->vendor->id ?? $data['vendor_id'],
                'type' => $data['type'],
                'region_id' => $data['region_id'] ?? null,
                'duration' => $data['duration'] ?? null,
                'min_persons' => $data['min_persons'] ?? 1,
                'max_persons' => $data['max_persons'] ?? 100,
                'booking_type' => $data['booking_type'],
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'status' => $status,
            ]);
            $product->slug_source = $data['translations']['ko']['title'] ?? 'product';
            $product->save();

            if (!empty($data['translations'])) {
                $this->syncTranslations($product, $data['translations']);
            }

            if (!empty($data['prices'])) {
                $this->syncPrices($product, $data['prices']);
            }

            if (!empty($images)) {
                $this->uploadImagesWithUrl($product, $images, true);
            }

            if (!empty($data['schedules'])) {
                $this->syncSchedules($product, $data['schedules']);
            }

            return $product->load(['translations', 'prices', 'vendor', 'images', 'schedules']);
        });
    }

    /**
     * 상품 수정
     * @param Product $product 수정할 상품
     * @param array $data 상품 데이터
     * @param array $images 업로드 이미지 파일 배열
     * @param array $deleteImageIds 삭제할 이미지 ID 배열
     */
    public function update(Product $product, array $data, array $images = [], array $deleteImageIds = []): Product
    {
        return DB::transaction(function () use ($product, $data, $images, $deleteImageIds) {
            $updateData = array_filter([
                'vendor_id' => $data['vendor_id'] ?? null,
                'type' => $data['type'] ?? null,
                'region_id' => $data['region_id'] ?? null,
                'duration' => $data['duration'] ?? null,
                'min_persons' => $data['min_persons'] ?? null,
                'max_persons' => $data['max_persons'] ?? null,
                'booking_type' => $data['booking_type'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
            ], fn ($value) => $value !== null);

            if (isset($data['status'])) {
                $updateData['status'] = $data['status'];
            }

            $product->update($updateData);

            if (!empty($data['translations'])) {
                $this->syncTranslations($product, $data['translations']);
            }

            if (isset($data['prices'])) {
                $this->syncPrices($product, $data['prices']);
            }

            if (!empty($deleteImageIds)) {
                $this->deleteImagesByIds($product, $deleteImageIds);
            }

            if (!empty($images)) {
                $this->uploadImagesWithUrl($product, $images, false);
            }

            if (!empty($data['delete_schedules'])) {
                $this->deleteSchedulesByIds($product, $data['delete_schedules']);
            }

            if (!empty($data['schedules'])) {
                $this->syncSchedules($product, $data['schedules']);
            }

            return $product->fresh(['translations', 'prices', 'vendor', 'images', 'schedules']);
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
                $q->where('title', 'like', "%{$keyword}%")
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

    /**
     * 번역 동기화 (영어 빈 값 삭제 포함)
     */
    private function syncTranslations(Product $product, array $translations): void
    {
        foreach ($translations as $locale => $translation) {
            $hasContent = $this->hasTranslationContent($translation);

            if ($locale === 'ko' && !empty($translation['title'])) {
                $product->translations()->updateOrCreate(
                    ['locale' => $locale],
                    $this->buildTranslationData($translation, true)
                );
            } elseif ($locale !== 'ko' && $hasContent) {
                $product->translations()->updateOrCreate(
                    ['locale' => $locale],
                    $this->buildTranslationData($translation, false)
                );
            } elseif ($locale !== 'ko' && !$hasContent) {
                $product->translations()->where('locale', $locale)->delete();
            }
        }
    }

    /**
     * 번역 컨텐츠 존재 여부 확인
     */
    private function hasTranslationContent(array $translation): bool
    {
        return !empty($translation['title'])
            || !empty($translation['description'])
            || !empty($translation['short_description'])
            || !empty($translation['includes'])
            || !empty($translation['excludes'])
            || !empty($translation['notes'])
            || !empty($translation['meeting_point'])
            || !empty($translation['meeting_point_detail']);
    }

    /**
     * 번역 데이터 배열 생성
     */
    private function buildTranslationData(array $translation, bool $isKorean): array
    {
        return [
            'title' => $translation['title'] ?? ($isKorean ? '' : null),
            'short_description' => $translation['short_description'] ?? null,
            'description' => $translation['description'] ?? ($isKorean ? '' : null),
            'includes' => $translation['includes'] ?? null,
            'excludes' => $translation['excludes'] ?? null,
            'notes' => $translation['notes'] ?? null,
            'meeting_point' => $translation['meeting_point'] ?? null,
            'meeting_point_detail' => $translation['meeting_point_detail'] ?? null,
        ];
    }

    /**
     * 가격 동기화 (아동 가격 빈 값 삭제 포함)
     */
    private function syncPrices(Product $product, array $prices): void
    {
        if (!empty($prices['adult'])) {
            $product->prices()->updateOrCreate(
                ['type' => 'adult'],
                ['label' => '성인', 'price' => $prices['adult']]
            );
        }

        if (!empty($prices['child'])) {
            $product->prices()->updateOrCreate(
                ['type' => 'child'],
                ['label' => '아동', 'price' => $prices['child']]
            );
        } else {
            $product->prices()->where('type', 'child')->delete();
        }
    }

    /**
     * 이미지 업로드 (URL 포함)
     */
    private function uploadImagesWithUrl(Product $product, array $files, bool $isNew): void
    {
        $sortOrder = $isNew ? 0 : ($product->images()->max('sort_order') ?? -1) + 1;
        $hasExistingImages = !$isNew && $product->images()->count() > 0;

        foreach ($files as $file) {
            $path = $file->store('products/' . $product->id, 'public');
            $product->images()->create([
                'url' => Storage::url($path),
                'path' => $path,
                'sort_order' => $sortOrder++,
                'is_primary' => $isNew ? ($sortOrder === 1) : !$hasExistingImages,
            ]);
            $hasExistingImages = true;
        }
    }

    /**
     * 이미지 ID로 삭제
     */
    private function deleteImagesByIds(Product $product, array $imageIds): void
    {
        $images = $product->images()->whereIn('id', $imageIds)->get();

        foreach ($images as $image) {
            if ($image->path) {
                Storage::disk('public')->delete($image->path);
            }
            $image->delete();
        }
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

    /**
     * 스케줄 동기화
     */
    private function syncSchedules(Product $product, array $schedules): void
    {
        foreach ($schedules as $schedule) {
            if (empty($schedule['date']) || empty($schedule['start_time']) || empty($schedule['total_count'])) {
                continue;
            }

            $existing = $product->schedules()->where('date', $schedule['date'])->first();

            if ($existing) {
                // 기존 스케줄이 있으면 업데이트 (예약이 없는 경우에만 정원 변경)
                $updateData = [
                    'start_time' => $schedule['start_time'],
                    'is_active' => isset($schedule['is_active']) ? (bool) $schedule['is_active'] : true,
                ];

                // 예약이 없으면 정원도 업데이트
                if ($existing->bookings()->count() === 0) {
                    $updateData['total_count'] = $schedule['total_count'];
                    $updateData['available_count'] = $schedule['total_count'];
                }

                $existing->update($updateData);
            } else {
                // 새 스케줄 생성
                $product->schedules()->create([
                    'date' => $schedule['date'],
                    'start_time' => $schedule['start_time'],
                    'total_count' => $schedule['total_count'],
                    'available_count' => $schedule['total_count'],
                    'is_active' => isset($schedule['is_active']) ? (bool) $schedule['is_active'] : true,
                ]);
            }
        }
    }

    /**
     * 스케줄 ID로 삭제
     */
    private function deleteSchedulesByIds(Product $product, array $scheduleIds): void
    {
        $product->schedules()->whereIn('id', $scheduleIds)->delete();
    }
}
