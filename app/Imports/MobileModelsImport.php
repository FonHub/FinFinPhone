<?php

namespace App\Imports;

use App\Models\GradeMaster;
use App\Models\MobileBrand;
use App\Models\MobileModel;
use App\Models\MobileModelPrice;
use App\Models\MobileModelPriceGrade;
use App\Models\MobileProductCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Throwable;

class MobileModelsImport implements ToCollection, WithHeadingRow
{
    protected ?int $mobileBrandId;

    protected ?int $mobileProductCategoryId;

    public function __construct(
        ?int $mobileBrandId = null,
        ?int $mobileProductCategoryId = null
    ) {
        $this->mobileBrandId = $mobileBrandId;
        $this->mobileProductCategoryId =
            $mobileProductCategoryId;
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new \Exception(
                'ไม่พบข้อมูลในไฟล์ที่อัปโหลด'
            );
        }

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | โหลดข้อมูลเกรด
            |--------------------------------------------------------------------------
            */

            $gradeMasters = GradeMaster::query()
                ->where('status', 1)
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            if ($gradeMasters->isEmpty()) {
                throw new \Exception(
                    'ไม่พบข้อมูลเกรดในตาราง grade_masters'
                );
            }

            $gradeMap = [];

            foreach ($gradeMasters as $gradeMaster) {
                $gradeCode = strtoupper(
                    trim(
                        (string) $gradeMaster->grade_code
                    )
                );

                if ($gradeCode !== '') {
                    $gradeMap[$gradeCode] =
                        $gradeMaster;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | ตรวจสอบหัวคอลัมน์
            |--------------------------------------------------------------------------
            */

            $requiredBaseColumns = [
                'brand_name',
                'category_name',
                'model_name',
                'sort_order',
                'capacity',
                'base_price',
                'min_price',
            ];

            $firstRow = $rows->first();

            $availableColumns = array_keys(
                $firstRow->toArray()
            );

            foreach ($requiredBaseColumns as $column) {
                if (
                    !in_array(
                        $column,
                        $availableColumns,
                        true
                    )
                ) {
                    throw new \Exception(
                        "ไม่พบคอลัมน์ {$column} ในไฟล์ที่อัปโหลด"
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | โหลดแบรนด์และประเภทที่เลือกจากหน้า
            |--------------------------------------------------------------------------
            */

            $selectedBrand = null;

            if (!empty($this->mobileBrandId)) {
                $selectedBrand = MobileBrand::query()
                    ->find($this->mobileBrandId);

                if (!$selectedBrand) {
                    throw new \Exception(
                        'ไม่พบแบรนด์สินค้าที่เลือก'
                    );
                }
            }

            $selectedCategory = null;

            if (
                !empty($this->mobileProductCategoryId)
            ) {
                $selectedCategory =
                    MobileProductCategory::query()
                    ->find(
                        $this->mobileProductCategoryId
                    );

                if (!$selectedCategory) {
                    throw new \Exception(
                        'ไม่พบประเภทสินค้าที่เลือก'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | ตัวแปรตรวจข้อมูลซ้ำ
            |--------------------------------------------------------------------------
            */

            $duplicatePriceCheck = [];

            /*
            |--------------------------------------------------------------------------
            | เก็บโมเดลที่ประมวลผลแล้ว
            |--------------------------------------------------------------------------
            |
            | โมเดลเดียวอาจมีหลายแถวจากหลายความจุ
            | จึงต้องจัดลำดับและบันทึกราคาหักเกรดเพียงครั้งเดียว
            |
            */

            $processedModels = [];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                /*
                |--------------------------------------------------------------------------
                | อ่านค่าจาก Excel
                |--------------------------------------------------------------------------
                */

                $brandName = trim(
                    (string) (
                        $row['brand_name'] ?? ''
                    )
                );

                $categoryName = trim(
                    (string) (
                        $row['category_name'] ?? ''
                    )
                );

                $modelName = trim(
                    (string) (
                        $row['model_name'] ?? ''
                    )
                );

                $capacity = trim(
                    (string) (
                        $row['capacity'] ?? ''
                    )
                );

                /*
                |--------------------------------------------------------------------------
                | ตรวจสอบข้อมูลพื้นฐาน
                |--------------------------------------------------------------------------
                */

                if ($brandName === '') {
                    throw new \Exception(
                        "แถวที่ {$rowNumber}: กรุณากรอก brand_name"
                    );
                }

                if ($categoryName === '') {
                    throw new \Exception(
                        "แถวที่ {$rowNumber}: กรุณากรอก category_name"
                    );
                }

                if ($modelName === '') {
                    throw new \Exception(
                        "แถวที่ {$rowNumber}: กรุณากรอก model_name"
                    );
                }

                if ($capacity === '') {
                    throw new \Exception(
                        "แถวที่ {$rowNumber}: กรุณากรอก capacity"
                    );
                }

                $sortOrder = $this->normalizeSortOrder(
                    $row['sort_order'] ?? null,
                    $rowNumber
                );

                /*
                |--------------------------------------------------------------------------
                | หาแบรนด์
                |--------------------------------------------------------------------------
                */

                if ($selectedBrand) {
                    if (
                        mb_strtolower($brandName)
                        !== mb_strtolower(
                            trim(
                                (string) $selectedBrand->name
                            )
                        )
                    ) {
                        throw new \Exception(
                            "แถวที่ {$rowNumber}: brand_name ในไฟล์ ({$brandName}) "
                                . "ไม่ตรงกับแบรนด์ที่เลือก ({$selectedBrand->name})"
                        );
                    }

                    $brand = $selectedBrand;
                } else {
                    $brand = MobileBrand::query()
                        ->whereRaw(
                            'LOWER(name) = ?',
                            [
                                mb_strtolower(
                                    $brandName
                                ),
                            ]
                        )
                        ->first();

                    if (!$brand) {
                        $brand = MobileBrand::query()
                            ->create([
                                'name' => $brandName,
                                'status' => 1,
                            ]);
                    } elseif (
                        (int) $brand->status !== 1
                    ) {
                        $brand->update([
                            'status' => 1,
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | หาประเภทสินค้า
                |--------------------------------------------------------------------------
                |
                | ประเภทสินค้าไม่ได้ผูกกับแบรนด์
                |
                */

                if ($selectedCategory) {
                    if (
                        mb_strtolower($categoryName)
                        !== mb_strtolower(
                            trim(
                                (string)
                                $selectedCategory
                                    ->category_name
                            )
                        )
                    ) {
                        throw new \Exception(
                            "แถวที่ {$rowNumber}: category_name ในไฟล์ ({$categoryName}) "
                                . "ไม่ตรงกับประเภทสินค้าที่เลือก "
                                . "({$selectedCategory->category_name})"
                        );
                    }

                    $category = $selectedCategory;
                } else {
                    $category =
                        MobileProductCategory::query()
                        ->whereRaw(
                            'LOWER(category_name) = ?',
                            [
                                mb_strtolower(
                                    $categoryName
                                ),
                            ]
                        )
                        ->first();

                    if (!$category) {
                        $category =
                            MobileProductCategory::query()
                            ->create([
                                'category_name' =>
                                $categoryName,

                                'icon' => null,

                                'sort_order' => 0,

                                'status' => 1,
                            ]);
                    } elseif (
                        (int) $category->status !== 1
                    ) {
                        $category->update([
                            'status' => 1,
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Key ของโมเดล
                |--------------------------------------------------------------------------
                */

                $modelKey = implode('|', [
                    (int) $brand->id,
                    (int) $category->id,
                    mb_strtolower($modelName),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Key ของราคาแต่ละความจุ
                |--------------------------------------------------------------------------
                */

                $priceDuplicateKey = implode('|', [
                    $modelKey,
                    mb_strtolower($capacity),
                ]);

                if (
                    isset(
                        $duplicatePriceCheck[$priceDuplicateKey]
                    )
                ) {
                    throw new \Exception(
                        "แถวที่ {$rowNumber}: พบข้อมูลซ้ำในไฟล์ "
                            . "({$brandName} / "
                            . "{$category->category_name} / "
                            . "{$modelName} / {$capacity})"
                    );
                }

                $duplicatePriceCheck[$priceDuplicateKey] = true;

                /*
                |--------------------------------------------------------------------------
                | ตรวจสอบสถานะและราคา
                |--------------------------------------------------------------------------
                */

                $modelStatus =
                    $this->normalizeStatus(
                        $row['model_status'] ?? 1,
                        $rowNumber,
                        'model_status'
                    );

                $priceStatus =
                    $this->normalizeStatus(
                        $row['price_status'] ?? 1,
                        $rowNumber,
                        'price_status'
                    );

                $basePrice =
                    $this->normalizeNumber(
                        $row['base_price'] ?? 0,
                        $rowNumber,
                        'base_price'
                    );

                $minPrice =
                    $this->normalizeNumber(
                        $row['min_price'] ?? 0,
                        $rowNumber,
                        'min_price'
                    );

                /*
                |--------------------------------------------------------------------------
                | สร้างหรืออัปเดตโมเดล
                |--------------------------------------------------------------------------
                */

                if (
                    isset($processedModels[$modelKey])
                ) {
                    $model =
                        $processedModels[$modelKey];

                    /*
                    |--------------------------------------------------------------------------
                    | ทุกแถวของโมเดลเดียวกันต้องใช้ sort_order เดียวกัน
                    |--------------------------------------------------------------------------
                    */

                    if (
                        (int) $model->sort_order
                        !== $sortOrder
                    ) {
                        throw new \Exception(
                            "แถวที่ {$rowNumber}: โมเดล {$modelName} "
                                . 'มี sort_order ไม่ตรงกันในแต่ละความจุ'
                        );
                    }
                } else {
                    $model = MobileModel::query()
                        ->where(
                            'mobile_brand_id',
                            $brand->id
                        )
                        ->where(
                            'mobile_product_category_id',
                            $category->id
                        )
                        ->where('name', $modelName)
                        ->first();

                    if ($model) {
                        /*
                        |--------------------------------------------------------------------------
                        | โมเดลเดิม เปลี่ยนตำแหน่งลำดับ
                        |--------------------------------------------------------------------------
                        */

                        $oldBrandId = (int)
                        $model->mobile_brand_id;

                        $oldCategoryId = (int)
                        $model
                            ->mobile_product_category_id;

                        $oldSortOrder = max(
                            1,
                            (int) $model->sort_order
                        );

                        $this->moveSortPosition(
                            modelId: (int) $model->id,
                            oldBrandId: $oldBrandId,
                            oldCategoryId: $oldCategoryId,
                            oldSortOrder: $oldSortOrder,
                            newBrandId: (int) $brand->id,
                            newCategoryId: (int) $category->id,
                            newSortOrder: $sortOrder
                        );

                        $model->update([
                            'mobile_brand_id' =>
                            $brand->id,

                            'mobile_product_category_id' =>
                            $category->id,

                            'name' =>
                            $modelName,

                            'sort_order' =>
                            $sortOrder,

                            'status' =>
                            $modelStatus,
                        ]);
                    } else {
                        /*
                        |--------------------------------------------------------------------------
                        | โมเดลใหม่ เปิดตำแหน่งลำดับก่อน
                        |--------------------------------------------------------------------------
                        */

                        $this->openSortPosition(
                            brandId: (int) $brand->id,

                            categoryId: (int) $category->id,

                            sortOrder: $sortOrder
                        );

                        $model =
                            MobileModel::query()
                            ->create([
                                'mobile_brand_id' =>
                                $brand->id,

                                'mobile_product_category_id' =>
                                $category->id,

                                'name' =>
                                $modelName,

                                'sort_order' =>
                                $sortOrder,

                                'status' =>
                                $modelStatus,
                            ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | บันทึกราคาหักตามเกรดที่ระดับโมเดล
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $gradeMap
                        as $gradeCode => $gradeMaster
                    ) {
                        $rawDeductPrice =
                            $this->getRowValueByGradeCode(
                                $row,
                                $gradeCode
                            );

                        $deductPrice =
                            $this->normalizeNumber(
                                $rawDeductPrice ?? 0,
                                $rowNumber,
                                $gradeCode
                            );

                        MobileModelPriceGrade::query()
                            ->updateOrCreate(
                                [
                                    'mobile_model_id' =>
                                    $model->id,

                                    'grade_master_id' =>
                                    $gradeMaster->id,
                                ],
                                [
                                    'deduct_price' =>
                                    $deductPrice,
                                ]
                            );
                    }

                    $processedModels[$modelKey] =
                        $model;
                }

                /*
                |--------------------------------------------------------------------------
                | สร้างหรืออัปเดตราคาตามความจุ
                |--------------------------------------------------------------------------
                */

                MobileModelPrice::query()
                    ->updateOrCreate(
                        [
                            'mobile_model_id' =>
                            $model->id,

                            'capacity' =>
                            $capacity,
                        ],
                        [
                            'base_price' =>
                            $basePrice,

                            'min_price' =>
                            $minPrice,

                            'status' =>
                            $priceStatus,
                        ]
                    );
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | เปิดตำแหน่งลำดับสำหรับโมเดลใหม่
    |--------------------------------------------------------------------------
    */

    private function openSortPosition(
        int $brandId,
        int $categoryId,
        int $sortOrder,
        ?int $ignoreModelId = null
    ): void {
        MobileModel::query()
            ->where(
                'mobile_brand_id',
                $brandId
            )
            ->where(
                'mobile_product_category_id',
                $categoryId
            )
            ->when(
                $ignoreModelId,
                function ($query) use (
                    $ignoreModelId
                ) {
                    $query->where(
                        'id',
                        '!=',
                        $ignoreModelId
                    );
                }
            )
            ->where(
                'sort_order',
                '>=',
                $sortOrder
            )
            ->increment('sort_order');
    }

    /*
    |--------------------------------------------------------------------------
    | ย้ายตำแหน่งลำดับของโมเดลเดิม
    |--------------------------------------------------------------------------
    */

    private function moveSortPosition(
        int $modelId,
        int $oldBrandId,
        int $oldCategoryId,
        int $oldSortOrder,
        int $newBrandId,
        int $newCategoryId,
        int $newSortOrder
    ): void {
        $sameGroup =
            $oldBrandId === $newBrandId
            && $oldCategoryId === $newCategoryId;

        if ($sameGroup) {
            if ($newSortOrder === $oldSortOrder) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | ย้ายขึ้น เช่น 5 ไป 2
            |--------------------------------------------------------------------------
            */

            if ($newSortOrder < $oldSortOrder) {
                MobileModel::query()
                    ->where(
                        'mobile_brand_id',
                        $newBrandId
                    )
                    ->where(
                        'mobile_product_category_id',
                        $newCategoryId
                    )
                    ->where(
                        'id',
                        '!=',
                        $modelId
                    )
                    ->whereBetween(
                        'sort_order',
                        [
                            $newSortOrder,
                            $oldSortOrder - 1,
                        ]
                    )
                    ->increment('sort_order');

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | ย้ายลง เช่น 2 ไป 5
            |--------------------------------------------------------------------------
            */

            MobileModel::query()
                ->where(
                    'mobile_brand_id',
                    $newBrandId
                )
                ->where(
                    'mobile_product_category_id',
                    $newCategoryId
                )
                ->where(
                    'id',
                    '!=',
                    $modelId
                )
                ->whereBetween(
                    'sort_order',
                    [
                        $oldSortOrder + 1,
                        $newSortOrder,
                    ]
                )
                ->decrement('sort_order');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | ปิดช่องว่างกลุ่มเดิม
        |--------------------------------------------------------------------------
        */

        MobileModel::query()
            ->where(
                'mobile_brand_id',
                $oldBrandId
            )
            ->where(
                'mobile_product_category_id',
                $oldCategoryId
            )
            ->where(
                'id',
                '!=',
                $modelId
            )
            ->where(
                'sort_order',
                '>',
                $oldSortOrder
            )
            ->decrement('sort_order');

        /*
        |--------------------------------------------------------------------------
        | เปิดตำแหน่งกลุ่มใหม่
        |--------------------------------------------------------------------------
        */

        $this->openSortPosition(
            brandId: $newBrandId,
            categoryId: $newCategoryId,
            sortOrder: $newSortOrder,
            ignoreModelId: $modelId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | อ่านราคาหักจากคอลัมน์เกรด
    |--------------------------------------------------------------------------
    */

    private function getRowValueByGradeCode(
        $row,
        string $gradeCode
    ) {
        $rowArray = $row->toArray();

        foreach ($rowArray as $key => $value) {
            if (
                strtoupper(
                    trim((string) $key)
                )
                === strtoupper(
                    trim($gradeCode)
                )
            ) {
                return $value;
            }
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | ตรวจสอบสถานะ
    |--------------------------------------------------------------------------
    */

    private function normalizeStatus(
        $value,
        int $rowNumber,
        string $field
    ): int {
        if ($value === '' || $value === null) {
            return 1;
        }

        if (
            !in_array(
                (string) $value,
                [
                    '0',
                    '1',
                ],
                true
            )
        ) {
            throw new \Exception(
                "แถวที่ {$rowNumber}: {$field} ต้องเป็น 0 หรือ 1"
            );
        }

        return (int) $value;
    }

    /*
    |--------------------------------------------------------------------------
    | ตรวจสอบตัวเลข
    |--------------------------------------------------------------------------
    */

    private function normalizeNumber(
        $value,
        int $rowNumber,
        string $field
    ): float {
        if ($value === '' || $value === null) {
            return 0;
        }

        if (!is_numeric($value)) {
            throw new \Exception(
                "แถวที่ {$rowNumber}: {$field} ต้องเป็นตัวเลข"
            );
        }

        return (float) $value;
    }

    /*
    |--------------------------------------------------------------------------
    | ตรวจสอบลำดับการแสดงผล
    |--------------------------------------------------------------------------
    */

    private function normalizeSortOrder(
        $value,
        int $rowNumber
    ): int {
        if ($value === '' || $value === null) {
            throw new \Exception(
                "แถวที่ {$rowNumber}: กรุณากรอก sort_order"
            );
        }

        if (
            filter_var(
                $value,
                FILTER_VALIDATE_INT
            ) === false
        ) {
            throw new \Exception(
                "แถวที่ {$rowNumber}: sort_order ต้องเป็นจำนวนเต็ม"
            );
        }

        $sortOrder = (int) $value;

        if ($sortOrder < 1) {
            throw new \Exception(
                "แถวที่ {$rowNumber}: sort_order ต้องเริ่มตั้งแต่ 1"
            );
        }

        return $sortOrder;
    }
}
