<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAdminController extends Controller
{
    public function index()
    {
        return view('admin.pengguna', [
            'users' => User::whereIn('role', ['admin', 'super_admin'])->orderBy('id')->get(),
            'agencies' => Agency::orderBy('name')->get(),
        ]);
    }

    public function store(Request $r)
    {
        $d = $r->validate(['name' => 'required|max:100', 'email' => 'required|email|unique:users,email',
            'password' => 'required|min:10|max:100', 'agency_id' => 'required|exists:agencies,id']);
        $u = new User;
        $u->name = $d['name'];
        $u->email = $d['email'];
        $u->password = Hash::make($d['password']);
        $u->forceFill(['role' => 'admin', 'is_active' => true, 'agency_id' => $d['agency_id']])->save();
        AuditLog::record('user.create_admin', $u);

        return back()->with('ok', 'Admin Instansi dibuat.');
    }

    public function toggle(User $user)
    {
        abort_if($user->id === auth()->id(), 422, 'Tidak dapat menonaktifkan diri sendiri.');
        $user->forceFill(['is_active' => ! $user->is_active])->save();
        AuditLog::record('user.toggle', $user, ['active' => $user->is_active]);

        return back();
    }
}
