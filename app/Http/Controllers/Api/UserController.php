<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collaborators;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ValidateUsers;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function store(Request $request)
    {
        try{
                    $rules = [
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'unique:users', 'email:rfc,dns', 'max:100', 'string'],
                'password' => ['required', 'string'],
                'document' => ['integer'],
                'cell' => ['integer'],
                'address' => ['string'],
                'neighborhood' => ['string'],
                'birth' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            ];



            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }
            $domain = substr(strrchr(strtolower($request->email), "@"), 1);
            if (!checkdnsrr($domain, "MX")) {
                return response()->json([
                    'status' => false,
                    'errors' => ['El correo no es válido']
                ], 400);
            }

            $profileUser = Profile::where('document', $request->document)->first();

            if ($profileUser) {
                return response()->json([
                    'status' => false,
                    'errors' => ['Ya hay un usuario registrado con este documento']
                ], 409);
            }
            $collaborator = Collaborators::where('document', $request->document)->first();

            $validation = ValidateUsers::findOrFail(1);

            if ($validation && $validation->status == 0) {
                if (!$collaborator) {
                    return response()->json([
                        'status' => false,
                        'errors' => 'En el momento no está habilitado el registro'
                    ], 401);
                }
            }

            $user = new User([
                "name" => $request->name,
                "email" => strtolower($request->email),
                "password" => Hash::make($request->password)
            ]);

            $user->save();
            $role = Role::where('name', 'User')->first();
            $user->assignRole($role);
            $profile = new Profile([
                'document' => $request->document,
                'cell' => $request->cell,
                'address' => $request->address,
                'neighborhood' => $request->neighborhood,
                'birth' => $request->birth,
                'eps' => $request->eps ?? '',
                'reference' => $request->reference ?? '',
                'experience2022' => $request->experience2022 ?? false,
                'is_collaborator' => $collaborator ? true : false,
            ]);
            $user->profile()->save($profile);

            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'data' => $user,
                'token' => $user->createToken('Authorization')->plainTextToken
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function me()
    {
        $auth = auth()->user();
        $user = User::where('id', $auth->id)->with('profile')->first();
        return response()->json([
            'status' => true,
            'message' => 'Retriving User Informations',
            'data' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string']
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'errors' => ['Unauthorized']
            ], 401);
        }

        $user = User::where('email', $request->email)
            ->with('profile')
            ->first();


        return response()->json([
            'status' => true,
            'message' => 'User logged in successfully',
            'data' => $user,
            'token' => $user->createToken('Authorization')->plainTextToken
        ], 200);
    }

    public function logout()
    {
        auth()->user()->tokens->delete();
        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully'
        ], 200);
    }

    public function update(Request $request)
    {

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'string'],
            'cell' => ['integer'],
            'address' => ['string'],
            'neighborhood' => ['string'],
            'birth' => ['date'],
        ];



        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        try {
            $user = User::find($request->userId);
            //check if email exist in another user
            $userEmail = User::where('email', $request->email)->first();
            if ($userEmail && $userEmail->id != $user->id) {
                return response()->json([
                    'status' => false,
                    'errors' => ['Ya hay un usuario registrado con este correo']
                ], 409);
            }
            $user->email = $request->email;

            // Actualiza los campos del usuario
            $user->name = $request->name;

            // Guarda los cambios en el usuario
            $user->save();

            // Supongamos que también tienes el perfil asociado al usuario
            $profile = $user->profile;

            // Actualiza los campos del perfil
            $profile->cell = $request->cell;
            $profile->address = $request->address;
            $profile->neighborhood = $request->neighborhood;
            $profile->birth = $request->birth;
            $profile->eps = $request->eps ?? '';
            $profile->reference = $request->reference ?? '';
            $profile->experience2022 = $request->experience2022 ?? false;

            // Guarda los cambios en el perfil
            $profile->save();

            return response()->json([
                'status' => true,
                'message' => 'User update successfully',
                'data' => $user,
                'token' => $user->createToken('Authorization')->plainTextToken
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => 'User not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'An error occurred'
            ], 500);
        }
    }
}
