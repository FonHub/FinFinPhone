<?php

namespace App\Http\Controllers;

use App\Models\AdminMenu;
use App\Models\AdminMenuPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->orderByDesc('id')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $user = new User();

        $menus = AdminMenu::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.users.form', [
            'user' => $user,
            'menus' => $menus,
            'mode' => 'create',
            'permissionMap' => [],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'status' => ['nullable', 'in:0,1'],
            'is_super_admin' => ['nullable', 'in:0,1'],
            'permissions' => ['nullable', 'array'],
        ], [
            'name.required' => 'กรุณากรอกชื่อผู้ใช้งาน',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องไม่น้อยกว่า 6 ตัวอักษร',
        ]);

        $menus = AdminMenu::query()
            ->where('status', 1)
            ->get();

        DB::beginTransaction();

        try {
            $user = User::query()->create([
                'name' => trim($validated['name']),
                'email' => trim($validated['email']),
                'password' => Hash::make($validated['password']),
                'status' => (int) $request->input('status', 1),
                'is_super_admin' => (int) $request->input('is_super_admin', 0),
            ]);

            $permissions = $request->input('permissions', []);

            foreach ($menus as $menu) {
                $row = $permissions[$menu->id] ?? [];

                AdminMenuPermission::query()->create([
                    'admin_user_id' => $user->id,
                    'admin_menu_id' => $menu->id,
                    'can_view' => !empty($row['can_view']) ? 1 : 0,
                    'can_create' => !empty($row['can_create']) ? 1 : 0,
                    'can_update' => !empty($row['can_update']) ? 1 : 0,
                    'can_delete' => !empty($row['can_delete']) ? 1 : 0,
                ]);
            }

            DB::commit();

            return redirect('admin/user')
                ->with('success', 'เพิ่มผู้ใช้งานหลังบ้านเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถเพิ่มผู้ใช้งานได้: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = User::query()->findOrFail($id);

        $menus = AdminMenu::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $permissionMap = AdminMenuPermission::query()
            ->where('admin_user_id', $user->id)
            ->get()
            ->keyBy('admin_menu_id')
            ->map(function ($row) {
                return [
                    'can_view' => (int) $row->can_view,
                    'can_create' => (int) $row->can_create,
                    'can_update' => (int) $row->can_update,
                    'can_delete' => (int) $row->can_delete,
                ];
            })
            ->toArray();

        return view('admin.users.form', [
            'user' => $user,
            'menus' => $menus,
            'mode' => 'edit',
            'permissionMap' => $permissionMap,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::query()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'status' => ['nullable', 'in:0,1'],
            'is_super_admin' => ['nullable', 'in:0,1'],
            'permissions' => ['nullable', 'array'],
        ], [
            'name.required' => 'กรุณากรอกชื่อผู้ใช้งาน',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'password.min' => 'รหัสผ่านต้องไม่น้อยกว่า 6 ตัวอักษร',
        ]);

        $menus = AdminMenu::query()
            ->where('status', 1)
            ->get();

        DB::beginTransaction();

        try {
            $payload = [
                'name' => trim($validated['name']),
                'email' => trim($validated['email']),
                'status' => (int) $request->input('status', 1),
                'is_super_admin' => (int) $request->input('is_super_admin', 0),
            ];

            if ($request->filled('password')) {
                $payload['password'] = Hash::make($request->input('password'));
            }

            $user->update($payload);

            AdminMenuPermission::query()
                ->where('admin_user_id', $user->id)
                ->delete();

            $permissions = $request->input('permissions', []);

            foreach ($menus as $menu) {
                $row = $permissions[$menu->id] ?? [];

                AdminMenuPermission::query()->create([
                    'admin_user_id' => $user->id,
                    'admin_menu_id' => $menu->id,
                    'can_view' => !empty($row['can_view']) ? 1 : 0,
                    'can_create' => !empty($row['can_create']) ? 1 : 0,
                    'can_update' => !empty($row['can_update']) ? 1 : 0,
                    'can_delete' => !empty($row['can_delete']) ? 1 : 0,
                ]);
            }

            DB::commit();

            return redirect('admin/user')
                ->with('success', 'แก้ไขผู้ใช้งานหลังบ้านเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถแก้ไขผู้ใช้งานได้: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
        ], [
            'id.required' => 'ไม่พบรหัสผู้ใช้งาน',
            'id.integer' => 'รหัสผู้ใช้งานไม่ถูกต้อง',
            'id.exists' => 'ไม่พบผู้ใช้งานในระบบ',
        ]);

        DB::beginTransaction();

        try {
            $user = User::query()->findOrFail($request->id);

            AdminMenuPermission::query()
                ->where('admin_user_id', $user->id)
                ->delete();

            $user->delete();

            DB::commit();

            return redirect('admin/user')
                ->with('success', 'ลบผู้ใช้งานเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'ไม่สามารถลบผู้ใช้งานได้: ' . $e->getMessage());
        }
    }
}