<?php

namespace App\Exports;

use App\Models\GradeMaster;
use App\Models\MobileBrand;
use App\Models\MobileModelPrice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MobileModelsExport implements FromCollection, WithHeadings
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
        return MobileModelPrice::query()
            ->with([
                'mobileModel.brand',
                'gradePrices.gradeMaster',
            ])
            ->whereHas('mobileModel', function ($query) {
                $query->where('mobile_brand_id', $this->brand->id);
            })
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($row) {
                $item = [
                    'brand_name' => $row->mobileModel->brand->name ?? '',
                    'model_name' => $row->mobileModel->name ?? '',
                    'capacity' => $row->capacity,
                    'base_price' => $row->base_price,
                    'min_price' => $row->min_price,
                ];

                $gradePriceMap = [];
                foreach ($row->gradePrices as $gradePrice) {
                    $gradeCode = strtoupper(trim((string) ($gradePrice->gradeMaster->grade_code ?? '')));
                    if ($gradeCode !== '') {
                        $gradePriceMap[$gradeCode] = $gradePrice->deduct_price;
                    }
                }

                foreach ($this->grades as $grade) {
                    $gradeCode = strtoupper(trim((string) $grade->grade_code));
                    $item[$gradeCode] = $gradePriceMap[$gradeCode] ?? 0;
                }

                $item['model_status'] = (int) ($row->mobileModel->status ?? 1);
                $item['price_status'] = (int) $row->status;

                return $item;
            });
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