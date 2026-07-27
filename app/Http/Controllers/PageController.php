<?php

namespace App\Http\Controllers;

use App\Models\AboutPageSetting;
use App\Models\BonusCode;
use App\Models\BonusCodeUsage;
use App\Models\HomeBanner;
use App\Models\MobileBrand;
use App\Models\MobileModel;
use App\Models\MobileModelPrice;
use App\Models\MobileProductCategory;
use App\Models\News;
use App\Models\ProductGradeQuestion;
use App\Models\ProductGradeQuestionOption;
use App\Models\SellMethod;
use App\Models\ServiceArea;
use App\Models\ServiceTimeSlot;
use App\Models\SupportPage;
use App\Models\SaleDetailSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class PageController extends Controller
{
    public function home()
    {
        $banner = HomeBanner::query()
            ->get();

        $categories = MobileProductCategory::query()
            ->with([
                'selectedIcon',
                'mobileModels' => function ($query) {
                    $query->with([
                        'brand',
                        'prices' => function ($priceQuery) {
                            $priceQuery->where('status', 1)
                                ->orderBy('id', 'asc');
                        },
                    ])
                        ->where('status', 1)
                        ->orderBy('name', 'asc');
                },
            ])
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'category_id' => $category->id,
                    'key' => (string) $category->id,

                    'name' => $category->category_name,
                    'label_th' => $category->category_name,

                    'icon_default' => $category->selectedIcon->icon_default ?? 'assets/media/icons/checked.gif',
                    'icon_active' => $category->selectedIcon->icon_active ?? 'assets/media/icons/checked-01.gif',

                    'models' => $category->mobileModels->map(function ($model) {
                        return [
                            'id' => $model->id,
                            'name' => $model->name,

                            'mobile_brand_id' => $model->mobile_brand_id,
                            'brand_name' => $model->brand->name ?? '-',

                            'mobile_product_category_id' => $model->mobile_product_category_id,

                            'capacities' => $model->prices->map(function ($price) {
                                return [
                                    'id' => $price->id,
                                    'capacity' => $price->capacity,
                                    'base_price' => $price->base_price,
                                    'min_price' => $price->min_price,
                                ];
                            })->values()->toArray(),
                        ];
                    })->values()->toArray(),
                ];
            })
            ->values()
            ->toArray();

        $activeKey = $categories[0]['key'] ?? '';

        $specialPrivileges = BonusCode::query()
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->where(function ($query) {
                $query->whereNull('usage_limit')
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->orderByDesc('id')
            ->get()
            ->map(function ($bonusCode) {
                if ($bonusCode->bonus_type === BonusCode::TYPE_PERCENT) {
                    $bonusText = number_format((float) $bonusCode->bonus_value, 0) . '%';

                    if ($bonusCode->max_bonus_amount !== null) {
                        $bonusText .= ' สูงสุด ' . number_format((float) $bonusCode->max_bonus_amount, 0) . '฿';
                    }
                } else {
                    $bonusText = '+' . number_format((float) $bonusCode->bonus_value, 0) . '฿';
                }

                return [
                    'id' => $bonusCode->id,
                    'title' => $bonusCode->name,
                    'bonus' => $bonusText,
                    'code' => $bonusCode->code,
                    'description' => $bonusCode->description,
                    'min_estimate_price' => $bonusCode->min_estimate_price,
                    'per_user_limit' => $bonusCode->per_user_limit,
                ];
            })
            ->values()
            ->toArray();



        $saleDetailSection = SaleDetailSection::with([
            'tabs' => function ($query) {
                $query->where('status', 'active')
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('id', 'asc');
            },
            'tabs.steps' => function ($query) {
                $query->where('status', 'active')
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('id', 'asc');
            },
        ])
            ->where('page_key', 'product_sale_detail')
            ->where('status', 'active')
            ->first();

        /*
    |--------------------------------------------------------------------------
    | รีวิวจากตาราง sell_order_reviews
    |--------------------------------------------------------------------------
    */
        $reviews = DB::table('sell_order_reviews')
            ->leftJoin('sell_orders', 'sell_order_reviews.sell_order_id', '=', 'sell_orders.id')
            ->select([
                'sell_order_reviews.id',
                'sell_order_reviews.sell_order_id',
                'sell_order_reviews.customer_name',
                'sell_order_reviews.customer_phone',
                'sell_order_reviews.rating',
                'sell_order_reviews.title',
                'sell_order_reviews.comment',
                'sell_order_reviews.image',
                'sell_order_reviews.created_at',

                'sell_orders.order_no',
                'sell_orders.summary_title',
                'sell_orders.brand_name',
                'sell_orders.model_name',
                'sell_orders.capacity',
            ])
            ->where('sell_order_reviews.is_displayed', 1)
            ->where('sell_order_reviews.is_active', 1)
            ->whereNull('sell_order_reviews.deleted_at')
            ->orderByDesc('sell_order_reviews.created_at')
            ->limit(12)
            ->get()
            ->map(function ($review) {
                $orderTitle = $review->summary_title;

                if (empty($orderTitle)) {
                    $orderTitle = trim(
                        ($review->brand_name ?? '') . ' ' .
                            ($review->model_name ?? '') . ' ' .
                            ($review->capacity ?? '')
                    );
                }

                $phone = preg_replace('/\D+/', '', (string) ($review->customer_phone ?? ''));

                if (strlen($phone) >= 7) {
                    $maskedPhone = substr($phone, 0, 3) . 'xxx' . substr($phone, -3);
                } elseif (!empty($phone)) {
                    $maskedPhone = substr($phone, 0, 2) . 'xxx';
                } else {
                    $maskedPhone = 'สมาชิก Cashkub';
                }

                $review->order_title = $orderTitle ?: ($review->order_no ?? 'คำสั่งขาย');
                $review->display_phone = $maskedPhone;
                $review->display_name = !empty($review->customer_name)
                    ? $review->customer_name
                    : $maskedPhone;
                $review->rating = max(1, min(5, (int) ($review->rating ?? 5)));

                return $review;
            });

        return view('pages.home', [
            'banner' => $banner,
            'categories' => $categories,
            'activeKey' => $activeKey,
            'specialPrivileges' => $specialPrivileges,
            'reviews' => $reviews,
            'saleDetailSection' => $saleDetailSection,
        ]);
    }
    public function sellProduct()
    {
        $banner = HomeBanner::query()
            ->get();

        $categories = MobileProductCategory::query()
            ->with([
                'selectedIcon',
                'mobileModels' => function ($query) {
                    $query->with([
                        'brand',
                        'prices' => function ($priceQuery) {
                            $priceQuery->where('status', 1)
                                ->orderBy('id', 'asc');
                        },
                    ])
                        ->where('status', 1)
                        ->orderBy('name', 'asc');
                },
            ])
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'category_id' => $category->id,
                    'key' => (string) $category->id,

                    'name' => $category->category_name,
                    'label_th' => $category->category_name,

                    'icon_default' => $category->selectedIcon->icon_default ?? 'assets/media/icons/checked.gif',
                    'icon_active' => $category->selectedIcon->icon_active ?? 'assets/media/icons/checked-01.gif',

                    'models' => $category->mobileModels->map(function ($model) {
                        return [
                            'id' => $model->id,
                            'name' => $model->name,

                            'mobile_brand_id' => $model->mobile_brand_id,
                            'brand_name' => $model->brand->name ?? '-',

                            'mobile_product_category_id' => $model->mobile_product_category_id,

                            'capacities' => $model->prices->map(function ($price) {
                                return [
                                    'id' => $price->id,
                                    'capacity' => $price->capacity,
                                    'base_price' => $price->base_price,
                                    'min_price' => $price->min_price,
                                ];
                            })->values()->toArray(),
                        ];
                    })->values()->toArray(),
                ];
            })
            ->values()
            ->toArray();

        $activeKey = $categories[0]['key'] ?? '';

        $saleDetailSection = SaleDetailSection::with([
            'tabs' => function ($query) {
                $query->where('status', 'active')
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('id', 'asc');
            },
            'tabs.steps' => function ($query) {
                $query->where('status', 'active')
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('id', 'asc');
            },
        ])
            ->where('page_key', 'product_sale_detail')
            ->where('status', 'active')
            ->first();

        /*
    |--------------------------------------------------------------------------
    | รีวิวจากคำสั่งขายจริง
    |--------------------------------------------------------------------------
    | ใช้เฉพาะรีวิวที่เปิดแสดงและ active
    |--------------------------------------------------------------------------
    */
        $reviews = DB::table('sell_order_reviews')
            ->leftJoin('sell_orders', 'sell_order_reviews.sell_order_id', '=', 'sell_orders.id')
            ->select([
                'sell_order_reviews.id',
                'sell_order_reviews.sell_order_id',
                'sell_order_reviews.customer_name',
                'sell_order_reviews.customer_phone',
                'sell_order_reviews.rating',
                'sell_order_reviews.title',
                'sell_order_reviews.comment',
                'sell_order_reviews.image',
                'sell_order_reviews.created_at',

                'sell_orders.order_no',
                'sell_orders.summary_title',
                'sell_orders.brand_name',
                'sell_orders.model_name',
                'sell_orders.capacity',
            ])
            ->where('sell_order_reviews.is_displayed', 1)
            ->where('sell_order_reviews.is_active', 1)
            ->whereNull('sell_order_reviews.deleted_at')
            ->orderByDesc('sell_order_reviews.created_at')
            ->limit(12)
            ->get()
            ->map(function ($review) {
                $orderTitle = $review->summary_title;

                if (empty($orderTitle)) {
                    $orderTitle = trim(
                        ($review->brand_name ?? '') . ' ' .
                            ($review->model_name ?? '') . ' ' .
                            ($review->capacity ?? '')
                    );
                }

                $phone = preg_replace('/\D+/', '', (string) ($review->customer_phone ?? ''));

                if (strlen($phone) >= 7) {
                    $maskedPhone = substr($phone, 0, 3) . 'xxx' . substr($phone, -3);
                } elseif (!empty($phone)) {
                    $maskedPhone = substr($phone, 0, 2) . 'xxx';
                } else {
                    $maskedPhone = 'สมาชิก Cashkub';
                }

                $review->order_title = $orderTitle ?: ($review->order_no ?? 'คำสั่งขาย');
                $review->display_name = !empty($review->customer_name)
                    ? $review->customer_name
                    : $maskedPhone;
                $review->display_phone = $maskedPhone;
                $review->rating = max(1, min(5, (int) ($review->rating ?? 5)));

                return $review;
            });

        return view('pages.sell-product', [
            'banner' => $banner,
            'categories' => $categories,
            'activeKey' => $activeKey,
            'reviews' => $reviews,
            'saleDetailSection' => $saleDetailSection,

        ]);
    }

    public function sellProductEstimate(Request $request)
    {
        $categoryId = $request->get('mobile_product_category_id');
        $brandId = $request->get('mobile_brand_id');
        $modelId = $request->get('mobile_model_id');
        $priceId = $request->get('mobile_model_price_id');

        $categoryData = MobileProductCategory::query()
            ->where('status', 1)
            ->find($categoryId);

        $brandData = MobileBrand::query()
            ->where('status', 1)
            ->find($brandId);

        $modelData = MobileModel::query()
            ->with([
                'brand',
                'productCategory',
                'gradePrices',
            ])
            ->where('status', 1)
            ->find($modelId);

        $priceData = MobileModelPrice::query()
            ->where('status', 1)
            ->find($priceId);

        if (!$categoryData || !$brandData || !$modelData || !$priceData) {
            return redirect()
                ->route('sell.product')
                ->with('error', 'กรุณาเลือกข้อมูลสินค้าให้ครบถ้วน');
        }

        if ((int) $modelData->mobile_product_category_id !== (int) $categoryData->id) {
            return redirect()
                ->route('sell.product')
                ->with('error', 'รุ่นสินค้าที่เลือกไม่ตรงกับประเภทสินค้า');
        }

        if ((int) $modelData->mobile_brand_id !== (int) $brandData->id) {
            return redirect()
                ->route('sell.product')
                ->with('error', 'รุ่นสินค้าที่เลือกไม่ตรงกับแบรนด์สินค้า');
        }

        if ((int) $priceData->mobile_model_id !== (int) $modelData->id) {
            return redirect()
                ->route('sell.product')
                ->with('error', 'ความจุที่เลือกไม่ตรงกับรุ่นสินค้า');
        }

        $category = $categoryData->category_name;
        $brand = $brandData->name;
        $model = $modelData->name;
        $storage = $priceData->capacity;

        $estimatePrice = (float) $priceData->base_price;
        $minPrice = (float) $priceData->min_price;

        $summaryTitle = trim($brand . ' ' . $model . ' ' . $storage);

        /*
    |--------------------------------------------------------------------------
    | ดึงคำถามคัดเกรดจริงจากหลังบ้าน
    |--------------------------------------------------------------------------
    | เงื่อนไข:
    | - คำถามเปิดใช้งาน
    | - ตรงกับประเภทสินค้า
    | - มีตัวเลือกคำตอบที่เปิดใช้งาน
    */
        $gradeQuestions = ProductGradeQuestion::query()
            ->with([
                'options' => function ($query) {
                    $query->where('status', 1)
                        ->orderBy('sort_order', 'asc')
                        ->orderBy('id', 'asc');
                },
                'options.grade',
            ])
            ->where('status', 1)
            ->where('mobile_product_category_id', $categoryData->id)
            ->where('mobile_brand_id', $brandData->id)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->filter(function ($question) {
                return $question->options->count() > 0;
            })
            ->values();

        /*
    |--------------------------------------------------------------------------
    | Map ราคาหักตามเกรดของรุ่นนี้
    |--------------------------------------------------------------------------
    | key = grade_master_id
    | value = deduct_price
    */
        $gradeDeductMap = $modelData->gradePrices
            ->mapWithKeys(function ($row) {
                return [
                    (string) $row->grade_master_id => (float) $row->deduct_price,
                ];
            })
            ->toArray();

        /*
    |--------------------------------------------------------------------------
    | ส่งคำถามไปหน้าเว็บ
    |--------------------------------------------------------------------------
    | หน้า Blade จะใช้ $sections นี้เท่านั้น
    | ไม่ใช้คำถาม static แล้ว
    */
        $sections = $gradeQuestions->map(function ($question, $index) use ($gradeDeductMap) {
            return [
                'id' => $question->id,
                'key' => 'question_' . $question->id,
                'title' => ($index + 2) . '. ' . $question->question_title,
                'answer_type' => $question->answer_type ?: 'single',
                'description' => $question->description,
                'fields' => $question->options->map(function ($option) use ($gradeDeductMap) {
                    $gradeId = (string) $option->grade_master_id;

                    return [
                        'id' => $option->id,
                        'label' => $option->option_title,
                        'icon_key' => $option->icon_key ?? null,
                        'grade_master_id' => $option->grade_master_id,
                        'grade_name' => $option->grade->grade_name ?? null,
                        'deduct_price' => $gradeDeductMap[$gradeId] ?? 0,
                    ];
                })->values()->toArray(),
            ];
        })->values()->toArray();

        /*
    |--------------------------------------------------------------------------
    | ผู้ใช้ที่ login อยู่
    |--------------------------------------------------------------------------
    | ใช้สำหรับแสดงโค้ดบวกราคาเฉพาะสมาชิกหน้าบ้านเท่านั้น
    | ไม่เอา id ของแอดมินมาใช้กับ bonus/user_id
    */
        $userId = null;

        if (Auth::check()) {
            $currentUserId = Auth::id();

            $isFrontendUser = DB::table('users')
                ->where('id', $currentUserId)
                ->where('status', 1)
                ->where('is_super_admin', 0)
                ->exists();

            if ($isFrontendUser) {
                $userId = $currentUserId;
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Bonus Code
    |--------------------------------------------------------------------------
    | ถ้าไม่ login หรือไม่ใช่สมาชิกหน้าบ้าน จะไม่ให้ใช้โค้ด
    */
        $bonusCodes = [];

        if ($userId) {
            $bonusCodes = BonusCode::query()
                ->where('status', 1)
                ->where(function ($query) {
                    $query->whereNull('starts_at')
                        ->orWhere('starts_at', '<=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('ends_at')
                        ->orWhere('ends_at', '>=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('usage_limit')
                        ->orWhereColumn('used_count', '<', 'usage_limit');
                })
                ->where(function ($query) use ($estimatePrice) {
                    $query->whereNull('min_estimate_price')
                        ->orWhere('min_estimate_price', '<=', $estimatePrice);
                })
                ->orderByDesc('id')
                ->get()
                ->filter(function ($bonusCode) use ($userId) {
                    if ($bonusCode->per_user_limit === null) {
                        return true;
                    }

                    $usedByUser = BonusCodeUsage::query()
                        ->where('bonus_code_id', $bonusCode->id)
                        ->where('user_id', $userId)
                        ->count();

                    return $usedByUser < (int) $bonusCode->per_user_limit;
                })
                ->map(function ($bonusCode) {
                    return [
                        'id' => $bonusCode->id,
                        'code' => $bonusCode->code,
                        'name' => $bonusCode->name,
                        'bonus_type' => $bonusCode->bonus_type,
                        'bonus_value' => (float) $bonusCode->bonus_value,
                        'max_bonus_amount' => $bonusCode->max_bonus_amount !== null
                            ? (float) $bonusCode->max_bonus_amount
                            : null,
                        'min_estimate_price' => (float) $bonusCode->min_estimate_price,
                        'usage_limit' => $bonusCode->usage_limit,
                        'per_user_limit' => $bonusCode->per_user_limit,
                        'description' => $bonusCode->description,
                    ];
                })
                ->values()
                ->toArray();
        }

        return view('pages.sell-product-estimate', [
            'category' => $category,
            'brand' => $brand,
            'model' => $model,
            'storage' => $storage,
            'summaryTitle' => $summaryTitle,

            'categoryData' => $categoryData,
            'brandData' => $brandData,
            'modelData' => $modelData,
            'priceData' => $priceData,

            'mobileProductCategoryId' => $categoryData->id,
            'mobileBrandId' => $brandData->id,
            'mobileModelId' => $modelData->id,
            'mobileModelPriceId' => $priceData->id,

            'estimatePrice' => $estimatePrice,
            'minPrice' => $minPrice,

            'sections' => $sections,

            'bonusCodes' => $bonusCodes,
            'canUseBonusCode' => $userId !== null,
            'icon_key' => $option->icon_key ?? null,
        ]);
    }
    public function articles(Request $request)
    {
        $pagedArticles = News::query()
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('pages.articles', compact('pagedArticles'));
    }

    public function articleDetail($slug)
    {
        $article = News::query()
            ->where('slug', $slug)
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->firstOrFail();

        $relatedArticles = News::query()
            ->where('id', '!=', $article->id)
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return view('pages.article-detail', compact('article', 'relatedArticles'));
    }
    public function faq()
    {
        $banner = HomeBanner::query()
            ->get();

        /*
    |--------------------------------------------------------------------------
    | FAQ หน้าบ้าน
    |--------------------------------------------------------------------------
    | ดึงคำถามจาก product_questions
    | - general ใช้ general_answer
    | - model_specific ใช้คำตอบจาก product_question_answers แยกตามโมเดล
    |--------------------------------------------------------------------------
    */
        $faqCategories = DB::table('mobile_product_categories')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($category) {
                $questions = DB::table('product_questions')
                    ->where('mobile_product_category_id', $category->id)
                    ->where('status', 1)
                    ->whereNotNull('question')
                    ->where('question', '!=', '')
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('id', 'asc')
                    ->get()
                    ->map(function ($question) {
                        $questionType = $question->question_type ?? 'general';

                        $modelAnswers = collect();

                        if ($questionType === 'model_specific') {
                            $modelAnswers = DB::table('product_question_answers')
                                ->leftJoin('mobile_models', 'product_question_answers.mobile_model_id', '=', 'mobile_models.id')
                                ->leftJoin('mobile_brands', 'mobile_models.mobile_brand_id', '=', 'mobile_brands.id')
                                ->leftJoin('mobile_product_categories', 'mobile_models.mobile_product_category_id', '=', 'mobile_product_categories.id')
                                ->where('product_question_answers.product_question_id', $question->id)
                                ->where('product_question_answers.status', 1)
                                ->whereNotNull('product_question_answers.answer')
                                ->where('product_question_answers.answer', '!=', '')
                                ->select([
                                    'product_question_answers.id',
                                    'product_question_answers.mobile_model_id',
                                    'product_question_answers.answer',
                                    'product_question_answers.sort_order',

                                    'mobile_models.name as model_name',
                                    'mobile_brands.name as brand_name',
                                    'mobile_product_categories.category_name',
                                ])
                                ->orderBy('product_question_answers.sort_order', 'asc')
                                ->orderBy('mobile_brands.name', 'asc')
                                ->orderBy('mobile_models.name', 'asc')
                                ->get()
                                ->map(function ($answer) {
                                    $modelTitle = trim(
                                        ($answer->brand_name ?? '') . ' ' .
                                            ($answer->model_name ?? '')
                                    );

                                    $answer->model_title = $modelTitle ?: 'ไม่ระบุรุ่น';

                                    return $answer;
                                });
                        }

                        $question->question_type = $questionType;
                        $question->model_answers = $modelAnswers;

                        return $question;
                    })
                    ->filter(function ($question) {
                        if (($question->question_type ?? 'general') === 'model_specific') {
                            return $question->model_answers->count() > 0;
                        }

                        return !empty($question->general_answer);
                    })
                    ->values();

                return (object) [
                    'id' => $category->id,
                    'category_name' => $category->category_name,
                    'questions' => $questions,
                ];
            })
            ->filter(function ($category) {
                return $category->questions->count() > 0;
            })
            ->values();

        return view('pages.faq', compact('banner', 'faqCategories'));
    }

    public function about()
    {
        $about = AboutPageSetting::query()->first();

        if (!$about) {
            $about = new AboutPageSetting([
                'hero_title' => 'เกี่ยวกับเรา',
                'hero_subtitle' => 'แพลตฟอร์มรับซื้อสินค้ามือสองที่สะดวก รวดเร็ว และเชื่อถือได้',
                'hero_background_color' => '#DFF3EA',

                'about_section_title' => 'เกี่ยวกับเรา',
                'about_company_title' => 'FinFin Phone.com',
                'about_description' => 'เป็นผู้ให้บริการแพลตฟอร์มออนไลน์ รับซื้อโทรศัพท์ทุกรุ่น ทุกยี่ห้อ iPad Macbook มือสองผ่านทางเว็บไซต์ โดยการันตีให้ราคาสูง รับซื้อเครื่องทุกสภาพ ลูกค้าสามารถเช็คราคาสินค้าที่จะขาย รู้ราคาภายใน 1 นาที และสามารถทำการขายได้ทันที โดยมีเจ้าหน้าที่ไปรับสินค้าถึงหน้าบ้าน หรือสถานที่ที่ลูกค้าสะดวกนัดหมาย เมื่อพนักงานตรวจเช็ครับสินค้า ทางบริษัทจ่ายเงินให้ลูกค้าทันที ฟรีค่าบริการ',

                'why_choose_title' => 'ทำไมถึงเลือกเรา',
                'why_choose_description' => 'ขายโทรศัพท์ได้ง่ายๆ ราบรื่น ตั้งแต่การตรวจสอบสภาพโทรศัพท์ฟรี ไปจนถึงการบริการถึงบ้านที่สะดวกรวดเร็วที่สุด',

                'feature_1_title' => 'ขั้นตอนง่าย',
                'feature_1_description' => 'เลือกสินค้า ประเมินราคา และส่งคำขอขายได้ทันที',

                'feature_2_title' => 'เชื่อถือได้และปลอดภัย',
                'feature_2_description' => 'ดูแลข้อมูลลูกค้าและตรวจสอบรายการอย่างโปร่งใส',

                'feature_3_title' => 'ราคาดีที่สุดสำหรับคุณ',
                'feature_3_description' => 'ประเมินราคาตามรุ่น ความจุ และสภาพจริงของสินค้า',

                'feature_4_title' => 'ชำระเงินด่วน',
                'feature_4_description' => 'เมื่อตรวจสอบสินค้าเรียบร้อยแล้ว ดำเนินการจ่ายเงินอย่างรวดเร็ว',
            ]);
        }

        return view('pages.about', compact('about'));
    }
    private function getSupportPageBySlug(string $slug)
    {
        return SupportPage::query()
            ->with([
                'sections' => function ($query) {
                    $query->where('status', 1)
                        ->with([
                            'items' => function ($itemQuery) {
                                $itemQuery->where('status', 1)
                                    ->orderBy('sort_order', 'asc')
                                    ->orderBy('id', 'asc');
                            },
                        ])
                        ->orderBy('sort_order', 'asc')
                        ->orderBy('id', 'asc');
                },
            ])
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();
    }

    private function getSupportTabs(string $activeSlug)
    {
        return SupportPage::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($page) use ($activeSlug) {
                $url = url('/' . $page->slug);

                if ($page->slug === 'cancel-selling') {
                    $url = route('cancel.selling');
                }

                if ($page->slug === 'how-to-sell') {
                    $url = route('how.to.sell');
                }

                if ($page->slug === 'how-to-get-paid') {
                    $url = route('how.to.get.paid');
                }

                return [
                    'label' => $page->menu_label ?: $page->page_title ?: $page->slug,
                    'url' => $url,
                    'active' => $page->slug === $activeSlug,
                ];
            })
            ->values();
    }

    private function getRandomFaqsForSupportPage(int $limit = 4)
    {
        return DB::table('product_questions')
            ->where('status', 1)
            ->whereNotNull('question')
            ->where('question', '!=', '')
            ->where(function ($query) {
                $query->where(function ($generalQuery) {
                    $generalQuery->where(function ($typeQuery) {
                        $typeQuery->where('question_type', 'general')
                            ->orWhereNull('question_type');
                    })
                        ->whereNotNull('general_answer')
                        ->where('general_answer', '!=', '');
                })
                    ->orWhere(function ($modelQuery) {
                        $modelQuery->where('question_type', 'model_specific')
                            ->whereExists(function ($existsQuery) {
                                $existsQuery->select(DB::raw(1))
                                    ->from('product_question_answers')
                                    ->whereColumn('product_question_answers.product_question_id', 'product_questions.id')
                                    ->where('product_question_answers.status', 1)
                                    ->whereNotNull('product_question_answers.answer')
                                    ->where('product_question_answers.answer', '!=', '');
                            });
                    });
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get()
            ->map(function ($faq) {
                if (($faq->question_type ?? 'general') === 'model_specific') {
                    $modelAnswer = DB::table('product_question_answers')
                        ->where('product_question_id', $faq->id)
                        ->where('status', 1)
                        ->whereNotNull('answer')
                        ->where('answer', '!=', '')
                        ->orderBy('sort_order', 'asc')
                        ->orderBy('id', 'asc')
                        ->value('answer');

                    return [
                        'q' => $faq->question,
                        'a' => $modelAnswer ?: 'กรุณาติดต่อเจ้าหน้าที่เพื่อสอบถามรายละเอียดเพิ่มเติม',
                    ];
                }

                return [
                    'q' => $faq->question,
                    'a' => $faq->general_answer,
                ];
            })
            ->values();
    }
    public function sellAtCashkub()
    {
        return view('pages.sell-at-Cashkub');
    }
    public function cancelSelling()
    {
        $supportPage = $this->getSupportPageBySlug('cancel-selling');
        $supportTabs = $this->getSupportTabs('cancel-selling');
        $randomFaqs = $this->getRandomFaqsForSupportPage(4);

        return view('pages.cancel-selling', compact('supportPage', 'supportTabs', 'randomFaqs'));
    }

    public function howToSell()
    {
        $supportPage = $this->getSupportPageBySlug('how-to-sell');
        $supportTabs = $this->getSupportTabs('how-to-sell');
        $randomFaqs = $this->getRandomFaqsForSupportPage(4);

        return view('pages.how-to-sell', compact('supportPage', 'supportTabs', 'randomFaqs'));
    }

    public function howToGetPaid()
    {
        $supportPage = $this->getSupportPageBySlug('how-to-get-paid');
        $supportTabs = $this->getSupportTabs('how-to-get-paid');
        $randomFaqs = $this->getRandomFaqsForSupportPage(4);

        return view('pages.how-to-get-paid', compact('supportPage', 'supportTabs', 'randomFaqs'));
    }
    public function sellProductCheckout(Request $request)
    {
        $categoryId = $request->input('mobile_product_category_id');
        $brandId = $request->input('mobile_brand_id');
        $modelId = $request->input('mobile_model_id');
        $priceId = $request->input('mobile_model_price_id');

        $categoryData = MobileProductCategory::query()->find($categoryId);
        $brandData = MobileBrand::query()->find($brandId);

        $modelData = MobileModel::query()
            ->with([
                'brand',
                'productCategory',
                'gradePrices',
            ])
            ->find($modelId);

        $priceData = MobileModelPrice::query()->find($priceId);

        if (!$categoryData || !$brandData || !$modelData || !$priceData) {
            return redirect()
                ->route('sell.product')
                ->with('error', 'ไม่พบข้อมูลสินค้าที่เลือก กรุณาเลือกสินค้าใหม่อีกครั้ง');
        }

        if ((int) $modelData->mobile_product_category_id !== (int) $categoryData->id) {
            return redirect()
                ->route('sell.product')
                ->with('error', 'ประเภทสินค้าไม่ตรงกับรุ่นที่เลือก');
        }

        if ((int) $modelData->mobile_brand_id !== (int) $brandData->id) {
            return redirect()
                ->route('sell.product')
                ->with('error', 'แบรนด์สินค้าไม่ตรงกับรุ่นที่เลือก');
        }

        if ((int) $priceData->mobile_model_id !== (int) $modelData->id) {
            return redirect()
                ->route('sell.product')
                ->with('error', 'ความจุสินค้าไม่ตรงกับรุ่นที่เลือก');
        }

        $category = $categoryData->category_name;
        $brand = $brandData->name;
        $model = $modelData->name;
        $storage = $priceData->capacity;

        $summaryTitle = trim($brand . ' ' . $model . ' ' . $storage);

        /*
    |--------------------------------------------------------------------------
    | คำตอบที่เลือกจากหน้าประเมิน
    |--------------------------------------------------------------------------
    */
        $answers = $request->input('answers', []);

        $selectedOptionIds = collect($answers)
            ->flatMap(function ($value) {
                return is_array($value) ? $value : [$value];
            })
            ->filter()
            ->map(function ($value) {
                return (int) $value;
            })
            ->unique()
            ->values()
            ->toArray();

        $selectedOptions = collect();

        if (!empty($selectedOptionIds)) {
            $selectedOptions = ProductGradeQuestionOption::query()
                ->with([
                    'question',
                    'grade',
                ])
                ->whereIn('id', $selectedOptionIds)
                ->get();
        }

        $selectedValues = [
            'category' => $category,
            'brand' => $brand,
            'model' => $model,
            'storage' => $storage,
            'answers' => $selectedOptions->map(function ($option) {
                return [
                    'question_id' => $option->product_grade_question_id,
                    'question_title' => $option->question->question_title ?? '-',
                    'option_id' => $option->id,
                    'option_title' => $option->option_title,
                    'grade_master_id' => $option->grade_master_id,
                    'grade_name' => $option->grade->grade_name ?? null,
                ];
            })->values()->toArray(),
        ];

        $summaryParts = $selectedOptions
            ->pluck('option_title')
            ->filter()
            ->values()
            ->toArray();

        $summaryText = !empty($summaryParts)
            ? implode(' / ', $summaryParts)
            : 'ยังไม่มีข้อมูลสภาพเครื่อง';

        /*
    |--------------------------------------------------------------------------
    | คำนวณราคาฝั่ง Server
    |--------------------------------------------------------------------------
    */
        $basePrice = (float) $priceData->base_price;
        $minPrice = (float) $priceData->min_price;

        $gradeDeductMap = $modelData->gradePrices
            ->mapWithKeys(function ($row) {
                return [
                    (string) $row->grade_master_id => (float) $row->deduct_price,
                ];
            })
            ->toArray();

        $deductTotal = 0;

        foreach ($selectedOptions as $option) {
            $gradeId = (string) $option->grade_master_id;
            $deductTotal += (float) ($gradeDeductMap[$gradeId] ?? 0);
        }

        $rawAfterDeduct = $basePrice - $deductTotal;
        $priceAfterDeduct = max($rawAfterDeduct, $minPrice);

        /*
    |--------------------------------------------------------------------------
    | ผู้ใช้ที่ login อยู่
    |--------------------------------------------------------------------------
    | ใช้สำหรับตรวจสอบโค้ดบวกราคาเฉพาะสมาชิกหน้าบ้านเท่านั้น
    | ไม่เอา id ของแอดมินมาใช้กับ bonus/user_id
    |--------------------------------------------------------------------------
    */
        $userId = null;

        if (Auth::check()) {
            $currentUserId = Auth::id();

            $isFrontendUser = DB::table('users')
                ->where('id', $currentUserId)
                ->where('status', 1)
                ->where('is_super_admin', 0)
                ->exists();

            if ($isFrontendUser) {
                $userId = $currentUserId;
            }
        }

        /*
|--------------------------------------------------------------------------
| Bonus Code
|--------------------------------------------------------------------------
| หน้า checkout เป็นคนเลือกโค้ดบวกราคา
| หน้านี้จึงต้องส่งรายการโค้ดที่ใช้ได้ไปให้ Blade
|--------------------------------------------------------------------------
*/
        $bonusCode = null;
        $bonusAmount = 0;
        $bonusCodes = [];

        if ($userId) {
            $bonusCodes = BonusCode::query()
                ->where('status', 1)
                ->where(function ($query) {
                    $query->whereNull('starts_at')
                        ->orWhere('starts_at', '<=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('ends_at')
                        ->orWhere('ends_at', '>=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('usage_limit')
                        ->orWhereColumn('used_count', '<', 'usage_limit');
                })
                ->where(function ($query) use ($priceAfterDeduct) {
                    $query->whereNull('min_estimate_price')
                        ->orWhere('min_estimate_price', '<=', $priceAfterDeduct);
                })
                ->orderByDesc('id')
                ->get()
                ->filter(function ($bonusCode) use ($userId) {
                    if ($bonusCode->per_user_limit === null) {
                        return true;
                    }

                    $usedByUser = BonusCodeUsage::query()
                        ->where('bonus_code_id', $bonusCode->id)
                        ->where('user_id', $userId)
                        ->count();

                    return $usedByUser < (int) $bonusCode->per_user_limit;
                })
                ->map(function ($bonusCode) {
                    return [
                        'id' => $bonusCode->id,
                        'code' => $bonusCode->code,
                        'name' => $bonusCode->name,
                        'bonus_type' => $bonusCode->bonus_type,
                        'bonus_value' => (float) $bonusCode->bonus_value,
                        'max_bonus_amount' => $bonusCode->max_bonus_amount !== null
                            ? (float) $bonusCode->max_bonus_amount
                            : null,
                        'min_estimate_price' => $bonusCode->min_estimate_price !== null
                            ? (float) $bonusCode->min_estimate_price
                            : null,
                        'usage_limit' => $bonusCode->usage_limit,
                        'per_user_limit' => $bonusCode->per_user_limit,
                        'description' => $bonusCode->description,
                    ];
                })
                ->values()
                ->toArray();
        }

        $estimatedPrice = round($priceAfterDeduct, 2);

        /*
    |--------------------------------------------------------------------------
    | ดึงประเภทการรับซื้อจริง
    |--------------------------------------------------------------------------
    */
        $sellMethods = SellMethod::query()
            ->with([
                'parcelSettings' => function ($query) {
                    $query->where('is_active', 1)
                        ->orderBy('id', 'asc');
                },
                'requiredDocuments' => function ($query) {
                    $query->where('is_active', 1)
                        ->orderBy('id', 'asc');
                },
                'transitStations' => function ($query) {
                    $query->wherePivot('is_active', 1)
                        ->where('transit_stations.is_active', 1)
                        ->orderBy('transit_stations.line_id', 'asc')
                        ->orderBy('transit_stations.sort_order', 'asc')
                        ->orderBy('transit_stations.id', 'asc');
                },
            ])
            ->where('is_active', 1)
            ->orderBy('id', 'asc')
            ->get();

        $resolvePanelKey = function ($method) {
            $key = mb_strtolower((string) ($method->key ?? ''));
            $name = mb_strtolower((string) ($method->name ?? ''));

            if (
                str_contains($key, 'bts') ||
                str_contains($key, 'mrt') ||
                str_contains($key, 'train') ||
                str_contains($key, 'transit') ||
                str_contains($name, 'bts') ||
                str_contains($name, 'mrt') ||
                str_contains($name, 'รถไฟ') ||
                str_contains($name, 'สถานี')
            ) {
                return 'bts_mrt';
            }

            if (
                str_contains($key, 'ems') ||
                str_contains($key, 'parcel') ||
                str_contains($key, 'shipping') ||
                str_contains($key, 'delivery') ||
                str_contains($name, 'ems') ||
                str_contains($name, 'พัสดุ') ||
                str_contains($name, 'จัดส่ง') ||
                str_contains($name, 'ไปรษณีย์')
            ) {
                return 'ems';
            }

            return 'store';
        };

        $pickupMethods = $sellMethods->map(function ($method) use ($resolvePanelKey) {
            return [
                'id' => $method->id,
                'key' => $method->key,
                'panel_key' => $resolvePanelKey($method),
                'label' => $method->name,
                'description' => $method->description,
            ];
        })->values()->toArray();

        $sellMethodsByKey = $sellMethods->keyBy('key');

        $storeMethod = $sellMethods->first(function ($method) use ($resolvePanelKey) {
            return $resolvePanelKey($method) === 'store';
        });

        $btsMethod = $sellMethods->first(function ($method) use ($resolvePanelKey) {
            return $resolvePanelKey($method) === 'bts_mrt';
        });

        $emsMethod = $sellMethods->first(function ($method) use ($resolvePanelKey) {
            return $resolvePanelKey($method) === 'ems';
        });

        /*
|--------------------------------------------------------------------------
| ดึงพื้นที่ให้บริการรับซื้อถึงที่จาก service_areas
|--------------------------------------------------------------------------
| รองรับโครงสร้างใหม่:
| - thai_province_id
| - thai_district_id
| - is_all_districts
|
| ถ้า is_all_districts = 1 จะดึงอำเภอทั้งหมดของจังหวัดนั้นไปให้หน้า checkout
| เพื่อให้ลูกค้าเลือกอำเภอได้ครบ
|--------------------------------------------------------------------------
*/
        $rawServiceAreas = ServiceArea::query()
            ->with(['provinceData', 'districtData'])
            ->orderBy('thai_province_id', 'asc')
            ->orderBy('is_all_districts', 'desc')
            ->orderBy('thai_district_id', 'asc')
            ->get();

        $serviceAreaRows = collect();

        foreach ($rawServiceAreas as $serviceArea) {
            $provinceName = $serviceArea->provinceData->name_th
                ?? $serviceArea->province
                ?? null;

            if (empty($provinceName)) {
                continue;
            }

            if ((int) $serviceArea->is_all_districts === 1) {
                $districtsByProvince = DB::table('thai_districts')
                    ->where('thai_province_id', $serviceArea->thai_province_id)
                    ->where('is_active', 1)
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('name_th', 'asc')
                    ->get();

                foreach ($districtsByProvince as $district) {
                    $serviceAreaRows->push([
                        'id' => $serviceArea->id,
                        'service_area_id' => $serviceArea->id,
                        'thai_province_id' => $serviceArea->thai_province_id,
                        'thai_district_id' => $district->id,
                        'province' => $provinceName,
                        'district' => $district->name_th,
                        'is_all_districts' => 1,
                    ]);
                }

                continue;
            }

            $districtName = $serviceArea->districtData->name_th
                ?? $serviceArea->district
                ?? null;

            if (empty($districtName)) {
                continue;
            }

            $serviceAreaRows->push([
                'id' => $serviceArea->id,
                'service_area_id' => $serviceArea->id,
                'thai_province_id' => $serviceArea->thai_province_id,
                'thai_district_id' => $serviceArea->thai_district_id,
                'province' => $provinceName,
                'district' => $districtName,
                'is_all_districts' => 0,
            ]);
        }

        $serviceAreas = $serviceAreaRows
            ->unique(function ($item) {
                return $item['province'] . '|' . $item['district'];
            })
            ->sortBy([
                ['province', 'asc'],
                ['district', 'asc'],
            ])
            ->values();

        $serviceProvinces = $serviceAreas
            ->pluck('province')
            ->filter()
            ->unique()
            ->values()
            ->all();
        /*
    |--------------------------------------------------------------------------
    | ดึงสถานีรถไฟฟ้า + จัดกลุ่มตามสายรถไฟฟ้าจริง
    |--------------------------------------------------------------------------
    */
        $transitStations = collect();
        $transitStationsByLine = [];

        if ($btsMethod) {
            $transitStations = $btsMethod->transitStations;

            $lineIds = $transitStations
                ->pluck('line_id')
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            $lines = collect();

            if (!empty($lineIds)) {
                $lines = DB::table('transit_lines')
                    ->whereIn('id', $lineIds)
                    ->where('is_active', 1)
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('id', 'asc')
                    ->get()
                    ->keyBy('id');
            }

            $transitStationsByLine = $transitStations
                ->filter(function ($station) use ($lines) {
                    return !empty($station->line_id) && $lines->has($station->line_id);
                })
                ->groupBy(function ($station) {
                    return (int) $station->line_id;
                })
                ->map(function ($stations, $lineId) use ($lines) {
                    $line = $lines->get((int) $lineId);

                    return [
                        'line_id' => $line->id ?? null,
                        'line_code' => $line->code ?? null,
                        'line_name' => (string) ($line->name ?? $line->code ?? 'ไม่ระบุสายรถไฟฟ้า'),
                        'operator_name' => $line->operator_name ?? null,
                        'line_color' => $line->line_color ?? null,
                        'line_sort_order' => (int) ($line->sort_order ?? 0),
                        'stations' => $stations
                            ->sortBy(function ($station) {
                                return sprintf(
                                    '%010d-%010d',
                                    (int) ($station->sort_order ?? 0),
                                    (int) ($station->id ?? 0)
                                );
                            })
                            ->map(function ($station) {
                                return [
                                    'id' => $station->id,
                                    'name' => (string) (
                                        $station->name_th
                                        ?? $station->name_en
                                        ?? $station->station_code
                                        ?? '-'
                                    ),
                                    'name_th' => $station->name_th ?? null,
                                    'name_en' => $station->name_en ?? null,
                                    'station_code' => $station->station_code ?? null,
                                ];
                            })
                            ->values()
                            ->toArray(),
                    ];
                })
                ->sortBy(function ($line) {
                    return sprintf(
                        '%010d-%010d',
                        (int) ($line['line_sort_order'] ?? 0),
                        (int) ($line['line_id'] ?? 0)
                    );
                })
                ->values()
                ->toArray();
        }

        $parcelSettings = $emsMethod
            ? $emsMethod->parcelSettings
            : collect();

        $requiredDocuments = $emsMethod
            ? $emsMethod->requiredDocuments
            : collect();

        /*
    |--------------------------------------------------------------------------
    | วัน / เวลา
    |--------------------------------------------------------------------------
    */
        $provinces = $serviceProvinces;

        $districts = $serviceAreas
            ->pluck('district')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $days = [
            'วัน',
            'จันทร์',
            'อังคาร',
            'พุธ',
            'พฤหัสบดี',
            'ศุกร์',
            'เสาร์',
            'อาทิตย์',
        ];

        /*
    |--------------------------------------------------------------------------
    | ดึงช่วงเวลาที่รับบริการจาก service_time_slots จริง
    |--------------------------------------------------------------------------
    */
        $serviceTimeSlots = ServiceTimeSlot::query()
            ->where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        $times = collect(['เวลา'])
            ->merge(
                $serviceTimeSlots->map(function ($slot) {
                    if (!empty($slot->label)) {
                        return $slot->label;
                    }

                    $startTime = substr((string) $slot->start_time, 0, 5);
                    $endTime = substr((string) $slot->end_time, 0, 5);

                    return str_replace(':', '.', $startTime) . ' - ' . str_replace(':', '.', $endTime);
                })
            )
            ->values()
            ->toArray();

        $productImage = 'assets/media/hero/hero-phone-right.png';

        return view('pages.sell-product-checkout', [
            'category' => $category,
            'brand' => $brand,
            'model' => $model,
            'storage' => $storage,
            'summaryTitle' => $summaryTitle,

            'categoryData' => $categoryData,
            'brandData' => $brandData,
            'modelData' => $modelData,
            'priceData' => $priceData,

            'mobileProductCategoryId' => $categoryData->id,
            'mobileBrandId' => $brandData->id,
            'mobileModelId' => $modelData->id,
            'mobileModelPriceId' => $priceData->id,

            'selectedValues' => $selectedValues,
            'summaryText' => $summaryText,

            'basePrice' => $basePrice,
            'minPrice' => $minPrice,
            'deductTotal' => $deductTotal,
            'priceAfterDeduct' => $priceAfterDeduct,
            'bonusCode' => $bonusCode,
            'bonusAmount' => $bonusAmount,
            'bonusCodes' => $bonusCodes,
            'canUseBonusCode' => $userId !== null,
            'estimatedPrice' => $estimatedPrice,

            'sellMethods' => $sellMethods,
            'sellMethodsByKey' => $sellMethodsByKey,
            'pickupMethods' => $pickupMethods,

            'storeMethod' => $storeMethod,
            'btsMethod' => $btsMethod,
            'emsMethod' => $emsMethod,

            'serviceAreas' => $serviceAreas,
            'serviceProvinces' => $serviceProvinces,
            'serviceTimeSlots' => $serviceTimeSlots,

            'transitStations' => $transitStations,
            'transitStationsByLine' => $transitStationsByLine,
            'parcelSettings' => $parcelSettings,
            'requiredDocuments' => $requiredDocuments,

            'provinces' => $provinces,
            'districts' => $districts,
            'days' => $days,
            'times' => $times,

            'productImage' => $productImage,
        ]);
    }

    public function storeSellOrder(Request $request)
    {
        $pickupPanel = $request->input('pickup_panel', 'store');

        $baseRules = [
            'mobile_product_category_id' => ['required', 'integer'],
            'mobile_brand_id' => ['required', 'integer'],
            'mobile_model_id' => ['required', 'integer'],
            'mobile_model_price_id' => ['required', 'integer'],
            'pickup_method' => ['required', 'string'],
            'pickup_panel' => ['required', 'in:store,bts_mrt,ems'],
            'accept_terms' => ['required'],
            'selected_option_ids' => ['nullable', 'array'],
            'selected_option_ids.*' => ['nullable', 'integer'],
            'bonus_code_id' => ['nullable', 'integer'],
        ];

        if ($pickupPanel === 'store') {
            $baseRules = array_merge($baseRules, [
                'fullname_store' => ['required', 'string', 'max:255'],
                'phone_store' => ['required', 'string', 'max:50'],
                'line_id_store' => ['nullable', 'string', 'max:100'],
                'email_store' => ['nullable', 'email', 'max:255'],
                'address_store' => ['required', 'string'],
                'province_store' => ['required'],
                'district_store' => ['required'],
                'pickup_date_store' => [
                    'required',
                    'date',
                    'after_or_equal:' . now()->format('Y-m-d'),
                    'before_or_equal:' . now()->addDays(30)->format('Y-m-d'),
                ],
                'pickup_time_store' => ['required', 'string', 'not_in:เวลา'],
            ]);
        }

        if ($pickupPanel === 'bts_mrt') {
            $baseRules = array_merge($baseRules, [
                'fullname_bts' => ['required', 'string', 'max:255'],
                'phone_bts' => ['required', 'string', 'max:50'],
                'line_id_bts' => ['nullable', 'string', 'max:100'],
                'email_bts' => ['nullable', 'email', 'max:255'],
                'transit_line' => ['required', 'string', 'max:255'],
                'transit_station_id' => ['required', 'integer'],
                'pickup_date_bts' => [
                    'required',
                    'date',
                    'after_or_equal:' . now()->format('Y-m-d'),
                    'before_or_equal:' . now()->addDays(30)->format('Y-m-d'),
                ],
                'pickup_time_bts' => ['required', 'string', 'not_in:เวลา'],
            ]);
        }

        if ($pickupPanel === 'ems') {
            $baseRules = array_merge($baseRules, [
                'fullname_ems' => ['required', 'string', 'max:255'],
                'phone_ems' => ['required', 'string', 'max:50'],
                'line_id_ems' => ['nullable', 'string', 'max:100'],
                'email_ems' => ['nullable', 'email', 'max:255'],
            ]);
        }

        $request->validate($baseRules, [
            'accept_terms.required' => 'กรุณายอมรับข้อตกลงและเงื่อนไข',
            'province_store.required' => 'กรุณาเลือกจังหวัด',
            'district_store.required' => 'กรุณาเลือกเขต / อำเภอ',
            'pickup_date_store.after_or_equal' => 'วันนัดหมายต้องเริ่มตั้งแต่วันนี้',
            'pickup_date_store.before_or_equal' => 'วันนัดหมายต้องไม่เกิน 30 วัน',
            'pickup_date_bts.after_or_equal' => 'วันนัดหมายต้องเริ่มตั้งแต่วันนี้',
            'pickup_date_bts.before_or_equal' => 'วันนัดหมายต้องไม่เกิน 30 วัน',
        ]);

        $provinceId = null;
        $districtId = null;
        $provinceName = null;
        $districtName = null;

        if ($pickupPanel === 'store') {
            $provinceInput = trim((string) $request->input('province_store'));
            $districtInput = trim((string) $request->input('district_store'));

            if (is_numeric($provinceInput)) {
                $provinceRow = DB::table('thai_provinces')
                    ->where('id', (int) $provinceInput)
                    ->first();
            } else {
                $provinceRow = DB::table('thai_provinces')
                    ->where(function ($query) use ($provinceInput) {
                        $query->where('name_th', $provinceInput)
                            ->orWhere('name_en', $provinceInput);
                    })
                    ->first();
            }

            if (!$provinceRow) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'province_store' => 'ไม่พบจังหวัดที่เลือก',
                    ]);
            }

            $provinceId = (int) $provinceRow->id;
            $provinceName = $provinceRow->name_th ?? $provinceRow->name_en ?? null;

            if (is_numeric($districtInput)) {
                $districtRow = DB::table('thai_districts')
                    ->where('id', (int) $districtInput)
                    ->where('thai_province_id', $provinceId)
                    ->first();
            } else {
                $districtRow = DB::table('thai_districts')
                    ->where('thai_province_id', $provinceId)
                    ->where(function ($query) use ($districtInput) {
                        $query->where('name_th', $districtInput)
                            ->orWhere('name_en', $districtInput);
                    })
                    ->first();
            }

            if (!$districtRow) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'district_store' => 'เขต / อำเภอที่เลือกไม่ตรงกับจังหวัด',
                    ]);
            }

            $districtId = (int) $districtRow->id;
            $districtName = $districtRow->name_th ?? $districtRow->name_en ?? null;

            $serviceAreaExists = ServiceArea::query()
                ->where('thai_province_id', $provinceId)
                ->where(function ($query) use ($districtId) {
                    $query->where('is_all_districts', 1)
                        ->orWhere('thai_district_id', $districtId);
                })
                ->exists();

            if (!$serviceAreaExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'district_store' => 'พื้นที่นี้ยังไม่เปิดให้บริการรับซื้อถึงที่',
                    ]);
            }
        }

        if ($pickupPanel === 'store' && $this->isPastOrCurrentPickupTime(
            $request->input('pickup_date_store'),
            $request->input('pickup_time_store')
        )) {
            return back()
                ->withInput()
                ->withErrors([
                    'pickup_time_store' => 'กรุณาเลือกช่วงเวลาถัดไป เนื่องจากช่วงเวลานี้เลยเวลาหรืออยู่ในช่วงเวลาปัจจุบันแล้ว',
                ]);
        }

        if ($pickupPanel === 'bts_mrt' && $this->isPastOrCurrentPickupTime(
            $request->input('pickup_date_bts'),
            $request->input('pickup_time_bts')
        )) {
            return back()
                ->withInput()
                ->withErrors([
                    'pickup_time_bts' => 'กรุณาเลือกช่วงเวลาถัดไป เนื่องจากช่วงเวลานี้เลยเวลาหรืออยู่ในช่วงเวลาปัจจุบันแล้ว',
                ]);
        }

        DB::beginTransaction();

        try {
            $categoryData = MobileProductCategory::query()
                ->findOrFail($request->input('mobile_product_category_id'));

            $brandData = MobileBrand::query()
                ->findOrFail($request->input('mobile_brand_id'));

            $modelData = MobileModel::query()
                ->with([
                    'brand',
                    'productCategory',
                    'gradePrices',
                ])
                ->findOrFail($request->input('mobile_model_id'));

            $priceData = MobileModelPrice::query()
                ->findOrFail($request->input('mobile_model_price_id'));

            if ((int) $modelData->mobile_product_category_id !== (int) $categoryData->id) {
                throw new \Exception('ประเภทสินค้าไม่ตรงกับรุ่นที่เลือก');
            }

            if ((int) $modelData->mobile_brand_id !== (int) $brandData->id) {
                throw new \Exception('แบรนด์สินค้าไม่ตรงกับรุ่นที่เลือก');
            }

            if ((int) $priceData->mobile_model_id !== (int) $modelData->id) {
                throw new \Exception('ความจุสินค้าไม่ตรงกับรุ่นที่เลือก');
            }

            $selectedOptionIds = collect($request->input('selected_option_ids', []))
                ->filter()
                ->map(function ($id) {
                    return (int) $id;
                })
                ->unique()
                ->values()
                ->toArray();

            $selectedOptions = collect();

            if (!empty($selectedOptionIds)) {
                $selectedOptions = ProductGradeQuestionOption::query()
                    ->with([
                        'question',
                        'grade',
                    ])
                    ->whereIn('id', $selectedOptionIds)
                    ->get();
            }

            $basePrice = (float) $priceData->base_price;
            $minPrice = (float) $priceData->min_price;

            $gradeDeductMap = $modelData->gradePrices
                ->mapWithKeys(function ($row) {
                    return [
                        (string) $row->grade_master_id => (float) $row->deduct_price,
                    ];
                })
                ->toArray();

            $deductTotal = 0;

            foreach ($selectedOptions as $option) {
                $gradeId = (string) $option->grade_master_id;
                $deductTotal += (float) ($gradeDeductMap[$gradeId] ?? 0);
            }

            $priceAfterDeduct = max($basePrice - $deductTotal, $minPrice);

            $userId = null;

            if (Auth::check()) {
                $currentUserId = Auth::id();

                $isFrontendUser = DB::table('users')
                    ->where('id', $currentUserId)
                    ->where('status', 1)
                    ->where('is_super_admin', 0)
                    ->exists();

                if ($isFrontendUser) {
                    $userId = $currentUserId;
                }
            }

            $bonusCode = null;
            $bonusAmount = 0;

            if ($userId && $request->filled('bonus_code_id')) {
                $bonusCode = BonusCode::query()
                    ->where('id', $request->input('bonus_code_id'))
                    ->where('status', 1)
                    ->where(function ($query) {
                        $query->whereNull('starts_at')
                            ->orWhere('starts_at', '<=', now());
                    })
                    ->where(function ($query) {
                        $query->whereNull('ends_at')
                            ->orWhere('ends_at', '>=', now());
                    })
                    ->where(function ($query) {
                        $query->whereNull('usage_limit')
                            ->orWhereColumn('used_count', '<', 'usage_limit');
                    })
                    ->where(function ($query) use ($priceAfterDeduct) {
                        $query->whereNull('min_estimate_price')
                            ->orWhere('min_estimate_price', '<=', $priceAfterDeduct);
                    })
                    ->first();

                if ($bonusCode && $bonusCode->per_user_limit !== null) {
                    $usedByUser = BonusCodeUsage::query()
                        ->where('bonus_code_id', $bonusCode->id)
                        ->where('user_id', $userId)
                        ->count();

                    if ($usedByUser >= (int) $bonusCode->per_user_limit) {
                        $bonusCode = null;
                    }
                }

                if ($bonusCode) {
                    if ($bonusCode->bonus_type === BonusCode::TYPE_PERCENT) {
                        $bonusAmount = ($priceAfterDeduct * (float) $bonusCode->bonus_value) / 100;

                        if ($bonusCode->max_bonus_amount !== null) {
                            $bonusAmount = min($bonusAmount, (float) $bonusCode->max_bonus_amount);
                        }
                    } else {
                        $bonusAmount = (float) $bonusCode->bonus_value;
                    }

                    $bonusAmount = max(0, round($bonusAmount, 2));
                }
            }

            $finalEstimatePrice = round($priceAfterDeduct + $bonusAmount, 2);

            $sellMethod = SellMethod::query()
                ->where('key', $request->input('pickup_method'))
                ->first();

            $customerName = null;
            $customerPhone = null;
            $customerLineId = null;
            $customerEmail = null;
            $pickupDate = null;
            $pickupTime = null;

            if ($pickupPanel === 'store') {
                $customerName = $request->input('fullname_store');
                $customerPhone = $request->input('phone_store');
                $customerLineId = $request->input('line_id_store');
                $customerEmail = $request->input('email_store');
                $pickupDate = $request->input('pickup_date_store');
                $pickupTime = $request->input('pickup_time_store');
            }

            if ($pickupPanel === 'bts_mrt') {
                $customerName = $request->input('fullname_bts');
                $customerPhone = $request->input('phone_bts');
                $customerLineId = $request->input('line_id_bts');
                $customerEmail = $request->input('email_bts');
                $pickupDate = $request->input('pickup_date_bts');
                $pickupTime = $request->input('pickup_time_bts');
            }

            if ($pickupPanel === 'ems') {
                $customerName = $request->input('fullname_ems');
                $customerPhone = $request->input('phone_ems');
                $customerLineId = $request->input('line_id_ems');
                $customerEmail = $request->input('email_ems');
            }

            $orderNo = $this->generateSellOrderNo();

            $summaryTitle = trim(($brandData->name ?? '') . ' ' . ($modelData->name ?? '') . ' ' . ($priceData->capacity ?? ''));
            $summaryText = $selectedOptions->pluck('option_title')->filter()->implode(' / ');

            $rawPayload = $request->except(['_token']);

            if ($pickupPanel === 'store') {
                $rawPayload['province_store_id'] = $provinceId;
                $rawPayload['district_store_id'] = $districtId;
                $rawPayload['province_store_name'] = $provinceName;
                $rawPayload['district_store_name'] = $districtName;
            }

            $sellOrderId = DB::table('sell_orders')->insertGetId([
                'order_no' => $orderNo,
                'user_id' => $userId,

                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'line_id' => $customerLineId,
                'customer_email' => $customerEmail,

                'mobile_product_category_id' => $categoryData->id,
                'mobile_brand_id' => $brandData->id,
                'mobile_model_id' => $modelData->id,
                'mobile_model_price_id' => $priceData->id,

                'category_name' => $categoryData->category_name,
                'brand_name' => $brandData->name,
                'model_name' => $modelData->name,
                'capacity' => $priceData->capacity,

                'summary_title' => $summaryTitle,
                'summary_text' => $summaryText,

                'base_price' => $basePrice,
                'min_price' => $minPrice,
                'deduct_total' => $deductTotal,
                'price_after_deduct' => $priceAfterDeduct,

                'bonus_code_id' => $bonusCode->id ?? null,
                'bonus_code' => $bonusCode->code ?? null,
                'bonus_name' => $bonusCode->name ?? null,
                'bonus_type' => $bonusCode->bonus_type ?? null,
                'bonus_value' => $bonusCode->bonus_value ?? 0,
                'bonus_amount' => $bonusAmount,
                'final_estimate_price' => $finalEstimatePrice,

                'sell_method_id' => $sellMethod->id ?? null,
                'sell_method_key' => $sellMethod->key ?? $request->input('pickup_method'),
                'sell_method_name' => $sellMethod->name ?? null,
                'pickup_panel' => $pickupPanel,

                'pickup_date' => $pickupDate,
                'pickup_time' => $pickupTime,

                'accept_terms' => 1,
                'status' => 'pending',
                'raw_payload' => json_encode($rawPayload, JSON_UNESCAPED_UNICODE),

                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($selectedOptions as $index => $option) {
                $gradeId = (string) $option->grade_master_id;
                $deductPrice = (float) ($gradeDeductMap[$gradeId] ?? 0);

                DB::table('sell_order_answers')->insert([
                    'sell_order_id' => $sellOrderId,
                    'product_grade_question_id' => $option->product_grade_question_id,
                    'product_grade_question_option_id' => $option->id,
                    'grade_master_id' => $option->grade_master_id,
                    'question_title' => $option->question->question_title ?? null,
                    'option_title' => $option->option_title,
                    'grade_name' => $option->grade->grade_name ?? null,
                    'deduct_price' => $deductPrice,
                    'sort_order' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $pickupDetail = $this->buildSellOrderPickupDetailPayload($request, $sellOrderId, $pickupPanel);

            if ($pickupPanel === 'store') {
                $pickupDetail['thai_province_id'] = $provinceId;
                $pickupDetail['thai_district_id'] = $districtId;
                $pickupDetail['province'] = $provinceName;
                $pickupDetail['district'] = $districtName;
            }

            $pickupDetailColumns = Schema::getColumnListing('sell_order_pickup_details');

            $pickupDetail = collect($pickupDetail)
                ->only($pickupDetailColumns)
                ->toArray();

            DB::table('sell_order_pickup_details')->insert($pickupDetail);

            if ($pickupPanel === 'ems' && $sellMethod) {
                $documents = DB::table('sell_method_required_documents')
                    ->where('sell_method_id', $sellMethod->id)
                    ->where('is_active', 1)
                    ->get();

                foreach ($documents as $document) {
                    DB::table('sell_order_required_documents')->insert([
                        'sell_order_id' => $sellOrderId,
                        'sell_method_required_document_id' => $document->id,
                        'document_name' => $document->name ?? $document->title ?? $document->document_name ?? '-',
                        'description' => $document->description ?? null,
                        'is_received' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::table('sell_order_status_histories')->insert([
                'sell_order_id' => $sellOrderId,
                'old_status' => null,
                'new_status' => 'pending',
                'changed_by_admin_id' => null,
                'changed_by_user_id' => $userId,
                'note' => 'ลูกค้าส่งคำขอขายสินค้า',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($bonusCode) {
                DB::table('bonus_code_usages')->insert([
                    'bonus_code_id' => $bonusCode->id,
                    'user_id' => $userId,
                    'sell_order_id' => $sellOrderId,
                    'code' => $bonusCode->code,
                    'estimate_price' => $finalEstimatePrice,
                    'bonus_amount' => $bonusAmount,
                    'used_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('bonus_codes')
                    ->where('id', $bonusCode->id)
                    ->increment('used_count');
            }

            // DB::commit();
            DB::commit();

            $this->sendSellOrderDiscordNotification([
                'sell_order_id' => $sellOrderId,
                'order_no' => $orderNo,

                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'customer_line_id' => $customerLineId,
                'customer_email' => $customerEmail,

                'summary_title' => $summaryTitle,
                'summary_text' => $summaryText,
                'final_estimate_price' => $finalEstimatePrice,

                'pickup_panel' => $pickupPanel,
                'pickup_date' => $pickupDate,
                'pickup_time' => $pickupTime,

                // สำหรับรับซื้อถึงที่
                'pickup_address' => $request->input('address_store'),
                'pickup_province' => $provinceName,
                'pickup_district' => $districtName,

                // สำหรับ BTS / MRT
                'transit_line' => $request->input('transit_line'),
                'transit_station_id' => $request->input('transit_station_id'),
            ]);

            return redirect()
                ->route('sell.product.orders.success', $orderNo)
                ->with('success', 'ส่งคำขอขายสินค้าเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Store sell order error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'pickup_panel' => $pickupPanel,
                'payload' => $request->except(['_token']),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'ไม่สามารถบันทึกคำสั่งขายได้: ' . $e->getMessage());
        }
    }
    private function sendSellOrderDiscordNotification(array $order): void
    {
        $webhookUrl = config('services.discord.webhook_url');

        if (empty($webhookUrl)) {
            Log::warning('Discord webhook URL is empty.', [
                'sell_order_id' => $order['sell_order_id'] ?? null,
                'order_no' => $order['order_no'] ?? null,
            ]);

            return;
        }

        $sellOrderId = $order['sell_order_id'] ?? null;

        $sellOrderBaseUrl = rtrim(
            config('services.admin_urls.sell_order_base_url', rtrim(config('app.url'), '/') . '/admin/sell-orders'),
            '/'
        );

        $adminOrderUrl = $sellOrderBaseUrl . '/' . $sellOrderId;

        $pickupPanel = $order['pickup_panel'] ?? 'store';

        $pickupMethodText = '-';
        $pickupLocationText = '-';
        $pickupDateText = '-';
        $pickupTimeText = '-';

        if ($pickupPanel === 'store') {
            $pickupMethodText = 'รับซื้อถึงที่';
            $address = trim((string) ($order['pickup_address'] ?? ''));
            $province = trim((string) ($order['pickup_province'] ?? ''));
            $district = trim((string) ($order['pickup_district'] ?? ''));
            $locationParts = [];
            if ($address !== '') {
                $locationParts[] = $address;
            }
            if ($province !== '') {
                $locationParts[] = 'จังหวัด ' . $province;
            }
            if ($district !== '') {
                $locationParts[] = 'เขต/อำเภอ ' . $district;
            }
            $pickupLocationText = !empty($locationParts)
                ? implode(' ', $locationParts)
                : '-';

            $pickupDateText = $order['pickup_date'] ?? '-';
            $pickupTimeText = $order['pickup_time'] ?? '-';
        } elseif ($pickupPanel === 'bts_mrt') {
            $pickupMethodText = 'นัดรับ BTS / MRT';

            $transitLine = $order['transit_line'] ?? '-';
            $transitStation = $order['transit_station'] ?? '-';

            $pickupLocationText = trim($transitLine . ' / ' . $transitStation);
            $pickupDateText = $order['pickup_date'] ?? '-';
            $pickupTimeText = $order['pickup_time'] ?? '-';
        } elseif ($pickupPanel === 'ems') {
            $pickupMethodText = 'ส่ง EMS';
            $pickupLocationText = 'ลูกค้าจัดส่งสินค้าทาง EMS';
            $pickupDateText = '-';
            $pickupTimeText = '-';
        }

        $orderNo = $order['order_no'] ?? '-';
        $customerName = $order['customer_name'] ?? '-';
        $customerPhone = $order['customer_phone'] ?? '-';
        $customerEmail = $order['customer_email'] ?? '-';
        $customerLineId = $order['customer_line_id'] ?? '-';

        $summaryTitle = $order['summary_title'] ?? '-';
        $summaryText = $order['summary_text'] ?? '-';

        $finalEstimatePrice = (float) ($order['final_estimate_price'] ?? 0);
        $priceText = number_format($finalEstimatePrice, 0) . ' บาท';

        $createdAtText = now()->format('d/M/Y H:i:s');

        $content = "มีผู้ใช้ส่งใบประเมินราคามือถือมาจาก Cashkub\n"
            . "------------------------------\n\n"
            . "หมายเลขใบประเมิน #{$orderNo}\n"
            . "วันที่ส่งใบประเมิน :{$createdAtText}\n"
            . "ชื่อลูกค้า :{$customerName}\n"
            . "วิธีรับซื้อ :{$pickupMethodText}\n"
            . "สถานที่นัดรับ :{$pickupLocationText}\n"
            . "วันที่นัดรับ :{$pickupDateText}\n"
            . "เวลานัดรับ :{$pickupTimeText}\n"
            . "เบอร์โทรติดต่อ :{$customerPhone}\n";

        if (!empty($customerLineId) && $customerLineId !== '-') {
            $content .= "Line ID :{$customerLineId}\n";
        }

        if (!empty($customerEmail) && $customerEmail !== '-') {
            $content .= "อีเมล :{$customerEmail}\n";
        }

        $content .= "\n------------------------------\n"
            . "#ข้อมูลใบประเมิน :\n"
            . "ยี่ห้อ/รุ่น :{$summaryTitle}\n"
            . "รายการประเมิน :{$summaryText}\n"
            . "ราคาประเมิน :{$priceText}\n"
            . "\n------------------------------\n"
            . "ดูรายละเอียดหลังบ้าน :\n"
            . $adminOrderUrl;

        try {
            $response = Http::timeout(5)
                ->retry(2, 500)
                ->post($webhookUrl, [
                    'username' => 'Noti Cashkub',
                    'content' => $content,
                ]);

            if ($response->failed()) {
                Log::error('Send sell order Discord notification failed.', [
                    'sell_order_id' => $sellOrderId,
                    'order_no' => $orderNo,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (Throwable $e) {
            Log::error('Send sell order Discord notification exception.', [
                'sell_order_id' => $sellOrderId,
                'order_no' => $orderNo,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
    private function isPastOrCurrentPickupTime($date, $timeRange)
    {
        if (empty($date) || empty($timeRange) || $timeRange === 'เวลา') {
            return false;
        }

        if ($date !== now()->format('Y-m-d')) {
            return false;
        }

        $normalizedTimeRange = str_replace('.', ':', $timeRange);

        if (!preg_match('/^(\d{2}):(\d{2})\s*-\s*(\d{2}):(\d{2})$/', $normalizedTimeRange, $matches)) {
            return false;
        }

        $startHour = (int) $matches[1];
        $startMinute = (int) $matches[2];

        $startMinutes = ($startHour * 60) + $startMinute;
        $currentMinutes = ((int) now()->format('H') * 60) + (int) now()->format('i');

        return $startMinutes <= $currentMinutes;
    }

    private function generateSellOrderNo()
    {
        $prefix = 'SEL-' . now()->format('ymd') . '-';

        $latestOrder = DB::table('sell_orders')
            ->where('order_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;

        if ($latestOrder && !empty($latestOrder->order_no)) {
            $lastNumber = (int) Str::afterLast($latestOrder->order_no, '-');
            $nextNumber = $lastNumber + 1;
        }

        return $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    private function buildSellOrderPickupDetailPayload(Request $request, int $sellOrderId, string $pickupPanel)
    {
        $payload = [
            'sell_order_id' => $sellOrderId,
            'pickup_panel' => $pickupPanel,

            'fullname' => null,
            'phone' => null,
            'email' => null,

            'sell_method_branch_id' => null,
            'branch_name' => null,
            'branch_address' => null,
            'branch_province' => null,
            'branch_district' => null,

            'customer_address' => null,
            'province' => null,
            'district' => null,

            'transit_line_name' => null,
            'transit_station_id' => null,
            'transit_station_name' => null,
            'transit_station_code' => null,

            'parcel_receiver_name' => null,
            'parcel_receiver_address' => null,
            'parcel_receiver_phone' => null,

            'sender_address' => null,

            'pickup_date' => null,
            'pickup_time' => null,

            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($pickupPanel === 'store') {
            $branch = null;

            if ($request->filled('sell_method_branch_id')) {
                $branch = DB::table('sell_method_branches')
                    ->where('id', $request->input('sell_method_branch_id'))
                    ->first();
            }

            $payload['fullname'] = $request->input('fullname_store');
            $payload['phone'] = $request->input('phone_store');
            $payload['email'] = $request->input('email_store');

            $payload['sell_method_branch_id'] = $branch->id ?? null;
            $payload['branch_name'] = $branch->branch_name ?? $branch->name ?? $branch->title ?? null;
            $payload['branch_address'] = $branch->address ?? null;
            $payload['branch_province'] = $branch->province ?? null;
            $payload['branch_district'] = $branch->district ?? null;

            $payload['customer_address'] = $request->input('address_store');
            $payload['province'] = $request->input('province_store');
            $payload['district'] = $request->input('district_store');
            $payload['pickup_date'] = $request->input('pickup_date_store');
            $payload['pickup_time'] = $request->input('pickup_time_store');
        }

        if ($pickupPanel === 'bts_mrt') {
            $station = DB::table('transit_stations')
                ->where('id', $request->input('transit_station_id'))
                ->first();

            $payload['fullname'] = $request->input('fullname_bts');
            $payload['phone'] = $request->input('phone_bts');
            $payload['email'] = $request->input('email_bts');

            $payload['transit_line_name'] = $request->input('transit_line');
            $payload['transit_station_id'] = $station->id ?? null;
            $payload['transit_station_name'] = $station->name_th ?? $station->name_en ?? $station->name ?? null;
            $payload['transit_station_code'] = $station->station_code ?? null;
            $payload['pickup_date'] = $request->input('pickup_date_bts');
            $payload['pickup_time'] = $request->input('pickup_time_bts');
        }

        if ($pickupPanel === 'ems') {
            $payload['fullname'] = $request->input('fullname_ems');
            $payload['phone'] = $request->input('phone_ems');
            $payload['email'] = $request->input('email_ems');
            $payload['sender_address'] = $request->input('address_ems');

            $parcel = DB::table('sell_method_parcel_settings')
                ->where('is_active', 1)
                ->orderBy('id', 'asc')
                ->first();

            $payload['parcel_receiver_name'] = $parcel->name ?? $parcel->title ?? $parcel->branch_name ?? null;
            $payload['parcel_receiver_address'] = $parcel->address ?? $parcel->full_address ?? null;
            $payload['parcel_receiver_phone'] = $parcel->phone ?? $parcel->tel ?? $parcel->contact_phone ?? null;
        }

        return $payload;
    }

    public function sellOrderSuccess($orderNo)
    {
        $order = DB::table('sell_orders')
            ->where('order_no', $orderNo)
            ->first();

        if (!$order) {
            abort(404);
        }

        $pickupDetail = DB::table('sell_order_pickup_details')
            ->where('sell_order_id', $order->id)
            ->first();

        $answers = DB::table('sell_order_answers')
            ->where('sell_order_id', $order->id)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('pages.sell-product-success', [
            'order' => $order,
            'pickupDetail' => $pickupDetail,
            'answers' => $answers,
        ]);
    }

    public function profile()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()
                ->route('login')
                ->with('error', 'กรุณาเข้าสู่ระบบก่อนใช้งาน');
        }

        /*
    |--------------------------------------------------------------------------
    | ราคาที่แสดงในหน้าโปรไฟล์
    |--------------------------------------------------------------------------
    | ใช้ราคาที่แอดมินปรับ/ยืนยันก่อน = admin_adjusted_price
    | ถ้าไม่มี ให้ใช้ final_estimate_price เดิม
    |--------------------------------------------------------------------------
    */
        $adminPriceColumns = [
            'admin_adjusted_price',
            'admin_confirmed_price',
            'confirmed_price',
            'admin_estimate_price',
            'approved_price',
            'final_price',
        ];

        $adminPriceColumn = null;

        foreach ($adminPriceColumns as $column) {
            if (Schema::hasColumn('sell_orders', $column)) {
                $adminPriceColumn = $column;
                break;
            }
        }

        $priceSelect = $adminPriceColumn
            ? "CASE 
                WHEN `{$adminPriceColumn}` IS NOT NULL AND `{$adminPriceColumn}` > 0 
                THEN `{$adminPriceColumn}` 
                ELSE `final_estimate_price` 
           END AS display_price"
            : "`final_estimate_price` AS display_price";

        $originalPriceSelect = Schema::hasColumn('sell_orders', 'original_final_estimate_price')
            ? "CASE 
                WHEN `original_final_estimate_price` IS NOT NULL AND `original_final_estimate_price` > 0 
                THEN `original_final_estimate_price` 
                ELSE `final_estimate_price` 
           END AS original_price"
            : "`final_estimate_price` AS original_price";

        $selectColumns = [
            'id',
            'order_no',
            'status',
            'pickup_panel',
            'final_estimate_price',
            'created_at',
        ];

        $optionalColumns = [
            'user_id',
            'summary_title',
            'summary_text',
            'brand_name',
            'model_name',
            'capacity',
            'sell_method_name',
            'customer_name',
            'customer_phone',
            'customer_email',
            'line_id',
            'admin_adjusted_price',
            'original_final_estimate_price',
            'price_adjustment_note',
            'price_adjusted_at',
        ];

        foreach ($optionalColumns as $column) {
            if (Schema::hasColumn('sell_orders', $column)) {
                $selectColumns[] = $column;
            }
        }

        $sellOrdersQuery = DB::table('sell_orders')
            ->select($selectColumns)
            ->selectRaw($priceSelect)
            ->selectRaw($originalPriceSelect);

        /*
    |--------------------------------------------------------------------------
    | ดึงเฉพาะคำสั่งขายของ user ที่ login เท่านั้น
    |--------------------------------------------------------------------------
    | ไม่ใช้ customer_email / customer_phone เพื่อกันข้อมูลคนอื่นหลุดมาแสดง
    |--------------------------------------------------------------------------
    */
        if (Schema::hasColumn('sell_orders', 'user_id')) {
            $sellOrdersQuery->where('user_id', $user->id);
        } else {
            $sellOrdersQuery->whereRaw('1 = 0');
        }

        $sellOrders = $sellOrdersQuery
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($order) {
                $statusLabels = [
                    'pending' => 'รอตรวจสอบ',
                    'price_adjusted' => 'แอดมินปรับราคาแล้ว',
                    'confirmed' => 'ยืนยันแล้ว',
                    'approved' => 'ยืนยันแล้ว',
                    'processing' => 'กำลังดำเนินการ',
                    'completed' => 'สำเร็จ',
                    'success' => 'สำเร็จ',
                    'cancelled' => 'ยกเลิก',
                    'canceled' => 'ยกเลิก',
                    'rejected' => 'ปฏิเสธ',
                ];

                $pickupLabels = [
                    'store' => 'รับซื้อถึงที่',
                    'bts_mrt' => 'รับซื้อตาม BTS/MRT',
                    'ems' => 'จัดส่งพัสดุมาที่ศูนย์ใหญ่',
                ];

                $summaryTitle = $order->summary_title ?? null;

                if (empty($summaryTitle)) {
                    $summaryTitle = trim(
                        ($order->brand_name ?? '') . ' ' .
                            ($order->model_name ?? '') . ' ' .
                            ($order->capacity ?? '')
                    );
                }

                $displayPrice = (float) ($order->display_price ?? $order->final_estimate_price ?? 0);
                $originalPrice = (float) ($order->original_price ?? $order->final_estimate_price ?? 0);
                $adminAdjustedPrice = (float) ($order->admin_adjusted_price ?? 0);

                $order->type_label = 'ขาย';
                $order->status_label = $statusLabels[$order->status] ?? ($order->status ?? 'รอตรวจสอบ');
                $order->pickup_label = $pickupLabels[$order->pickup_panel] ?? ($order->sell_method_name ?? '-');
                $order->title = $summaryTitle ?: '-';
                $order->display_price = $displayPrice;
                $order->original_price = $originalPrice;
                $order->has_admin_price = $adminAdjustedPrice > 0;
                $order->price_source_label = $adminAdjustedPrice > 0
                    ? 'ราคาที่แอดมินยืนยัน'
                    : 'ราคาประเมินเดิม';

                return $order;
            });

        $completedStatuses = [
            'completed',
            'success',
        ];

        $profileStats = [
            'sell_order_count' => $sellOrders->count(),
            'completed_order_count' => $sellOrders
                ->filter(function ($order) use ($completedStatuses) {
                    return in_array($order->status, $completedStatuses, true);
                })
                ->count(),
            'total_sell_amount' => $sellOrders->sum('display_price'),
        ];

        /*
    |--------------------------------------------------------------------------
    | คำสั่งขายที่ user สามารถรีวิวได้
    |--------------------------------------------------------------------------
    | เงื่อนไข:
    | - เป็นออเดอร์ของ user นี้
    | - สถานะ completed / success
    | - ยังไม่มีรีวิวใน sell_order_reviews
    |--------------------------------------------------------------------------
    */
        $reviewableOrders = collect();

        if (
            Schema::hasTable('sell_orders') &&
            Schema::hasTable('sell_order_reviews') &&
            Schema::hasColumn('sell_orders', 'user_id')
        ) {
            $reviewableOrders = DB::table('sell_orders')
                ->leftJoin('sell_order_reviews', function ($join) {
                    $join->on('sell_orders.id', '=', 'sell_order_reviews.sell_order_id')
                        ->whereNull('sell_order_reviews.deleted_at');
                })
                ->where('sell_orders.user_id', $user->id)
                ->whereIn('sell_orders.status', ['completed', 'success'])
                ->whereNull('sell_order_reviews.id')
                ->select([
                    'sell_orders.id',
                    'sell_orders.order_no',
                    'sell_orders.summary_title',
                    'sell_orders.brand_name',
                    'sell_orders.model_name',
                    'sell_orders.capacity',
                    'sell_orders.final_estimate_price',
                    'sell_orders.created_at',
                ])
                ->orderByDesc('sell_orders.created_at')
                ->get()
                ->map(function ($order) {
                    $title = $order->summary_title;

                    if (empty($title)) {
                        $title = trim(
                            ($order->brand_name ?? '') . ' ' .
                                ($order->model_name ?? '') . ' ' .
                                ($order->capacity ?? '')
                        );
                    }

                    $order->title = $title ?: $order->order_no;

                    return $order;
                });
        }

        /*
    |--------------------------------------------------------------------------
    | รีวิวของ user
    |--------------------------------------------------------------------------
    | ใช้เฉพาะตาราง sell_order_reviews ที่สร้างใหม่
    |--------------------------------------------------------------------------
    */
        $reviews = collect();

        if (
            Schema::hasTable('sell_order_reviews') &&
            Schema::hasTable('sell_orders')
        ) {
            $reviews = DB::table('sell_order_reviews')
                ->leftJoin('sell_orders', 'sell_order_reviews.sell_order_id', '=', 'sell_orders.id')
                ->where('sell_order_reviews.user_id', $user->id)
                ->whereNull('sell_order_reviews.deleted_at')
                ->select([
                    'sell_order_reviews.id',
                    'sell_order_reviews.sell_order_id',
                    'sell_order_reviews.user_id',
                    'sell_order_reviews.reviewed_by_type',
                    'sell_order_reviews.reviewed_by_admin_id',
                    'sell_order_reviews.customer_name',
                    'sell_order_reviews.customer_phone',
                    'sell_order_reviews.rating',
                    'sell_order_reviews.title',
                    'sell_order_reviews.comment',
                    'sell_order_reviews.image',
                    'sell_order_reviews.is_displayed',
                    'sell_order_reviews.is_active',
                    'sell_order_reviews.created_at',

                    'sell_orders.order_no',
                    'sell_orders.summary_title',
                    'sell_orders.brand_name',
                    'sell_orders.model_name',
                    'sell_orders.capacity',
                ])
                ->orderByDesc('sell_order_reviews.created_at')
                ->get()
                ->map(function ($review) {
                    $orderTitle = $review->summary_title;

                    if (empty($orderTitle)) {
                        $orderTitle = trim(
                            ($review->brand_name ?? '') . ' ' .
                                ($review->model_name ?? '') . ' ' .
                                ($review->capacity ?? '')
                        );
                    }

                    $review->order_title = $orderTitle ?: ($review->order_no ?? '-');

                    return $review;
                });
        }

        return view('profile.index', compact(
            'user',
            'sellOrders',
            'profileStats',
            'reviews',
            'reviewableOrders'
        ));
    }
    public function storeSellOrderReview(Request $request)
    {
        $request->validate([
            'sell_order_id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'comment' => ['required', 'string', 'max:3000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'sell_order_id.required' => 'กรุณาเลือกคำสั่งขายที่ต้องการรีวิว',
            'rating.required' => 'กรุณาให้คะแนนรีวิว',
            'rating.integer' => 'คะแนนรีวิวไม่ถูกต้อง',
            'rating.min' => 'คะแนนต้องไม่น้อยกว่า 1',
            'rating.max' => 'คะแนนต้องไม่เกิน 5',
            'comment.required' => 'กรุณากรอกข้อความรีวิว',
            'comment.max' => 'ข้อความรีวิวต้องไม่เกิน 3000 ตัวอักษร',
            'image.image' => 'ไฟล์ต้องเป็นรูปภาพเท่านั้น',
            'image.mimes' => 'รูปภาพต้องเป็นไฟล์ jpg, jpeg, png หรือ webp',
            'image.max' => 'รูปภาพต้องมีขนาดไม่เกิน 4MB',
        ]);

        $userId = Auth::id();

        if (!$userId) {
            return redirect()
                ->route('login')
                ->with('error', 'กรุณาเข้าสู่ระบบก่อนรีวิว');
        }

        DB::beginTransaction();

        try {
            $order = DB::table('sell_orders')
                ->where('id', $request->sell_order_id)
                ->where('user_id', $userId)
                ->first();

            if (!$order) {
                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'ไม่พบคำสั่งขายของคุณ หรือคุณไม่มีสิทธิ์รีวิวรายการนี้');
            }

            $allowStatuses = [
                'completed',
                'success',
            ];

            if (!in_array($order->status, $allowStatuses, true)) {
                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'สามารถรีวิวได้เฉพาะคำสั่งขายที่สำเร็จแล้วเท่านั้น');
            }

            $reviewExists = DB::table('sell_order_reviews')
                ->where('sell_order_id', $order->id)
                ->exists();

            if ($reviewExists) {
                DB::rollBack();

                return redirect()
                    ->back()
                    ->with('error', 'คำสั่งขายนี้ถูกรีวิวแล้ว ไม่สามารถรีวิวซ้ำได้');
            }

            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('sell-order-reviews', 'public');
            }

            DB::table('sell_order_reviews')->insert([
                'sell_order_id' => $order->id,
                'user_id' => $userId,
                'reviewed_by_type' => 'user',
                'reviewed_by_admin_id' => null,
                'customer_name' => $order->customer_name ?? null,
                'customer_phone' => $order->customer_phone ?? null,
                'rating' => (int) $request->rating,
                'title' => $request->title,
                'comment' => $request->comment,
                'image' => $imagePath,
                'is_displayed' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('profile')
                ->with('success', 'ส่งรีวิวเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'ไม่สามารถส่งรีวิวได้: ' . $e->getMessage());
        }
    }
}
