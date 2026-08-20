<?php

namespace App\Http\Controllers;

use App\Models\GradeMaster;
use App\Models\MobileBrand;
use App\Models\MobileProductCategory;
use App\Models\ProductGradeQuestion;
use App\Models\ProductGradeQuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class ProductGradeQuestionController extends Controller
{
    private array $allowedIconKeys = [
        'none',
        'touch',
        'connect',
        'vibration',
        'call',
        'face_scan',
        'home',
        'display',
        'camera',
        'sensor',
        'button',
        'speaker',
        'mic',
        'charge',
        'sim',
        'bent',
        's_pen',

        // MacBook / Notebook
        'keyboard_touchpad',
        'usb',
        'keyboard_eng',
        'touch_bar',
        'screen_edge',

        'other',
    ];

    private function allowedIconValidationKeys(): array
    {
        return array_merge(['', 'null'], $this->allowedIconKeys);
    }

    private function normalizeIconKey($iconKey): ?string
    {
        if ($iconKey === null) {
            return null;
        }

        $iconKey = trim((string) $iconKey);

        if ($iconKey === '' || $iconKey === 'null') {
            return null;
        }

        if (!in_array($iconKey, $this->allowedIconKeys, true)) {
            return null;
        }

        return $iconKey;
    }

    private function redirectAfterSave(ProductGradeQuestion $question, string $message)
    {
        $categoryId = $question->mobile_product_category_id;
        $brandId = $question->mobile_brand_id;

        if (!empty($categoryId) && !empty($brandId)) {
            return redirect('admin/product-grade-questions/by-category/' . $categoryId . '/brands/' . $brandId)
                ->with('success', $message);
        }

        if (!empty($categoryId)) {
            return redirect('admin/product-grade-questions/by-category/' . $categoryId . '/brands')
                ->with('success', $message);
        }

        return redirect('admin/product-grade-questions/by-category')
            ->with('success', $message);
    }
    public function indexByBrand($brand)
    {
        $brand = MobileBrand::query()->findOrFail($brand);

        $questions = ProductGradeQuestion::query()
            ->withCount('options')
            ->where('mobile_brand_id', $brand->id)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.product_grade_questions.index-by-brand', [
            'questions' => $questions,
            'brand' => $brand,
            'selectedBrand' => $brand,
        ]);
    }
    public function indexByCategory($category)
    {
        $categoryData = MobileProductCategory::query()
            ->findOrFail($category);

        $questions = ProductGradeQuestion::query()
            ->with(['brand', 'productCategory'])
            ->withCount('options')
            ->where('mobile_product_category_id', $categoryData->id)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.product_grade_questions.index_by_category', [
            'questions' => $questions,
            'category' => $categoryData,
            'selectedBrand' => null,
            'selectedCategory' => $categoryData,
        ]);
    }

    public function categoryLayer()
    {
        $categories = MobileProductCategory::query()
            ->select([
                'mobile_product_categories.*',
            ])
            ->withCount([
                'gradeQuestions as questions_count',
            ])
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.product_grade_questions.category-layer', [
            'categories' => $categories,
        ]);
    }

    public function brandLayer($category)
    {
        $category = MobileProductCategory::query()->findOrFail($category);

        $brands = MobileBrand::query()
            ->select([
                'mobile_brands.*',
            ])
            ->whereExists(function ($query) use ($category) {
                $query->select(DB::raw(1))
                    ->from('product_grade_questions')
                    ->whereColumn('product_grade_questions.mobile_brand_id', 'mobile_brands.id')
                    ->where('product_grade_questions.mobile_product_category_id', $category->id);
            })
            ->withCount([
                'gradeQuestions as questions_count' => function ($query) use ($category) {
                    $query->where('mobile_product_category_id', $category->id);
                },
            ])
            ->orderBy('name', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.product_grade_questions.brand-layer', [
            'category' => $category,
            'selectedCategory' => $category,
            'brands' => $brands,
        ]);
    }

    public function questionLayer($category, $brand)
    {
        $category = MobileProductCategory::query()->findOrFail($category);
        $brand = MobileBrand::query()->findOrFail($brand);

        $questions = ProductGradeQuestion::query()
            ->with(['productCategory', 'brand'])
            ->withCount('options')
            ->where('mobile_product_category_id', $category->id)
            ->where('mobile_brand_id', $brand->id)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.product_grade_questions.question-layer', [
            'category' => $category,
            'selectedCategory' => $category,
            'brand' => $brand,
            'selectedBrand' => $brand,
            'questions' => $questions,
        ]);
    }
    public function index()
    {
        $questions = ProductGradeQuestion::query()
            ->with(['brand', 'productCategory'])
            ->withCount('options')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.product_grade_questions.index', compact('questions'));
    }

    public function create()
    {
        $question = new ProductGradeQuestion();

        $grades = GradeMaster::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $brands = MobileBrand::query()
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        $categories = MobileProductCategory::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.product_grade_questions.form', [
            'question' => $question,
            'grades' => $grades,
            'brands' => $brands,
            'categories' => $categories,
            'mode' => 'create',
            'optionRows' => [
                [
                    'option_title' => '',
                    'icon_key' => null,
                    'grade_master_id' => '',
                    'sort_order' => 0,
                    'status' => 1,
                ]
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mobile_brand_id' => ['nullable', 'integer', 'exists:mobile_brands,id'],
            'mobile_product_category_id' => ['nullable', 'integer', 'exists:mobile_product_categories,id'],

            'question_title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'answer_type' => ['required', 'in:single,multiple'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'in:0,1'],

            'options' => ['required', 'array', 'min:1'],
            'options.*.option_title' => ['required', 'string', 'max:255'],
            'options.*.icon_key' => ['nullable', Rule::in($this->allowedIconValidationKeys())],
            'options.*.grade_master_id' => ['required', 'integer', 'exists:grade_masters,id'],
            'options.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'options.*.status' => ['nullable', 'in:0,1'],
        ], [
            'mobile_brand_id.exists' => 'ไม่พบแบรนด์สินค้าที่เลือก',
            'mobile_product_category_id.exists' => 'ไม่พบประเภทสินค้าที่เลือก',
            'question_title.required' => 'กรุณากรอกหัวข้อคำถาม',
            'answer_type.required' => 'กรุณาเลือกประเภทคำตอบ',
            'options.required' => 'กรุณาเพิ่มตัวเลือกอย่างน้อย 1 รายการ',
            'options.min' => 'กรุณาเพิ่มตัวเลือกอย่างน้อย 1 รายการ',
            'options.*.option_title.required' => 'กรุณากรอกชื่อตัวเลือก',
            'options.*.icon_key.in' => 'ไอคอนที่เลือกไม่ถูกต้อง',
            'options.*.grade_master_id.required' => 'กรุณาเลือกเกรดของตัวเลือก',
        ]);

        DB::beginTransaction();

        try {
            $question = ProductGradeQuestion::query()->create([
                'mobile_brand_id' => $validated['mobile_brand_id'] ?? null,
                'mobile_product_category_id' => $validated['mobile_product_category_id'] ?? null,
                'question_title' => trim($validated['question_title']),
                'description' => $request->input('description'),
                'answer_type' => $validated['answer_type'],
                'sort_order' => (int) ($validated['sort_order'] ?? 0),
                'status' => (int) $request->input('status', 1),
            ]);

            foreach ($validated['options'] as $row) {
                $iconKey = $this->normalizeIconKey($row['icon_key'] ?? null);

                $question->options()->create([
                    'option_title' => trim($row['option_title']),
                    'icon_key' => $iconKey,
                    'grade_master_id' => (int) $row['grade_master_id'],
                    'sort_order' => (int) ($row['sort_order'] ?? 0),
                    'status' => (int) ($row['status'] ?? 1),
                ]);
            }

            DB::commit();
            return $this->redirectAfterSave($question, 'เพิ่มชุดคำถามเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถเพิ่มชุดคำถามได้: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $question = ProductGradeQuestion::query()
            ->with([
                'brand',
                'productCategory',
                'options' => function ($query) {
                    $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
                }
            ])
            ->findOrFail($id);

        $grades = GradeMaster::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $brands = MobileBrand::query()
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        $categories = MobileProductCategory::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $optionRows = $question->options->map(function ($row) {
            return [
                'id' => $row->id,
                'option_title' => $row->option_title,
                'icon_key' => $this->normalizeIconKey($row->icon_key),
                'grade_master_id' => $row->grade_master_id,
                'sort_order' => $row->sort_order,
                'status' => (int) $row->status,
            ];
        })->toArray();

        if (empty($optionRows)) {
            $optionRows[] = [
                'option_title' => '',
                'icon_key' => null,
                'grade_master_id' => '',
                'sort_order' => 0,
                'status' => 1,
            ];
        }

        return view('admin.product_grade_questions.form', [
            'question' => $question,
            'grades' => $grades,
            'brands' => $brands,
            'categories' => $categories,
            'mode' => 'edit',
            'optionRows' => $optionRows,
        ]);
    }

    public function update(Request $request, $id)
    {
        $question = ProductGradeQuestion::query()
            ->with('options')
            ->findOrFail($id);

        $validated = $request->validate([
            'mobile_brand_id' => ['nullable', 'integer', 'exists:mobile_brands,id'],
            'mobile_product_category_id' => ['nullable', 'integer', 'exists:mobile_product_categories,id'],

            'question_title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'answer_type' => ['required', 'in:single,multiple'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'in:0,1'],

            'options' => ['required', 'array', 'min:1'],
            'options.*.id' => ['nullable', 'integer'],
            'options.*.option_title' => ['required', 'string', 'max:255'],
            'options.*.icon_key' => ['nullable', Rule::in($this->allowedIconValidationKeys())],
            'options.*.grade_master_id' => ['required', 'integer', 'exists:grade_masters,id'],
            'options.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'options.*.status' => ['nullable', 'in:0,1'],
        ], [
            'mobile_brand_id.exists' => 'ไม่พบแบรนด์สินค้าที่เลือก',
            'mobile_product_category_id.exists' => 'ไม่พบประเภทสินค้าที่เลือก',
            'question_title.required' => 'กรุณากรอกหัวข้อคำถาม',
            'answer_type.required' => 'กรุณาเลือกประเภทคำตอบ',
            'options.required' => 'กรุณาเพิ่มตัวเลือกอย่างน้อย 1 รายการ',
            'options.min' => 'กรุณาเพิ่มตัวเลือกอย่างน้อย 1 รายการ',
            'options.*.option_title.required' => 'กรุณากรอกชื่อตัวเลือก',
            'options.*.icon_key.in' => 'ไอคอนที่เลือกไม่ถูกต้อง',
            'options.*.grade_master_id.required' => 'กรุณาเลือกเกรดของตัวเลือก',
        ]);

        DB::beginTransaction();

        try {
            $question->update([
                'mobile_brand_id' => $validated['mobile_brand_id'] ?? null,
                'mobile_product_category_id' => $validated['mobile_product_category_id'] ?? null,
                'question_title' => trim($validated['question_title']),
                'description' => $request->input('description'),
                'answer_type' => $validated['answer_type'],
                'sort_order' => (int) ($validated['sort_order'] ?? 0),
                'status' => (int) $request->input('status', 1),
            ]);

            $keepIds = [];

            foreach ($validated['options'] as $row) {
                $iconKey = $this->normalizeIconKey($row['icon_key'] ?? null);

                if (!empty($row['id'])) {
                    $option = ProductGradeQuestionOption::query()
                        ->where('product_grade_question_id', $question->id)
                        ->where('id', $row['id'])
                        ->first();

                    if ($option) {
                        $option->update([
                            'option_title' => trim($row['option_title']),
                            'icon_key' => $iconKey,
                            'grade_master_id' => (int) $row['grade_master_id'],
                            'sort_order' => (int) ($row['sort_order'] ?? 0),
                            'status' => (int) ($row['status'] ?? 1),
                        ]);

                        $keepIds[] = $option->id;
                    }
                } else {
                    $newOption = $question->options()->create([
                        'option_title' => trim($row['option_title']),
                        'icon_key' => $iconKey,
                        'grade_master_id' => (int) $row['grade_master_id'],
                        'sort_order' => (int) ($row['sort_order'] ?? 0),
                        'status' => (int) ($row['status'] ?? 1),
                    ]);

                    $keepIds[] = $newOption->id;
                }
            }

            if (!empty($keepIds)) {
                ProductGradeQuestionOption::query()
                    ->where('product_grade_question_id', $question->id)
                    ->whereNotIn('id', $keepIds)
                    ->delete();
            } else {
                ProductGradeQuestionOption::query()
                    ->where('product_grade_question_id', $question->id)
                    ->delete();
            }

            DB::commit();

            return $this->redirectAfterSave($question, 'แก้ไขชุดคำถามเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถแก้ไขชุดคำถามได้: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:product_grade_questions,id'],
        ], [
            'id.required' => 'ไม่พบรหัสข้อมูลที่ต้องการลบ',
            'id.integer' => 'รหัสข้อมูลไม่ถูกต้อง',
            'id.exists' => 'ไม่พบข้อมูลชุดคำถาม',
        ]);

        DB::beginTransaction();

        try {
            $question = ProductGradeQuestion::query()->findOrFail($request->id);
            $question->delete();

            DB::commit();

            return redirect('admin/product-grade-questions')
                ->with('success', 'ลบชุดคำถามเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'ไม่สามารถลบชุดคำถามได้: ' . $e->getMessage());
        }
    }
}
