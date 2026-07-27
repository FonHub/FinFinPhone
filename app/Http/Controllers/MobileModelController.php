<?php

namespace App\Http\Controllers;

use App\Exports\MobileModelsExport;
use App\Exports\MobileModelsTemplateExport;
use App\Imports\MobileModelsImport;
use App\Models\GradeMaster;
use App\Models\MobileBrand;
use App\Models\MobileModel;
use App\Models\MobileModelPrice;
use App\Models\MobileModelPriceGrade;
use App\Models\MobileProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class MobileModelController extends Controller
{
    public function index($category)
    {
        $categoryData = MobileProductCategory::query()
            ->where('id', $category)
            ->firstOrFail();

        $models = MobileModel::query()
            ->with(['brand', 'productCategory'])
            ->where('mobile_product_category_id', $categoryData->id)
            ->orderByDesc('id')
            ->get();

        return view('admin.mobile_models.index', [
            'models' => $models,
            'category' => $categoryData,
            'selectedCategory' => $categoryData,
            'selectedBrand' => null,
        ]);
    }

    public function indexByBrand($brand)
    {
        $brandData = MobileBrand::query()->findOrFail($brand);

        $models = MobileModel::query()
            ->with(['brand', 'productCategory'])
            ->where('mobile_brand_id', $brandData->id)
            ->orderByDesc('id')
            ->get();

        return view('admin.mobile_models.index', [
            'models' => $models,
            'category' => null,
            'selectedCategory' => null,
            'selectedBrand' => $brandData,
        ]);
    }

    public function create($category)
    {
        $categoryData = MobileProductCategory::query()
            ->findOrFail($category);

        $model = new MobileModel();

        $brands = MobileBrand::query()
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        $categories = MobileProductCategory::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $grades = GradeMaster::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $modelGradePrices = $grades->mapWithKeys(function ($grade) {
            return [$grade->id => 0];
        })->toArray();

        return view('admin.mobile_models.form', [
            'model' => $model,
            'brands' => $brands,
            'categories' => $categories,
            'grades' => $grades,
            'mode' => 'create',
            'selectedBrand' => null,
            'selectedCategory' => $categoryData,
            'priceRows' => [
                [
                    'capacity' => '',
                    'base_price' => 0,
                    'min_price' => 0,
                    'status' => 1,
                ]
            ],
            'modelGradePrices' => $modelGradePrices,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mobile_brand_id' => ['required', 'integer', 'exists:mobile_brands,id'],
            'mobile_product_category_id' => ['required', 'integer', 'exists:mobile_product_categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'status' => ['nullable', 'in:0,1'],

            'prices' => ['required', 'array', 'min:1'],
            'prices.*.capacity' => ['required', 'string', 'max:100'],
            'prices.*.base_price' => ['required', 'numeric', 'min:0'],
            'prices.*.min_price' => ['required', 'numeric', 'min:0'],
            'prices.*.status' => ['nullable', 'in:0,1'],

            'grade_prices' => ['required', 'array'],
        ], [
            'mobile_brand_id.required' => 'กรุณาเลือกแบรนด์สินค้า',
            'mobile_brand_id.exists' => 'ไม่พบแบรนด์สินค้าในระบบ',
            'mobile_product_category_id.required' => 'กรุณาเลือกประเภทสินค้า',
            'mobile_product_category_id.exists' => 'ไม่พบประเภทสินค้าในระบบ',
            'name.required' => 'กรุณากรอกชื่อโมเดลสินค้า',
            'name.max' => 'ชื่อโมเดลสินค้าต้องไม่เกิน 150 ตัวอักษร',
            'prices.required' => 'กรุณาเพิ่มรายการราคาอย่างน้อย 1 รายการ',
            'prices.min' => 'กรุณาเพิ่มรายการราคาอย่างน้อย 1 รายการ',
            'prices.*.capacity.required' => 'กรุณากรอกความจุ',
            'prices.*.base_price.required' => 'กรุณากรอกราคาพื้นฐาน',
            'prices.*.min_price.required' => 'กรุณากรอกราคาต่ำสุด',
            'grade_prices.required' => 'กรุณากรอกราคาหักเกรด',
        ]);

        $modelName = trim($validated['name']);

        $modelExists = MobileModel::query()
            ->where('mobile_brand_id', $validated['mobile_brand_id'])
            ->where('mobile_product_category_id', $validated['mobile_product_category_id'])
            ->where('name', $modelName)
            ->exists();

        if ($modelExists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'โมเดลสินค้านี้มีอยู่ในประเภทสินค้าที่เลือกแล้ว');
        }

        $capacityCheck = [];
        $normalizedPrices = [];

        foreach ($validated['prices'] as $row) {
            $capacity = trim($row['capacity']);

            if (isset($capacityCheck[mb_strtolower($capacity)])) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'พบรายการความจุซ้ำในฟอร์ม: ' . $capacity);
            }

            $capacityCheck[mb_strtolower($capacity)] = true;

            $normalizedPrices[] = [
                'capacity' => $capacity,
                'base_price' => $row['base_price'],
                'min_price' => $row['min_price'],
                'status' => (int) ($row['status'] ?? 1),
            ];
        }

        $normalizedGradePrices = [];

        foreach ($validated['grade_prices'] as $gradeId => $deductPrice) {
            $normalizedGradePrices[(int) $gradeId] = (float) $deductPrice;
        }

        DB::beginTransaction();

        try {
            $model = MobileModel::create([
                'mobile_brand_id' => (int) $validated['mobile_brand_id'],
                'mobile_product_category_id' => (int) $validated['mobile_product_category_id'],
                'name' => $modelName,
                'status' => (int) $request->input('status', 1),
            ]);

            foreach ($normalizedPrices as $priceRow) {
                $model->prices()->create([
                    'capacity' => $priceRow['capacity'],
                    'base_price' => $priceRow['base_price'],
                    'min_price' => $priceRow['min_price'],
                    'status' => $priceRow['status'],
                ]);
            }

            foreach ($normalizedGradePrices as $gradeId => $deductPrice) {
                MobileModelPriceGrade::create([
                    'mobile_model_id' => $model->id,
                    'grade_master_id' => $gradeId,
                    'deduct_price' => $deductPrice,
                ]);
            }

            DB::commit();
            return redirect()->back()

                // return redirect('admin/mobile-models/category/' . $validated['mobile_product_category_id'])
                ->with('success', 'เพิ่มโมเดลสินค้าและรายการราคาเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถเพิ่มโมเดลสินค้าได้: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $model = MobileModel::query()
            ->with(['brand', 'productCategory', 'prices', 'gradePrices'])
            ->findOrFail($id);

        $brands = MobileBrand::query()
            ->orderBy('name', 'asc')
            ->get();

        $categories = MobileProductCategory::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $grades = GradeMaster::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $priceRows = $model->prices->map(function ($row) {
            return [
                'id' => $row->id,
                'capacity' => $row->capacity,
                'base_price' => $row->base_price,
                'min_price' => $row->min_price,
                'status' => (int) $row->status,
            ];
        })->toArray();

        if (empty($priceRows)) {
            $priceRows[] = [
                'capacity' => '',
                'base_price' => 0,
                'min_price' => 0,
                'status' => 1,
            ];
        }

        $modelGradePrices = [];

        foreach ($grades as $grade) {
            $modelGradePrices[$grade->id] = 0;
        }

        foreach ($model->gradePrices as $gradePrice) {
            $modelGradePrices[$gradePrice->grade_master_id] = $gradePrice->deduct_price;
        }

        return view('admin.mobile_models.form', [
            'model' => $model,
            'brands' => $brands,
            'categories' => $categories,
            'grades' => $grades,
            'mode' => 'edit',
            'selectedBrand' => $model->brand,
            'selectedCategory' => $model->productCategory,
            'priceRows' => $priceRows,
            'modelGradePrices' => $modelGradePrices,
        ]);
    }

    public function update(Request $request, $id)
    {
        $model = MobileModel::query()
            ->with(['prices', 'gradePrices'])
            ->findOrFail($id);

        $validated = $request->validate([
            'mobile_brand_id' => ['required', 'integer', 'exists:mobile_brands,id'],
            'mobile_product_category_id' => ['required', 'integer', 'exists:mobile_product_categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'status' => ['nullable', 'in:0,1'],

            'prices' => ['required', 'array', 'min:1'],
            'prices.*.id' => ['nullable', 'integer'],
            'prices.*.capacity' => ['required', 'string', 'max:100'],
            'prices.*.base_price' => ['required', 'numeric', 'min:0'],
            'prices.*.min_price' => ['required', 'numeric', 'min:0'],
            'prices.*.status' => ['nullable', 'in:0,1'],

            'grade_prices' => ['required', 'array'],
        ], [
            'mobile_brand_id.required' => 'กรุณาเลือกแบรนด์สินค้า',
            'mobile_brand_id.exists' => 'ไม่พบแบรนด์สินค้าในระบบ',
            'mobile_product_category_id.required' => 'กรุณาเลือกประเภทสินค้า',
            'mobile_product_category_id.exists' => 'ไม่พบประเภทสินค้าในระบบ',
            'name.required' => 'กรุณากรอกชื่อโมเดลสินค้า',
            'name.max' => 'ชื่อโมเดลสินค้าต้องไม่เกิน 150 ตัวอักษร',
            'prices.required' => 'กรุณาเพิ่มรายการราคาอย่างน้อย 1 รายการ',
            'prices.min' => 'กรุณาเพิ่มรายการราคาอย่างน้อย 1 รายการ',
            'prices.*.capacity.required' => 'กรุณากรอกความจุ',
            'prices.*.base_price.required' => 'กรุณากรอกราคาพื้นฐาน',
            'prices.*.min_price.required' => 'กรุณากรอกราคาต่ำสุด',
            'grade_prices.required' => 'กรุณากรอกราคาหักเกรด',
        ]);

        $modelName = trim($validated['name']);

        $modelExists = MobileModel::query()
            ->where('mobile_brand_id', $validated['mobile_brand_id'])
            ->where('mobile_product_category_id', $validated['mobile_product_category_id'])
            ->where('name', $modelName)
            ->where('id', '!=', $model->id)
            ->exists();

        if ($modelExists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'โมเดลสินค้านี้มีอยู่ในประเภทสินค้าที่เลือกแล้ว');
        }

        $capacityCheck = [];
        $normalizedPrices = [];

        foreach ($validated['prices'] as $row) {
            $capacity = trim($row['capacity']);

            if (isset($capacityCheck[mb_strtolower($capacity)])) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'พบรายการความจุซ้ำในฟอร์ม: ' . $capacity);
            }

            $capacityCheck[mb_strtolower($capacity)] = true;

            $normalizedPrices[] = [
                'id' => $row['id'] ?? null,
                'capacity' => $capacity,
                'base_price' => $row['base_price'],
                'min_price' => $row['min_price'],
                'status' => (int) ($row['status'] ?? 1),
            ];
        }

        $normalizedGradePrices = [];

        foreach ($validated['grade_prices'] as $gradeId => $deductPrice) {
            $normalizedGradePrices[(int) $gradeId] = (float) $deductPrice;
        }

        DB::beginTransaction();

        try {
            $model->update([
                'mobile_brand_id' => (int) $validated['mobile_brand_id'],
                'mobile_product_category_id' => (int) $validated['mobile_product_category_id'],
                'name' => $modelName,
                'status' => (int) $request->input('status', 1),
            ]);

            $keepIds = [];

            foreach ($normalizedPrices as $priceRow) {
                if (!empty($priceRow['id'])) {
                    $price = MobileModelPrice::query()
                        ->where('mobile_model_id', $model->id)
                        ->where('id', $priceRow['id'])
                        ->first();

                    if ($price) {
                        $price->update([
                            'capacity' => $priceRow['capacity'],
                            'base_price' => $priceRow['base_price'],
                            'min_price' => $priceRow['min_price'],
                            'status' => $priceRow['status'],
                        ]);

                        $keepIds[] = $price->id;
                    }
                } else {
                    $newPrice = $model->prices()->create([
                        'capacity' => $priceRow['capacity'],
                        'base_price' => $priceRow['base_price'],
                        'min_price' => $priceRow['min_price'],
                        'status' => $priceRow['status'],
                    ]);

                    $keepIds[] = $newPrice->id;
                }
            }

            if (!empty($keepIds)) {
                MobileModelPrice::query()
                    ->where('mobile_model_id', $model->id)
                    ->whereNotIn('id', $keepIds)
                    ->delete();
            } else {
                MobileModelPrice::query()
                    ->where('mobile_model_id', $model->id)
                    ->delete();
            }

            MobileModelPriceGrade::query()
                ->where('mobile_model_id', $model->id)
                ->delete();

            foreach ($normalizedGradePrices as $gradeId => $deductPrice) {
                MobileModelPriceGrade::create([
                    'mobile_model_id' => $model->id,
                    'grade_master_id' => $gradeId,
                    'deduct_price' => $deductPrice,
                ]);
            }

            DB::commit();
            return redirect()->back()
                // return redirect('admin/mobile-models/category/' . $validated['mobile_product_category_id'])
                ->with('success', 'แก้ไขโมเดลสินค้าและรายการราคาเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถแก้ไขโมเดลสินค้าได้: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:mobile_models,id'],
        ], [
            'id.required' => 'ไม่พบรหัสข้อมูลที่ต้องการลบ',
            'id.integer' => 'รหัสข้อมูลไม่ถูกต้อง',
            'id.exists' => 'ไม่พบข้อมูลโมเดลสินค้า',
        ]);

        DB::beginTransaction();

        try {
            $model = MobileModel::query()->findOrFail($request->id);
            $categoryId = $model->mobile_product_category_id;
            $brandId = $model->mobile_brand_id;

            $model->delete();

            DB::commit();

            if ($categoryId) {
                return redirect('admin/mobile-models/category/' . $categoryId)
                    ->with('success', 'ลบโมเดลสินค้าเรียบร้อยแล้ว');
            }

            return redirect('admin/mobile-models/brand/' . $brandId)
                ->with('success', 'ลบโมเดลสินค้าเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'ไม่สามารถลบโมเดลสินค้าได้: ' . $e->getMessage());
        }
    }

    public function export($category)
    {
        return Excel::download(
            new MobileModelsExport((int) $category),
            'mobile-models.xlsx'
        );
    }

    public function exportTemplate($category)
    {
        return Excel::download(
            new MobileModelsTemplateExport((int) $category),
            'mobile-models-template.xlsx'
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'mobile_brand_id' => ['nullable', 'integer', 'exists:mobile_brands,id'],
            'mobile_product_category_id' => ['nullable', 'integer', 'exists:mobile_product_categories,id'],
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ], [
            'mobile_brand_id.exists' => 'ไม่พบแบรนด์สินค้าในระบบ',
            'mobile_product_category_id.exists' => 'ไม่พบประเภทสินค้าในระบบ',
            'file.required' => 'กรุณาเลือกไฟล์ Excel',
            'file.file' => 'ไฟล์ที่อัปโหลดไม่ถูกต้อง',
            'file.mimes' => 'รองรับเฉพาะไฟล์ xlsx, xls, csv เท่านั้น',
        ]);

        try {
            Excel::import(
                new MobileModelsImport(
                    $request->filled('mobile_brand_id') ? (int) $request->mobile_brand_id : null,
                    $request->filled('mobile_product_category_id') ? (int) $request->mobile_product_category_id : null
                ),
                $request->file('file')
            );

            if ($request->filled('mobile_product_category_id')) {
                return redirect('admin/mobile-models/category/' . $request->mobile_product_category_id)
                    ->with('success', 'นำเข้าข้อมูลโมเดลสินค้าเรียบร้อยแล้ว');
            }

            if ($request->filled('mobile_brand_id')) {
                return redirect('admin/mobile-models/brand/' . $request->mobile_brand_id)
                    ->with('success', 'นำเข้าข้อมูลโมเดลสินค้าเรียบร้อยแล้ว');
            }

            return redirect()->back()
                ->with('success', 'นำเข้าข้อมูลโมเดลสินค้าเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถนำเข้าข้อมูลได้: ' . $e->getMessage());
        }
    }
}
