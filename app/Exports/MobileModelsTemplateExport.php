<?php

namespace App\Exports;

use App\Models\GradeMaster;
use App\Models\MobileBrand;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MobileModelsTemplateExport implements FromCollection, WithHeadings
{
    protected $grades;
    protected $brand;

    public function __construct(int $mobileBrandId)
    {
        $this->grades = GradeMaster::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $this->brand = MobileBrand::query()->findOrFail($mobileBrandId);
    }

    public function collection()
    {
        $row = [
            'brand_name' => $this->brand->name,
            'model_name' => 'iPhone 15 Pro',
            'capacity' => '128GB',
            'base_price' => 30000,
            'min_price' => 24000,
        ];

        foreach ($this->grades as $grade) {
            $gradeCode = strtoupper(trim((string) $grade->grade_code));

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

        return new Collection([$row]);
    }

    public function headings(): array
    {
        $headings = [
            'brand_name',
            'model_name',
            'capacity',
            'base_price',
            'min_price',
        ];

        foreach ($this->grades as $grade) {
            $headings[] = strtoupper(trim((string) $grade->grade_code));
        }

        $headings[] = 'model_status';
        $headings[] = 'price_status';

        return $headings;
    }
}