<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user',
            'pseudo' => 'string|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 400);
        }

        $user = User::create([
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'pseudo' => $request->get('pseudo'),
            'password' => hash('sha256', $request->get('password')),
        ]);

        $token = $user->createToken('MyApp')->accessToken;

        return response()->json([
            "message" => "Ok",
            "data" => [
                "token" => $token, 
                "user" => $user
            ],
        ], 201);
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 400);
        }

        $user = User::whereEmail(request('email'))
            ->wherePassword(hash('sha256', request('password')))
            ->first();

        if (isset($user)) {
            $token = $user->createToken('MyApp')->accessToken;
            return response()->json([
                "message" => "Ok",
                "user" => $user,
                "token" => $token,
            ], 201);
        }
        return response()->json([
            "message" => "Not found",
        ], 404);
    }

    public function deleteUser($id)
    {
        $user = Auth::user();

        if ($user->id != $id) {
            return response()->json([
                "message" => 'Unauthorize',
            ], 401);
        }

        if ($user->delete()) {
            return response()->json([
                "message" => 'Ok',
            ], 200);
        }
        return response()->json([
            "message" => "Error during user deletion",
        ], 400);
    }

    public function updateUser($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'string',
            'pseudo' => 'string|nullable',
            'email' => 'string|email',
            'password' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first(),
            ], 400);
        }

        $user = Auth::user();

        if ($user->id != $id) {
            return response()->json([
                "message" => 'Unauthorize',
            ], 401);
        }
        
        $email = User::where('email', $request->email)->first();
        if (isset($email) && $user->id !== $email->id) {
            return response()->json([
                "message" => "Email already taken",
            ], 401);
        }

        if ($request->get('password') !== NULL)
            $request->merge(['password' => hash('sha256', $request->get('password'))]);

        User::where('id', $id)->update($request->all());

        return response()->json([
            "message" => "Ok",
            "data" => $user,
        ], 200);
    }

    public function getUsers(Request $request)
    {
        $perPage = $request->get('perPage') == null ? 5 : $request->get('perPage');
        $page = $request->get('page') == null ? 1 : $request->get('page');
        $pseudo = $request->get('pseudo');

        $userList = User::where('username', 'like','%' . $pseudo . '%')->paginate($perPage, ['*'], 'page', $page);

        if (empty($userList->items())) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $pager[] = [
            'current' => $userList->currentPage(),
            'total' => $userList->lastPage()
        ];

        if ($page > $userList->lastPage()) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }
        
        return response()->json([
            'message' => 'Ok', 
            'data' => $userList->items(), 
            'pager' => $pager
        ], 200);
    }

    public function getUser($id)
    {
        $connectedUser = Auth::user();

        if ($connectedUser->id == $id) {
            $user = User::where('id', $id)->first();
        } else {
            $user = User::where('id', $id)->select('id', 'pseudo', 'username', 'created_at')->first();
        }

        if ($user !== null) {
            return response()->json([
                "message" => "Ok",
                "data" => $user
            ], 200);  
        }

        return response()->json([
            "message" => "Not found",
        ], 404);
    }
}
