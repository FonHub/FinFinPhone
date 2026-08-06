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
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class MobileModelController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | รายการโมเดลจากหน้าประเภทสินค้า
    |--------------------------------------------------------------------------
    */

    public function index($category)
    {
        $categoryData = MobileProductCategory::query()
            ->findOrFail((int) $category);

        $models = MobileModel::query()
            ->with([
                'brand',
                'productCategory',
            ])
            ->where(
                'mobile_product_category_id',
                $categoryData->id
            )
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.mobile_models.index', [
            'models' => $models,
            'category' => $categoryData,
            'selectedCategory' => $categoryData,
            'selectedBrand' => null,
            'pageScope' => 'category',
            'scopeId' => $categoryData->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | รายการโมเดลจากหน้าแบรนด์
    |--------------------------------------------------------------------------
    */

    public function indexByBrand($brand)
    {
        $brandData = MobileBrand::query()
            ->findOrFail((int) $brand);

        $models = MobileModel::query()
            ->with([
                'brand',
                'productCategory',
            ])
            ->where(
                'mobile_brand_id',
                $brandData->id
            )
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.mobile_models.index', [
            'models' => $models,
            'category' => null,
            'selectedCategory' => null,
            'selectedBrand' => $brandData,
            'pageScope' => 'brand',
            'scopeId' => $brandData->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | เพิ่มโมเดลจากหน้าประเภทสินค้า
    |--------------------------------------------------------------------------
    */

    public function create($category)
    {
        $categoryData = MobileProductCategory::query()
            ->findOrFail((int) $category);

        return $this->renderCreateForm(
            selectedBrand: null,
            selectedCategory: $categoryData,
            pageScope: 'category'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | เพิ่มโมเดลจากหน้าแบรนด์
    |--------------------------------------------------------------------------
    */

    public function createByBrand($brand)
    {
        $brandData = MobileBrand::query()
            ->findOrFail((int) $brand);

        return $this->renderCreateForm(
            selectedBrand: $brandData,
            selectedCategory: null,
            pageScope: 'brand'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | แสดงฟอร์มเพิ่มข้อมูล
    |--------------------------------------------------------------------------
    */

    private function renderCreateForm(
        ?MobileBrand $selectedBrand,
        ?MobileProductCategory $selectedCategory,
        string $pageScope
    ) {
        $model = new MobileModel();

        $brands = MobileBrand::query()
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ประเภทสินค้าเป็นอิสระจากแบรนด์
        |--------------------------------------------------------------------------
        */

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

        $modelGradePrices = $grades
            ->mapWithKeys(function ($grade) {
                return [
                    $grade->id => 0,
                ];
            })
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | ลำดับเริ่มต้น
        |--------------------------------------------------------------------------
        |
        | เข้าจากหน้าแบรนด์แต่ยังไม่เลือกประเภท:
        | ใช้ลำดับถัดไปของแบรนด์นั้นก่อน
        |
        | เข้าจากหน้าประเภทแต่ยังไม่เลือกแบรนด์:
        | ใช้ลำดับถัดไปของประเภทนั้นก่อน
        |
        | ตอนบันทึกจริง ระบบจะขยับลำดับในคู่แบรนด์ + ประเภทที่เลือก
        |
        */

        $nextSortOrder = MobileModel::query()
            ->when(
                $selectedBrand,
                function ($query) use ($selectedBrand) {
                    $query->where(
                        'mobile_brand_id',
                        $selectedBrand->id
                    );
                }
            )
            ->when(
                $selectedCategory,
                function ($query) use ($selectedCategory) {
                    $query->where(
                        'mobile_product_category_id',
                        $selectedCategory->id
                    );
                }
            )
            ->max('sort_order');

        $nextSortOrder = ((int) $nextSortOrder) + 1;

        return view('admin.mobile_models.form', [
            'model' => $model,
            'brands' => $brands,
            'categories' => $categories,
            'grades' => $grades,
            'mode' => 'create',

            'selectedBrand' => $selectedBrand,
            'selectedCategory' => $selectedCategory,
            'pageScope' => $pageScope,
            'nextSortOrder' => $nextSortOrder,

            'priceRows' => [
                [
                    'capacity' => '',
                    'base_price' => 0,
                    'min_price' => 0,
                    'status' => 1,
                ],
            ],

            'modelGradePrices' => $modelGradePrices,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | บันทึกโมเดลใหม่
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_scope' => [
                'nullable',
                Rule::in([
                    'brand',
                    'category',
                ]),
            ],

            'mobile_brand_id' => [
                'required',
                'integer',
                'exists:mobile_brands,id',
            ],

            'mobile_product_category_id' => [
                'required',
                'integer',
                'exists:mobile_product_categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    0,
                    1,
                    '0',
                    '1',
                ]),
            ],

            'prices' => [
                'required',
                'array',
                'min:1',
            ],

            'prices.*.capacity' => [
                'required',
                'string',
                'max:100',
            ],

            'prices.*.base_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'prices.*.min_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'prices.*.status' => [
                'nullable',
                Rule::in([
                    0,
                    1,
                    '0',
                    '1',
                ]),
            ],

            'grade_prices' => [
                'required',
                'array',
            ],

            'grade_prices.*' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ], [
            'mobile_brand_id.required' =>
            'กรุณาเลือกแบรนด์สินค้า',

            'mobile_brand_id.integer' =>
            'รหัสแบรนด์สินค้าไม่ถูกต้อง',

            'mobile_brand_id.exists' =>
            'ไม่พบแบรนด์สินค้าในระบบ',

            'mobile_product_category_id.required' =>
            'กรุณาเลือกประเภทสินค้า',

            'mobile_product_category_id.integer' =>
            'รหัสประเภทสินค้าไม่ถูกต้อง',

            'mobile_product_category_id.exists' =>
            'ไม่พบประเภทสินค้าในระบบ',

            'name.required' =>
            'กรุณากรอกชื่อโมเดลสินค้า',

            'name.max' =>
            'ชื่อโมเดลสินค้าต้องไม่เกิน 150 ตัวอักษร',

            'sort_order.required' =>
            'กรุณากรอกลำดับการแสดงผล',

            'sort_order.integer' =>
            'ลำดับการแสดงผลต้องเป็นตัวเลข',

            'sort_order.min' =>
            'ลำดับการแสดงผลต้องเริ่มตั้งแต่ 1',

            'prices.required' =>
            'กรุณาเพิ่มรายการราคาอย่างน้อย 1 รายการ',

            'prices.min' =>
            'กรุณาเพิ่มรายการราคาอย่างน้อย 1 รายการ',

            'prices.*.capacity.required' =>
            'กรุณากรอกความจุ',

            'prices.*.base_price.required' =>
            'กรุณากรอกราคาพื้นฐาน',

            'prices.*.min_price.required' =>
            'กรุณากรอกราคาต่ำสุด',

            'grade_prices.required' =>
            'กรุณากรอกราคาหักเกรด',

            'grade_prices.*.numeric' =>
            'ราคาหักเกรดต้องเป็นตัวเลข',

            'grade_prices.*.min' =>
            'ราคาหักเกรดต้องไม่น้อยกว่า 0',
        ]);

        $pageScope = $validated['page_scope'] ?? 'category';

        $brandId = (int) $validated['mobile_brand_id'];
        $categoryId = (int) $validated['mobile_product_category_id'];
        $modelName = trim($validated['name']);
        $sortOrder = (int) $validated['sort_order'];

        /*
        |--------------------------------------------------------------------------
        | ตรวจชื่อโมเดลซ้ำภายในคู่แบรนด์และประเภทเดียวกัน
        |--------------------------------------------------------------------------
        */

        $modelExists = MobileModel::query()
            ->where(
                'mobile_brand_id',
                $brandId
            )
            ->where(
                'mobile_product_category_id',
                $categoryId
            )
            ->whereRaw(
                'LOWER(name) = ?',
                [
                    mb_strtolower($modelName),
                ]
            )
            ->exists();

        if ($modelExists) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'โมเดลสินค้านี้มีอยู่ในแบรนด์และประเภทสินค้าที่เลือกแล้ว'
                );
        }

        $normalizedPrices = $this->normalizePrices(
            $validated['prices']
        );

        if (
            $normalizedPrices
            instanceof \Illuminate\Http\RedirectResponse
        ) {
            return $normalizedPrices;
        }

        $normalizedGradePrices =
            $this->normalizeGradePrices(
                $validated['grade_prices']
            );

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | เปิดตำแหน่งลำดับใหม่
            |--------------------------------------------------------------------------
            */

            $this->openSortPosition(
                brandId: $brandId,
                categoryId: $categoryId,
                sortOrder: $sortOrder
            );

            /*
            |--------------------------------------------------------------------------
            | สร้างโมเดล
            |--------------------------------------------------------------------------
            */

            $model = MobileModel::query()->create([
                'mobile_brand_id' => $brandId,

                'mobile_product_category_id' =>
                $categoryId,

                'name' => $modelName,

                'sort_order' => $sortOrder,

                'status' => (int) $request->input(
                    'status',
                    1
                ),
            ]);

            /*
            |--------------------------------------------------------------------------
            | บันทึกราคาแยกตามความจุ
            |--------------------------------------------------------------------------
            */

            foreach ($normalizedPrices as $priceRow) {
                $model->prices()->create([
                    'capacity' =>
                    $priceRow['capacity'],

                    'base_price' =>
                    $priceRow['base_price'],

                    'min_price' =>
                    $priceRow['min_price'],

                    'status' =>
                    $priceRow['status'],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | บันทึกราคาหักตามเกรด
            |--------------------------------------------------------------------------
            */

            foreach (
                $normalizedGradePrices
                as $gradeId => $deductPrice
            ) {
                MobileModelPriceGrade::query()->create([
                    'mobile_model_id' =>
                    $model->id,

                    'grade_master_id' =>
                    $gradeId,

                    'deduct_price' =>
                    $deductPrice,
                ]);
            }

            DB::commit();

            return $this->redirectToScope(
                scope: $pageScope,
                brandId: $brandId,
                categoryId: $categoryId,
                message: 'เพิ่มโมเดลสินค้าและรายการราคาเรียบร้อยแล้ว'
            );
        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'ไม่สามารถเพิ่มโมเดลสินค้าได้: '
                        . $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ฟอร์มแก้ไข
    |--------------------------------------------------------------------------
    */

    public function edit(Request $request, $id)
    {
        $model = MobileModel::query()
            ->with([
                'brand',
                'productCategory',
                'prices',
                'gradePrices',
            ])
            ->findOrFail((int) $id);

        $pageScope = $request->query(
            'scope',
            'category'
        );

        if (
            !in_array(
                $pageScope,
                [
                    'brand',
                    'category',
                ],
                true
            )
        ) {
            $pageScope = 'category';
        }

        $brands = MobileBrand::query()
            ->where('status', 1)
            ->orWhere('id', $model->mobile_brand_id)
            ->orderBy('name', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $categories = MobileProductCategory::query()
            ->where('status', 1)
            ->orWhere(
                'id',
                $model->mobile_product_category_id
            )
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $grades = GradeMaster::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | เรียงความจุจากน้อยไปมากในฟอร์มแก้ไข
        |--------------------------------------------------------------------------
        */

        $priceRows = $model->prices
            ->sortBy(function ($row) {
                return $this->getCapacitySortValue(
                    (string) $row->capacity
                );
            })
            ->values()
            ->map(function ($row) {
                return [
                    'id' => $row->id,
                    'capacity' => $row->capacity,
                    'base_price' => $row->base_price,
                    'min_price' => $row->min_price,
                    'status' => (int) $row->status,
                ];
            })
            ->toArray();

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

            'selectedCategory' =>
            $model->productCategory,

            'pageScope' => $pageScope,

            'priceRows' => $priceRows,

            'modelGradePrices' =>
            $modelGradePrices,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | อัปเดตโมเดล
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $model = MobileModel::query()
            ->with([
                'prices',
                'gradePrices',
            ])
            ->findOrFail((int) $id);

        $validated = $request->validate([
            'page_scope' => [
                'nullable',
                Rule::in([
                    'brand',
                    'category',
                ]),
            ],

            'mobile_brand_id' => [
                'required',
                'integer',
                'exists:mobile_brands,id',
            ],

            'mobile_product_category_id' => [
                'required',
                'integer',
                'exists:mobile_product_categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    0,
                    1,
                    '0',
                    '1',
                ]),
            ],

            'prices' => [
                'required',
                'array',
                'min:1',
            ],

            'prices.*.id' => [
                'nullable',
                'integer',
            ],

            'prices.*.capacity' => [
                'required',
                'string',
                'max:100',
            ],

            'prices.*.base_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'prices.*.min_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'prices.*.status' => [
                'nullable',
                Rule::in([
                    0,
                    1,
                    '0',
                    '1',
                ]),
            ],

            'grade_prices' => [
                'required',
                'array',
            ],

            'grade_prices.*' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ], [
            'mobile_brand_id.required' =>
            'กรุณาเลือกแบรนด์สินค้า',

            'mobile_brand_id.integer' =>
            'รหัสแบรนด์สินค้าไม่ถูกต้อง',

            'mobile_brand_id.exists' =>
            'ไม่พบแบรนด์สินค้าในระบบ',

            'mobile_product_category_id.required' =>
            'กรุณาเลือกประเภทสินค้า',

            'mobile_product_category_id.integer' =>
            'รหัสประเภทสินค้าไม่ถูกต้อง',

            'mobile_product_category_id.exists' =>
            'ไม่พบประเภทสินค้าในระบบ',

            'name.required' =>
            'กรุณากรอกชื่อโมเดลสินค้า',

            'name.max' =>
            'ชื่อโมเดลสินค้าต้องไม่เกิน 150 ตัวอักษร',

            'sort_order.required' =>
            'กรุณากรอกลำดับการแสดงผล',

            'sort_order.integer' =>
            'ลำดับการแสดงผลต้องเป็นตัวเลข',

            'sort_order.min' =>
            'ลำดับการแสดงผลต้องเริ่มตั้งแต่ 1',

            'prices.required' =>
            'กรุณาเพิ่มรายการราคาอย่างน้อย 1 รายการ',

            'prices.min' =>
            'กรุณาเพิ่มรายการราคาอย่างน้อย 1 รายการ',

            'prices.*.capacity.required' =>
            'กรุณากรอกความจุ',

            'prices.*.base_price.required' =>
            'กรุณากรอกราคาพื้นฐาน',

            'prices.*.min_price.required' =>
            'กรุณากรอกราคาต่ำสุด',

            'grade_prices.required' =>
            'กรุณากรอกราคาหักเกรด',

            'grade_prices.*.numeric' =>
            'ราคาหักเกรดต้องเป็นตัวเลข',

            'grade_prices.*.min' =>
            'ราคาหักเกรดต้องไม่น้อยกว่า 0',
        ]);

        $pageScope = $validated['page_scope'] ?? 'category';

        $brandId = (int) $validated['mobile_brand_id'];

        $categoryId =
            (int) $validated['mobile_product_category_id'];

        $modelName = trim($validated['name']);

        $newSortOrder =
            (int) $validated['sort_order'];

        $oldBrandId =
            (int) $model->mobile_brand_id;

        $oldCategoryId =
            (int) $model->mobile_product_category_id;

        $oldSortOrder =
            max(1, (int) $model->sort_order);

        /*
        |--------------------------------------------------------------------------
        | ตรวจชื่อซ้ำ
        |--------------------------------------------------------------------------
        */

        $modelExists = MobileModel::query()
            ->where(
                'mobile_brand_id',
                $brandId
            )
            ->where(
                'mobile_product_category_id',
                $categoryId
            )
            ->whereRaw(
                'LOWER(name) = ?',
                [
                    mb_strtolower($modelName),
                ]
            )
            ->where(
                'id',
                '!=',
                $model->id
            )
            ->exists();

        if ($modelExists) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'โมเดลสินค้านี้มีอยู่ในแบรนด์และประเภทสินค้าที่เลือกแล้ว'
                );
        }

        $normalizedPrices = $this->normalizePrices(
            $validated['prices'],
            true
        );

        if (
            $normalizedPrices
            instanceof \Illuminate\Http\RedirectResponse
        ) {
            return $normalizedPrices;
        }

        $normalizedGradePrices =
            $this->normalizeGradePrices(
                $validated['grade_prices']
            );

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | ย้ายตำแหน่งลำดับ
            |--------------------------------------------------------------------------
            */

            $this->moveSortPosition(
                modelId: (int) $model->id,
                oldBrandId: $oldBrandId,
                oldCategoryId: $oldCategoryId,
                oldSortOrder: $oldSortOrder,
                newBrandId: $brandId,
                newCategoryId: $categoryId,
                newSortOrder: $newSortOrder
            );

            /*
            |--------------------------------------------------------------------------
            | อัปเดตโมเดล
            |--------------------------------------------------------------------------
            */

            $model->update([
                'mobile_brand_id' =>
                $brandId,

                'mobile_product_category_id' =>
                $categoryId,

                'name' =>
                $modelName,

                'sort_order' =>
                $newSortOrder,

                'status' =>
                (int) $request->input(
                    'status',
                    1
                ),
            ]);

            /*
            |--------------------------------------------------------------------------
            | อัปเดตราคาแยกตามความจุ
            |--------------------------------------------------------------------------
            */

            $keepIds = [];

            foreach ($normalizedPrices as $priceRow) {
                $priceId =
                    $priceRow['id'] ?? null;

                if ($priceId) {
                    $price = MobileModelPrice::query()
                        ->where(
                            'mobile_model_id',
                            $model->id
                        )
                        ->where(
                            'id',
                            $priceId
                        )
                        ->first();

                    if ($price) {
                        $price->update([
                            'capacity' =>
                            $priceRow['capacity'],

                            'base_price' =>
                            $priceRow['base_price'],

                            'min_price' =>
                            $priceRow['min_price'],

                            'status' =>
                            $priceRow['status'],
                        ]);

                        $keepIds[] = $price->id;

                        continue;
                    }
                }

                $newPrice = $model->prices()->create([
                    'capacity' =>
                    $priceRow['capacity'],

                    'base_price' =>
                    $priceRow['base_price'],

                    'min_price' =>
                    $priceRow['min_price'],

                    'status' =>
                    $priceRow['status'],
                ]);

                $keepIds[] = $newPrice->id;
            }

            MobileModelPrice::query()
                ->where(
                    'mobile_model_id',
                    $model->id
                )
                ->when(
                    !empty($keepIds),
                    function ($query) use ($keepIds) {
                        $query->whereNotIn(
                            'id',
                            $keepIds
                        );
                    }
                )
                ->delete();

            /*
            |--------------------------------------------------------------------------
            | อัปเดตราคาหักตามเกรด
            |--------------------------------------------------------------------------
            */

            MobileModelPriceGrade::query()
                ->where(
                    'mobile_model_id',
                    $model->id
                )
                ->delete();

            foreach (
                $normalizedGradePrices
                as $gradeId => $deductPrice
            ) {
                MobileModelPriceGrade::query()->create([
                    'mobile_model_id' =>
                    $model->id,

                    'grade_master_id' =>
                    $gradeId,

                    'deduct_price' =>
                    $deductPrice,
                ]);
            }

            DB::commit();

            return $this->redirectToScope(
                scope: $pageScope,
                brandId: $brandId,
                categoryId: $categoryId,
                message: 'แก้ไขโมเดลสินค้าและรายการราคาเรียบร้อยแล้ว'
            );
        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'ไม่สามารถแก้ไขโมเดลสินค้าได้: '
                        . $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ลบโมเดล
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => [
                'required',
                'integer',
                'exists:mobile_models,id',
            ],

            'scope' => [
                'nullable',
                Rule::in([
                    'brand',
                    'category',
                ]),
            ],
        ], [
            'id.required' =>
            'ไม่พบรหัสข้อมูลที่ต้องการลบ',

            'id.integer' =>
            'รหัสข้อมูลไม่ถูกต้อง',

            'id.exists' =>
            'ไม่พบข้อมูลโมเดลสินค้า',
        ]);

        $scope = $validated['scope'] ?? 'category';

        DB::beginTransaction();

        try {
            $model = MobileModel::query()
                ->findOrFail(
                    (int) $validated['id']
                );

            $categoryId =
                (int) $model
                    ->mobile_product_category_id;

            $brandId =
                (int) $model->mobile_brand_id;

            $sortOrder =
                max(1, (int) $model->sort_order);

            /*
            |--------------------------------------------------------------------------
            | ลบโมเดล
            |--------------------------------------------------------------------------
            */

            $model->delete();

            /*
            |--------------------------------------------------------------------------
            | ปิดช่องว่างของลำดับ
            |--------------------------------------------------------------------------
            */

            $this->closeSortPosition(
                brandId: $brandId,
                categoryId: $categoryId,
                deletedSortOrder: $sortOrder,
                ignoreModelId: (int) $model->id
            );

            DB::commit();

            return $this->redirectToScope(
                scope: $scope,
                brandId: $brandId,
                categoryId: $categoryId,
                message: 'ลบโมเดลสินค้าเรียบร้อยแล้ว'
            );
        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'ไม่สามารถลบโมเดลสินค้าได้: '
                        . $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Export ตามประเภทสินค้า
    |--------------------------------------------------------------------------
    */

    public function export($category)
    {
        $categoryData = MobileProductCategory::query()
            ->findOrFail((int) $category);

        return Excel::download(
            new MobileModelsExport(
                null,
                $categoryData->id
            ),
            'mobile-models-category-'
                . $categoryData->id
                . '.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Export Template ตามประเภทสินค้า
    |--------------------------------------------------------------------------
    */

    public function exportTemplate($category)
    {
        $categoryData = MobileProductCategory::query()
            ->findOrFail((int) $category);

        return Excel::download(
            new MobileModelsTemplateExport(
                null,
                $categoryData->id
            ),
            'mobile-models-template-category-'
                . $categoryData->id
                . '.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Export ตามแบรนด์
    |--------------------------------------------------------------------------
    */

    public function exportByBrand($brand)
    {
        $brandData = MobileBrand::query()
            ->findOrFail((int) $brand);

        return Excel::download(
            new MobileModelsExport(
                $brandData->id,
                null
            ),
            'mobile-models-brand-'
                . $brandData->id
                . '.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Export Template ตามแบรนด์
    |--------------------------------------------------------------------------
    */

    public function exportTemplateByBrand($brand)
    {
        $brandData = MobileBrand::query()
            ->findOrFail((int) $brand);

        return Excel::download(
            new MobileModelsTemplateExport(
                $brandData->id,
                null
            ),
            'mobile-models-template-brand-'
                . $brandData->id
                . '.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Import
    |--------------------------------------------------------------------------
    */

    public function import(Request $request)
    {
        $validated = $request->validate([
            'scope' => [
                'required',
                Rule::in([
                    'brand',
                    'category',
                ]),
            ],

            'mobile_brand_id' => [
                'nullable',
                'integer',
                'exists:mobile_brands,id',
            ],

            'mobile_product_category_id' => [
                'nullable',
                'integer',
                'exists:mobile_product_categories,id',
            ],

            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
            ],
        ], [
            'scope.required' =>
            'ไม่พบรูปแบบการนำเข้าข้อมูล',

            'scope.in' =>
            'รูปแบบการนำเข้าข้อมูลไม่ถูกต้อง',

            'mobile_brand_id.integer' =>
            'รหัสแบรนด์สินค้าไม่ถูกต้อง',

            'mobile_brand_id.exists' =>
            'ไม่พบแบรนด์สินค้าในระบบ',

            'mobile_product_category_id.integer' =>
            'รหัสประเภทสินค้าไม่ถูกต้อง',

            'mobile_product_category_id.exists' =>
            'ไม่พบประเภทสินค้าในระบบ',

            'file.required' =>
            'กรุณาเลือกไฟล์ Excel',

            'file.file' =>
            'ไฟล์ที่อัปโหลดไม่ถูกต้อง',

            'file.mimes' =>
            'รองรับเฉพาะไฟล์ xlsx, xls และ csv เท่านั้น',
        ]);

        $scope = $validated['scope'];

        $brandId =
            !empty($validated['mobile_brand_id'])
            ? (int) $validated['mobile_brand_id']
            : null;

        $categoryId =
            !empty($validated['mobile_product_category_id'])
            ? (int) $validated['mobile_product_category_id']
            : null;

        if ($scope === 'brand' && !$brandId) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'ไม่พบรหัสแบรนด์สินค้า'
                );
        }

        if ($scope === 'category' && !$categoryId) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'ไม่พบรหัสประเภทสินค้า'
                );
        }

        try {
            Excel::import(
                new MobileModelsImport(
                    $brandId,
                    $categoryId
                ),
                $request->file('file')
            );

            return $this->redirectToScope(
                scope: $scope,
                brandId: $brandId,
                categoryId: $categoryId,
                message: 'นำเข้าข้อมูลโมเดลสินค้าเรียบร้อยแล้ว'
            );
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'ไม่สามารถนำเข้าข้อมูลได้: '
                        . $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | จัดรูปแบบราคา
    |--------------------------------------------------------------------------
    */

    private function normalizePrices(
        array $prices,
        bool $includeId = false
    ) {
        $capacityCheck = [];
        $normalizedPrices = [];

        foreach ($prices as $row) {
            $capacity = trim(
                (string) ($row['capacity'] ?? '')
            );

            $capacityKey =
                mb_strtolower($capacity);

            if (isset($capacityCheck[$capacityKey])) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'พบรายการความจุซ้ำในฟอร์ม: '
                            . $capacity
                    );
            }

            $capacityCheck[$capacityKey] = true;

            $priceRow = [
                'capacity' =>
                $capacity,

                'base_price' =>
                (float) (
                    $row['base_price'] ?? 0
                ),

                'min_price' =>
                (float) (
                    $row['min_price'] ?? 0
                ),

                'status' =>
                (int) (
                    $row['status'] ?? 1
                ),
            ];

            if ($includeId) {
                $priceRow['id'] =
                    !empty($row['id'])
                    ? (int) $row['id']
                    : null;
            }

            $normalizedPrices[] = $priceRow;
        }

        /*
        |--------------------------------------------------------------------------
        | เรียงความจุจากน้อยไปมากก่อนบันทึก
        |--------------------------------------------------------------------------
        */

        usort(
            $normalizedPrices,
            function ($left, $right) {
                return $this->getCapacitySortValue(
                    (string) $left['capacity']
                ) <=> $this->getCapacitySortValue(
                    (string) $right['capacity']
                );
            }
        );

        return $normalizedPrices;
    }

    /*
    |--------------------------------------------------------------------------
    | จัดรูปแบบราคาหักเกรด
    |--------------------------------------------------------------------------
    */

    private function normalizeGradePrices(
        array $gradePrices
    ): array {
        $normalizedGradePrices = [];

        foreach (
            $gradePrices
            as $gradeId => $deductPrice
        ) {
            $normalizedGradePrices[(int) $gradeId] = (float) $deductPrice;
        }

        return $normalizedGradePrices;
    }

    /*
    |--------------------------------------------------------------------------
    | เปิดตำแหน่งลำดับ
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
    | ย้ายตำแหน่งลำดับ
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
        | ปิดช่องว่างของกลุ่มเดิม
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
        | เปิดตำแหน่งในกลุ่มใหม่
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
    | ปิดช่องว่างของลำดับ
    |--------------------------------------------------------------------------
    */

    private function closeSortPosition(
        int $brandId,
        int $categoryId,
        int $deletedSortOrder,
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
                '>',
                $deletedSortOrder
            )
            ->decrement('sort_order');
    }

    /*
    |--------------------------------------------------------------------------
    | แปลงความจุเป็นตัวเลขสำหรับเรียงลำดับ
    |--------------------------------------------------------------------------
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

    /*
    |--------------------------------------------------------------------------
    | Redirect กลับตามหน้าที่เข้ามา
    |--------------------------------------------------------------------------
    */

    private function redirectToScope(
        string $scope,
        ?int $brandId,
        ?int $categoryId,
        string $message
    ) {
        if ($scope === 'brand' && $brandId) {
            return redirect(
                'admin/mobile-models/brand/'
                    . $brandId
            )->with(
                'success',
                $message
            );
        }

        if (
            $scope === 'category'
            && $categoryId
        ) {
            return redirect(
                'admin/mobile-models/category/'
                    . $categoryId
            )->with(
                'success',
                $message
            );
        }

        if ($categoryId) {
            return redirect(
                'admin/mobile-models/category/'
                    . $categoryId
            )->with(
                'success',
                $message
            );
        }

        if ($brandId) {
            return redirect(
                'admin/mobile-models/brand/'
                    . $brandId
            )->with(
                'success',
                $message
            );
        }

        return redirect()
            ->back()
            ->with(
                'success',
                $message
            );
    }
}
