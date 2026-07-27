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

    public function __construct(?int $mobileBrandId = null, ?int $mobileProductCategoryId = null)
    {
        $this->mobileBrandId = $mobileBrandId;
        $this->mobileProductCategoryId = $mobileProductCategoryId;
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new \Exception('ไม่พบข้อมูลในไฟล์ที่อัปโหลด');
        }

        DB::beginTransaction();

        try {
            $duplicateCheck = [];

            $gradeMasters = GradeMaster::query()
                ->where('status', 1)
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            if ($gradeMasters->isEmpty()) {
                throw new \Exception('ไม่พบข้อมูลเกรดในตาราง grade_masters');
            }

            $gradeMap = [];
            foreach ($gradeMasters as $gradeMaster) {
                $gradeCode = strtoupper(trim((string) $gradeMaster->grade_code));
                $gradeMap[$gradeCode] = $gradeMaster;
            }

            $requiredBaseColumns = [
                'brand_name',
                'model_name',
                'capacity',
                'base_price',
                'min_price',
            ];

            $firstRow = $rows->first();
            $availableColumns = array_keys($firstRow->toArray());

            foreach ($requiredBaseColumns as $column) {
                if (!in_array($column, $availableColumns, true)) {
                    throw new \Exception("ไม่พบคอลัมน์ {$column} ในไฟล์ที่อัปโหลด");
                }
            }

            $hasCategoryNameColumn = in_array('category_name', $availableColumns, true);

            $selectedBrand = null;
            if (!empty($this->mobileBrandId)) {
                $selectedBrand = MobileBrand::query()->find($this->mobileBrandId);

                if (!$selectedBrand) {
                    throw new \Exception('ไม่พบแบรนด์สินค้าที่เลือก');
                }
            }

            $selectedCategory = null;
            if (!empty($this->mobileProductCategoryId)) {
                $selectedCategory = MobileProductCategory::query()->find($this->mobileProductCategoryId);

                if (!$selectedCategory) {
                    throw new \Exception('ไม่พบประเภทสินค้าที่เลือก');
                }
            }

            if (!$selectedCategory && !$hasCategoryNameColumn) {
                throw new \Exception('ไม่พบคอลัมน์ category_name ในไฟล์ที่อัปโหลด');
            }

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                $brandName = trim((string) ($row['brand_name'] ?? ''));
                $categoryName = trim((string) ($row['category_name'] ?? ''));
                $modelName = trim((string) ($row['model_name'] ?? ''));
                $capacity = trim((string) ($row['capacity'] ?? ''));

                if ($brandName === '') {
                    throw new \Exception("แถวที่ {$rowNumber}: กรุณากรอก brand_name");
                }

                if (!$selectedCategory && $categoryName === '') {
                    throw new \Exception("แถวที่ {$rowNumber}: กรุณากรอก category_name");
                }

                if ($modelName === '') {
                    throw new \Exception("แถวที่ {$rowNumber}: กรุณากรอก model_name");
                }

                if ($capacity === '') {
                    throw new \Exception("แถวที่ {$rowNumber}: กรุณากรอก capacity");
                }

                if ($selectedBrand) {
                    if (mb_strtolower($brandName) !== mb_strtolower(trim((string) $selectedBrand->name))) {
                        throw new \Exception("แถวที่ {$rowNumber}: brand_name ในไฟล์ ({$brandName}) ไม่ตรงกับแบรนด์ที่เลือก ({$selectedBrand->name})");
                    }

                    $brand = $selectedBrand;
                } else {
                    $brand = MobileBrand::query()->firstOrCreate(
                        [
                            'name' => $brandName,
                        ],
                        [
                            'status' => 1,
                        ]
                    );

                    if ((int) $brand->status !== 1) {
                        $brand->update([
                            'status' => 1,
                        ]);
                    }
                }

                if ($selectedCategory) {
                    if ($hasCategoryNameColumn && $categoryName !== '') {
                        if (mb_strtolower($categoryName) !== mb_strtolower(trim((string) $selectedCategory->category_name))) {
                            throw new \Exception("แถวที่ {$rowNumber}: category_name ในไฟล์ ({$categoryName}) ไม่ตรงกับประเภทสินค้าที่เลือก ({$selectedCategory->category_name})");
                        }
                    }

                    $category = $selectedCategory;
                } else {
                    $category = MobileProductCategory::query()->firstOrCreate(
                        [
                            'category_name' => $categoryName,
                        ],
                        [
                            'icon' => null,
                            'sort_order' => 0,
                            'status' => 1,
                        ]
                    );

                    if ((int) $category->status !== 1) {
                        $category->update([
                            'status' => 1,
                        ]);
                    }
                }

                $dupKey = mb_strtolower($brand->id . '|' . $category->id . '|' . $modelName . '|' . $capacity);

                if (isset($duplicateCheck[$dupKey])) {
                    throw new \Exception("แถวที่ {$rowNumber}: พบข้อมูลซ้ำในไฟล์ ({$brandName} / {$category->category_name} / {$modelName} / {$capacity})");
                }

                $duplicateCheck[$dupKey] = true;

                $modelStatus = $this->normalizeStatus($row['model_status'] ?? 1, $rowNumber, 'model_status');
                $priceStatus = $this->normalizeStatus($row['price_status'] ?? 1, $rowNumber, 'price_status');

                $basePrice = $this->normalizeNumber($row['base_price'] ?? 0, $rowNumber, 'base_price');
                $minPrice = $this->normalizeNumber($row['min_price'] ?? 0, $rowNumber, 'min_price');

                $model = MobileModel::query()->updateOrCreate(
                    [
                        'mobile_brand_id' => $brand->id,
                        'mobile_product_category_id' => $category->id,
                        'name' => $modelName,
                    ],
                    [
                        'status' => $modelStatus,
                    ]
                );

                $price = MobileModelPrice::query()->updateOrCreate(
                    [
                        'mobile_model_id' => $model->id,
                        'capacity' => $capacity,
                    ],
                    [
                        'base_price' => $basePrice,
                        'min_price' => $minPrice,
                        'status' => $priceStatus,
                    ]
                );

                foreach ($gradeMap as $gradeCode => $gradeMaster) {
                    $rawDeductPrice = $this->getRowValueByGradeCode($row, $gradeCode);
                    $deductPrice = $this->normalizeNumber($rawDeductPrice ?? 0, $rowNumber, $gradeCode);

                    MobileModelPriceGrade::query()->updateOrCreate(
                        [
                            'mobile_model_price_id' => $price->id,
                            'grade_master_id' => $gradeMaster->id,
                        ],
                        [
                            'deduct_price' => $deductPrice,
                        ]
                    );
                }
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function getRowValueByGradeCode($row, string $gradeCode)
    {
        $rowArray = $row->toArray();

        foreach ($rowArray as $key => $value) {
            if (strtoupper(trim((string) $key)) === strtoupper(trim($gradeCode))) {
                return $value;
            }
        }

        return null;
    }

    private function normalizeStatus($value, int $rowNumber, string $field): int
    {
        if ($value === '' || $value === null) {
            return 1;
        }

        if (!in_array((string) $value, ['0', '1'], true)) {
            throw new \Exception("แถวที่ {$rowNumber}: {$field} ต้องเป็น 0 หรือ 1");
        }

        return (int) $value;
    }

    private function normalizeNumber($value, int $rowNumber, string $field): float
    {
        if ($value === '' || $value === null) {
            return 0;
        }

        if (!is_numeric($value)) {
            throw new \Exception("แถวที่ {$rowNumber}: {$field} ต้องเป็นตัวเลข");
        }

        return (float) $value;
    }
}
