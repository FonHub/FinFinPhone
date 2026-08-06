<?php

namespace App\Exports;

use App\Models\GradeMaster;
use App\Models\MobileBrand;
use App\Models\MobileModel;
use App\Models\MobileModelPrice;
use App\Models\MobileProductCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MobileModelsExport implements FromCollection, WithHeadings
{
    protected Collection $grades;

    protected ?MobileBrand $brand = null;

    protected ?MobileProductCategory $category = null;

    public function __construct(
        ?int $mobileBrandId = null,
        ?int $mobileProductCategoryId = null
    ) {
        if (!$mobileBrandId && !$mobileProductCategoryId) {
            throw new \InvalidArgumentException(
                'กรุณาระบุรหัสแบรนด์หรือรหัสประเภทสินค้า'
            );
        }

        $this->grades = GradeMaster::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        if ($mobileBrandId) {
            $this->brand = MobileBrand::query()
                ->findOrFail($mobileBrandId);
        }

        if ($mobileProductCategoryId) {
            $this->category = MobileProductCategory::query()
                ->findOrFail($mobileProductCategoryId);
        }
    }

    public function collection()
    {
        $rows = MobileModelPrice::query()
            ->with([
                'mobileModel.brand',
                'mobileModel.productCategory',
                'mobileModel.gradePrices.grade',
            ])
            ->whereHas('mobileModel', function ($query) {
                if ($this->brand) {
                    $query->where(
                        'mobile_brand_id',
                        $this->brand->id
                    );
                }

                if ($this->category) {
                    $query->where(
                        'mobile_product_category_id',
                        $this->category->id
                    );
                }
            })

            /*
            |--------------------------------------------------------------------------
            | เรียงโมเดลตาม sort_order
            |--------------------------------------------------------------------------
            */

            ->orderBy(
                MobileModel::query()
                    ->select('sort_order')
                    ->whereColumn(
                        'mobile_models.id',
                        'mobile_model_prices.mobile_model_id'
                    )
                    ->limit(1),
                'asc'
            )

            /*
            |--------------------------------------------------------------------------
            | เรียงตาม ID ของโมเดลไว้เป็นลำดับสำรอง
            |--------------------------------------------------------------------------
            */

            ->orderBy('mobile_model_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | เรียงความจุภายในโมเดลเดียวกันจากน้อยไปมาก
        |--------------------------------------------------------------------------
        |
        | ตัวอย่าง:
        | 32GB
        | 64GB
        | 128GB
        | 256GB
        | 512GB
        | 1TB
        |
        */

        $rows = $rows
            ->sortBy(function (MobileModelPrice $row) {
                $mobileModel = $row->mobileModel;

                $modelSortOrder = max(
                    0,
                    (int) ($mobileModel?->sort_order ?? 0)
                );

                $modelId = (int) (
                    $mobileModel?->id ?? 0
                );

                $capacitySortValue =
                    $this->getCapacitySortValue(
                        (string) $row->capacity
                    );

                return sprintf(
                    '%012d-%012d-%020d-%012d',
                    $modelSortOrder,
                    $modelId,
                    $capacitySortValue,
                    (int) $row->id
                );
            })
            ->values();

        return $rows->map(function (MobileModelPrice $row) {
            $mobileModel = $row->mobileModel;

            $item = [
                'brand_name' =>
                $mobileModel?->brand?->name ?? '',

                'category_name' =>
                $mobileModel?->productCategory?->category_name ?? '',

                'model_name' =>
                $mobileModel?->name ?? '',

                'sort_order' =>
                (int) ($mobileModel?->sort_order ?? 0),

                'capacity' =>
                $row->capacity,

                'base_price' =>
                $row->base_price,

                'min_price' =>
                $row->min_price,
            ];

            /*
            |--------------------------------------------------------------------------
            | สร้างรายการราคาหักตามเกรด
            |--------------------------------------------------------------------------
            |
            | ราคาหักเกรดผูกกับ MobileModel
            | ไม่ได้ผูกกับ MobileModelPrice
            |
            */

            $gradePriceMap = [];

            foreach (
                $mobileModel?->gradePrices ?? collect()
                as $gradePrice
            ) {
                $gradeCode = strtoupper(
                    trim(
                        (string) (
                            $gradePrice->grade?->grade_code
                            ?? ''
                        )
                    )
                );

                if ($gradeCode !== '') {
                    $gradePriceMap[$gradeCode] =
                        $gradePrice->deduct_price;
                }
            }

            foreach ($this->grades as $grade) {
                $gradeCode = strtoupper(
                    trim((string) $grade->grade_code)
                );

                $item[$gradeCode] =
                    $gradePriceMap[$gradeCode] ?? 0;
            }

            $item['model_status'] =
                (int) ($mobileModel?->status ?? 1);

            $item['price_status'] =
                (int) $row->status;

            return $item;
        });
    }

    public function headings(): array
    {
        $headings = [
            'brand_name',
            'category_name',
            'model_name',
            'sort_order',
            'capacity',
            'base_price',
            'min_price',
        ];

        foreach ($this->grades as $grade) {
            $headings[] = strtoupper(
                trim((string) $grade->grade_code)
            );
        }

        $headings[] = 'model_status';
        $headings[] = 'price_status';

        return $headings;
    }

    /*
    |--------------------------------------------------------------------------
    | แปลงความจุเป็นค่าตัวเลขสำหรับใช้เรียงลำดับ
    |--------------------------------------------------------------------------
    |
    | หน่วยกลางที่ใช้คือ MB
    |
    | 512MB  = 512
    | 1GB    = 1,024
    | 128GB  = 131,072
    | 1TB    = 1,048,576
    |
    */

    private function getCapacitySortValue(
        string $capacity
    ): int {
        $capacity = strtoupper(
            trim($capacity)
        );

        if ($capacity === '') {
            return PHP_INT_MAX;
        }

        /*
        |--------------------------------------------------------------------------
        | รองรับรูปแบบ เช่น
        |--------------------------------------------------------------------------
        |
        | 128GB
        | 128 GB
        | 1TB
        | 1.5TB
        | 1,024GB
        |
        */

        preg_match(
            '/([\d,.]+)\s*(TB|GB|MB)?/i',
            $capacity,
            $matches
        );

        if (empty($matches[1])) {
            return PHP_INT_MAX;
        }

        $number = (float) str_replace(
            ',',
            '',
            $matches[1]
        );

        $unit = strtoupper(
            $matches[2] ?? 'GB'
        );

        return match ($unit) {
            'TB' => (int) round(
                $number * 1024 * 1024
            ),

            'GB' => (int) round(
                $number * 1024
            ),

            'MB' => (int) round(
                $number
            ),

            default => (int) round(
                $number * 1024
            ),
        };
    }
}
