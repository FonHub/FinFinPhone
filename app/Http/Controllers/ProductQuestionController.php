<?php

namespace App\Http\Controllers;

use App\Models\MobileModel;
use App\Models\MobileProductCategory;
use App\Models\ProductQuestion;
use App\Models\ProductQuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class ProductQuestionController extends Controller
{
    public function indexByCategory($category)
    {
        $categoryData = MobileProductCategory::query()
            ->findOrFail($category);

        $questions = ProductQuestion::query()
            ->with(['productCategory'])
            ->withCount('answers')
            ->where('mobile_product_category_id', $categoryData->id)
            ->orderBy('sort_order', 'asc')
            ->orderByDesc('id')
            ->get();

        return view('admin.product_questions.index_by_category', [
            'questions' => $questions,
            'category' => $categoryData,
            'selectedCategory' => $categoryData,
        ]);
    }

    public function index()
    {
        $questions = ProductQuestion::query()
            ->with(['productCategory'])
            ->withCount('answers')
            ->orderBy('sort_order', 'asc')
            ->orderByDesc('id')
            ->get();

        return view('admin.product_questions.index', [
            'questions' => $questions,
        ]);
    }

    public function create(Request $request)
    {
        $question = new ProductQuestion();

        if ($request->filled('mobile_product_category_id')) {
            $question->mobile_product_category_id = (int) $request->mobile_product_category_id;
        }

        $categories = MobileProductCategory::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $mobileModels = MobileModel::query()
            ->with(['brand', 'productCategory'])
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.product_questions.form', [
            'question' => $question,
            'categories' => $categories,
            'mobileModels' => $mobileModels,
            'mode' => 'create',
            'answerRows' => [
                [
                    'mobile_model_id' => '',
                    'answer' => '',
                    'sort_order' => 0,
                    'status' => 1,
                ],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mobile_product_category_id' => ['nullable', 'integer', 'exists:mobile_product_categories,id'],

            'question' => ['required', 'string', 'max:255'],
            'question_type' => [
                'required',
                Rule::in([
                    ProductQuestion::TYPE_GENERAL,
                    ProductQuestion::TYPE_MODEL_SPECIFIC,
                ]),
            ],
            'general_answer' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'in:0,1'],

            'answers' => ['nullable', 'array'],
            'answers.*.mobile_model_id' => ['nullable', 'integer', 'exists:mobile_models,id'],
            'answers.*.answer' => ['nullable', 'string'],
            'answers.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'answers.*.status' => ['nullable', 'in:0,1'],
        ], [
            'mobile_product_category_id.exists' => 'ไม่พบประเภทสินค้าที่เลือก',
            'question.required' => 'กรุณากรอกคำถาม',
            'question.max' => 'คำถามต้องไม่เกิน 255 ตัวอักษร',
            'question_type.required' => 'กรุณาเลือกประเภทคำถาม',
            'question_type.in' => 'ประเภทคำถามไม่ถูกต้อง',
            'sort_order.integer' => 'ลำดับต้องเป็นตัวเลข',
            'sort_order.min' => 'ลำดับต้องไม่น้อยกว่า 0',
            'status.in' => 'สถานะไม่ถูกต้อง',
            'answers.*.mobile_model_id.exists' => 'ไม่พบโมเดลสินค้าที่เลือก',
            'answers.*.sort_order.integer' => 'ลำดับคำตอบต้องเป็นตัวเลข',
            'answers.*.sort_order.min' => 'ลำดับคำตอบต้องไม่น้อยกว่า 0',
            'answers.*.status.in' => 'สถานะคำตอบไม่ถูกต้อง',
        ]);

        $answerRows = $this->normalizeAnswerRows(
            $request->input('question_type'),
            $request->input('answers', [])
        );

        DB::beginTransaction();

        try {
            $question = ProductQuestion::query()->create([
                'mobile_product_category_id' => $validated['mobile_product_category_id'] ?? null,
                'question' => trim($validated['question']),
                'question_type' => $validated['question_type'],
                'general_answer' => $validated['question_type'] === ProductQuestion::TYPE_GENERAL
                    ? trim((string) $request->input('general_answer', ''))
                    : null,
                'sort_order' => (int) ($validated['sort_order'] ?? 0),
                'status' => (int) $request->input('status', 1),
            ]);

            if ($validated['question_type'] === ProductQuestion::TYPE_MODEL_SPECIFIC) {
                foreach ($answerRows as $row) {
                    $question->answers()->create([
                        'mobile_model_id' => $row['mobile_model_id'],
                        'answer' => $row['answer'],
                        'sort_order' => $row['sort_order'],
                        'status' => $row['status'],
                    ]);
                }
            }

            DB::commit();

            if (!empty($validated['mobile_product_category_id'])) {
                return redirect('admin/mobile-product-categories/' . $validated['mobile_product_category_id'] . '/product-questions')
                    ->with('success', 'เพิ่มคำถามสินค้าเรียบร้อยแล้ว');
            }

            return redirect('admin/product-questions')
                ->with('success', 'เพิ่มคำถามสินค้าเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถเพิ่มคำถามสินค้าได้: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $question = ProductQuestion::query()
            ->with([
                'productCategory',
                'answers.mobileModel.brand',
                'answers.mobileModel.productCategory',
            ])
            ->findOrFail($id);

        $categories = MobileProductCategory::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $mobileModels = MobileModel::query()
            ->with(['brand', 'productCategory'])
            ->orderBy('name', 'asc')
            ->get();

        $answerRows = $question->answers->map(function ($row) {
            return [
                'id' => $row->id,
                'mobile_model_id' => $row->mobile_model_id,
                'answer' => $row->answer,
                'sort_order' => $row->sort_order,
                'status' => (int) $row->status,
            ];
        })->toArray();

        if (empty($answerRows)) {
            $answerRows[] = [
                'mobile_model_id' => '',
                'answer' => '',
                'sort_order' => 0,
                'status' => 1,
            ];
        }

        return view('admin.product_questions.form', [
            'question' => $question,
            'categories' => $categories,
            'mobileModels' => $mobileModels,
            'mode' => 'edit',
            'answerRows' => $answerRows,
        ]);
    }

    public function update(Request $request, $id)
    {
        $question = ProductQuestion::query()
            ->with('answers')
            ->findOrFail($id);

        $validated = $request->validate([
            'mobile_product_category_id' => ['nullable', 'integer', 'exists:mobile_product_categories,id'],

            'question' => ['required', 'string', 'max:255'],
            'question_type' => [
                'required',
                Rule::in([
                    ProductQuestion::TYPE_GENERAL,
                    ProductQuestion::TYPE_MODEL_SPECIFIC,
                ]),
            ],
            'general_answer' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'in:0,1'],

            'answers' => ['nullable', 'array'],
            'answers.*.id' => ['nullable', 'integer'],
            'answers.*.mobile_model_id' => ['nullable', 'integer', 'exists:mobile_models,id'],
            'answers.*.answer' => ['nullable', 'string'],
            'answers.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'answers.*.status' => ['nullable', 'in:0,1'],
        ], [
            'mobile_product_category_id.exists' => 'ไม่พบประเภทสินค้าที่เลือก',
            'question.required' => 'กรุณากรอกคำถาม',
            'question.max' => 'คำถามต้องไม่เกิน 255 ตัวอักษร',
            'question_type.required' => 'กรุณาเลือกประเภทคำถาม',
            'question_type.in' => 'ประเภทคำถามไม่ถูกต้อง',
            'sort_order.integer' => 'ลำดับต้องเป็นตัวเลข',
            'sort_order.min' => 'ลำดับต้องไม่น้อยกว่า 0',
            'status.in' => 'สถานะไม่ถูกต้อง',
            'answers.*.mobile_model_id.exists' => 'ไม่พบโมเดลสินค้าที่เลือก',
            'answers.*.sort_order.integer' => 'ลำดับคำตอบต้องเป็นตัวเลข',
            'answers.*.sort_order.min' => 'ลำดับคำตอบต้องไม่น้อยกว่า 0',
            'answers.*.status.in' => 'สถานะคำตอบไม่ถูกต้อง',
        ]);

        $answerRows = $this->normalizeAnswerRows(
            $request->input('question_type'),
            $request->input('answers', [])
        );

        DB::beginTransaction();

        try {
            $question->update([
                'mobile_product_category_id' => $validated['mobile_product_category_id'] ?? null,
                'question' => trim($validated['question']),
                'question_type' => $validated['question_type'],
                'general_answer' => $validated['question_type'] === ProductQuestion::TYPE_GENERAL
                    ? trim((string) $request->input('general_answer', ''))
                    : null,
                'sort_order' => (int) ($validated['sort_order'] ?? 0),
                'status' => (int) $request->input('status', 1),
            ]);

            if ($validated['question_type'] === ProductQuestion::TYPE_GENERAL) {
                ProductQuestionAnswer::query()
                    ->where('product_question_id', $question->id)
                    ->delete();
            } else {
                $keepIds = [];

                foreach ($answerRows as $row) {
                    if (!empty($row['id'])) {
                        $answer = ProductQuestionAnswer::query()
                            ->where('product_question_id', $question->id)
                            ->where('id', $row['id'])
                            ->first();

                        if ($answer) {
                            $answer->update([
                                'mobile_model_id' => $row['mobile_model_id'],
                                'answer' => $row['answer'],
                                'sort_order' => $row['sort_order'],
                                'status' => $row['status'],
                            ]);

                            $keepIds[] = $answer->id;
                        }
                    } else {
                        $newAnswer = $question->answers()->create([
                            'mobile_model_id' => $row['mobile_model_id'],
                            'answer' => $row['answer'],
                            'sort_order' => $row['sort_order'],
                            'status' => $row['status'],
                        ]);

                        $keepIds[] = $newAnswer->id;
                    }
                }

                if (!empty($keepIds)) {
                    ProductQuestionAnswer::query()
                        ->where('product_question_id', $question->id)
                        ->whereNotIn('id', $keepIds)
                        ->delete();
                } else {
                    ProductQuestionAnswer::query()
                        ->where('product_question_id', $question->id)
                        ->delete();
                }
            }

            DB::commit();

            if (!empty($validated['mobile_product_category_id'])) {
                return redirect('admin/mobile-product-categories/' . $validated['mobile_product_category_id'] . '/product-questions')
                    ->with('success', 'แก้ไขคำถามสินค้าเรียบร้อยแล้ว');
            }

            return redirect('admin/product-questions')
                ->with('success', 'แก้ไขคำถามสินค้าเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถแก้ไขคำถามสินค้าได้: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:product_questions,id'],
        ], [
            'id.required' => 'ไม่พบรหัสข้อมูลที่ต้องการลบ',
            'id.integer' => 'รหัสข้อมูลไม่ถูกต้อง',
            'id.exists' => 'ไม่พบข้อมูลคำถามสินค้า',
        ]);

        DB::beginTransaction();

        try {
            $question = ProductQuestion::query()->findOrFail($request->id);
            $categoryId = $question->mobile_product_category_id;

            $question->delete();

            DB::commit();

            if (!empty($categoryId)) {
                return redirect('admin/mobile-product-categories/' . $categoryId . '/product-questions')
                    ->with('success', 'ลบคำถามสินค้าเรียบร้อยแล้ว');
            }

            return redirect('admin/product-questions')
                ->with('success', 'ลบคำถามสินค้าเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'ไม่สามารถลบคำถามสินค้าได้: ' . $e->getMessage());
        }
    }

    private function normalizeAnswerRows(string $questionType, array $answers): array
    {
        if ($questionType !== ProductQuestion::TYPE_MODEL_SPECIFIC) {
            return [];
        }

        $rows = [];
        $modelCheck = [];

        foreach ($answers as $row) {
            $mobileModelId = isset($row['mobile_model_id']) ? (int) $row['mobile_model_id'] : 0;
            $answerText = trim((string) ($row['answer'] ?? ''));
            $sortOrder = isset($row['sort_order']) ? (int) $row['sort_order'] : 0;
            $status = isset($row['status']) ? (int) $row['status'] : 1;
            $id = isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null;

            if ($mobileModelId <= 0 && $answerText === '') {
                continue;
            }

            if ($mobileModelId <= 0) {
                throw new \RuntimeException('กรุณาเลือกโมเดลสินค้าให้ครบทุกแถวของคำตอบ');
            }

            if ($answerText === '') {
                throw new \RuntimeException('กรุณากรอกคำตอบให้ครบทุกแถว');
            }

            if (isset($modelCheck[$mobileModelId])) {
                throw new \RuntimeException('เลือกโมเดลสินค้าซ้ำในคำตอบของคำถามเดียวกัน');
            }

            $modelCheck[$mobileModelId] = true;

            $rows[] = [
                'id' => $id,
                'mobile_model_id' => $mobileModelId,
                'answer' => $answerText,
                'sort_order' => $sortOrder,
                'status' => $status,
            ];
        }

        if (empty($rows)) {
            throw new \RuntimeException('คำถามแบบเฉพาะโมเดลต้องมีคำตอบอย่างน้อย 1 รายการ');
        }

        return $rows;
    }
}