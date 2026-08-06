<?php

namespace App\Exports;

use App\Models\GradeMaster;
use App\Models\MobileBrand;
use App\Models\MobileProductCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MobileModelsTemplateExport implements FromCollection, WithHeadings
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
        $brandName = $this->brand?->name ?? 'Apple';

        $categoryName =
            $this->category?->category_name
            ?? 'โทรศัพท์มือถือ';

        $row = [
            'brand_name' => $brandName,

            'category_name' => $categoryName,

            'model_name' => 'iPhone 15 Pro',

            'sort_order' => 1,

            'capacity' => '128GB',

            'base_price' => 30000,

            'min_price' => 24000,
        ];

        foreach ($this->grades as $grade) {
            $gradeCode = strtoupper(
                trim((string) $grade->grade_code)
            );

            $sampleDeduct = match ($gradeCode) {
                'A' => 0,
                'B' => 1000,
                'C' => 2000,
                'D' => 3000,
                'E' => 4500,
                'F' => 6000,
                default => 0,
            };

            $row[$gradeCode] = $sampleDeduct;
        }

        $row['model_status'] = 1;
        $row['price_status'] = 1;

        /*
        |--------------------------------------------------------------------------
        | ตัวอย่างหลายความจุของโมเดลเดียวกัน
        |--------------------------------------------------------------------------
        */

        $row2 = $row;
        $row2['capacity'] = '256GB';
        $row2['base_price'] = 34000;
        $row2['min_price'] = 27000;

        $row3 = $row;
        $row3['capacity'] = '512GB';
        $row3['base_price'] = 40000;
        $row3['min_price'] = 32000;

        return new Collection([
            $row,
            $row2,
            $row3,
        ]);
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
}
