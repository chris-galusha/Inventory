<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use Auth;
use Log;
use App\Role;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{

    public function __construct () {
      // User must be an admin to use this controller
      $this->middleware('admin');
    }

    public function index() {
      $users = User::all();
      return view('admin.user.index', compact('users'));
    }

    public function show(User $user) {
      return view('admin.user.show', compact('user'));
    }

    public function store(UserStoreRequest $request) {

      $name = $request->get('name');
      $email = $request->get('email');
      $role = Role::where('name', $request->get('role'))->first();
      $password = Hash::make($request->get('password'));

      $user = User::create([
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'role_id' => $role->id
      ]);

      $message = Auth::user()->name." created User: $user->name.";
      Log::debug($message);
      session()->flash('message', $message);

      return redirect('/users');
    }

    public function create() {
      return view('admin.user.create');
    }

    public function confirmDelete(User $user) {

      if ($user === null) {
        $error = 'No user provided';
        return back()->withErrors($error)->withInput(request()->all());
      } else {
        return view('admin/user/confirmDelete', compact('user'));
      }
    }

    public function destroy(User $user) {
      $user->delete();

      $message = Auth::user()->name." deleted User: $user->name.";
      Log::debug($message);
      session()->flash('message', $message);

      return redirect('/users');
    }

    public function edit(User $user) {
      return view('admin.user.edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, User $user) {

      $name = $request->get('name');
      $email = $request->get('email');
      $role = Role::where('name', $request->get('role'))->first();
      $password = $request->get('password');

      if ($password !== null) {
        $password = Hash::make($password);
      }

      if ($user->isAdmin() && !$role->isAdmin() && isOnlyOneAdmin()) {
        $error = 'There must be at least one Admin user.';
        return back()->withErrors($error)->withInput(request()->all());
      }

      if (areSameUser($user, Auth::user()) && $role != Auth::user()->role) {
        $error = 'You cannot edit your own role.';
        return back()->withErrors($error)->withInput(request()->all());
      }

      $user->email = $email;
      $user->name = $name;
      $user->setRole($role);
      if ($password !== null) {
        $user->password = $password;
      }
      $user->save();

      $message = Auth::user()->name." updated User: $user->name.";
      Log::debug($message);
      session()->flash('message', $message);

      return redirect('/users');
    }
}
