<?php

namespace App\Http\Controllers;

use App\User;
use http\Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserRegisterRequest;

class UserController extends Controller
{
    protected $user;

    /**
     * UserController constructor.
     * @param User $users
     */
    public function __construct(User $users)
    {
        $this->middleware('auth:api', ['except' => ['register']]);
        $this->user = $users;
    }

    public function index()
    {
        return $this->user->orderBy('id','DESC')->with('roles')->paginate(50);
    }

    /**
     * List User by ID whith Roles and Permissions
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $sistema = $this->user
            ->with('roles.permissions')
            ->findOrFail($id);

        return response()->json($sistema);
    }

    /**
     * @param UserRegisterRequest $request
     * @return JsonResponse
     */
    public function register(UserRegisterRequest $request)
    {
        try {
            $input = $request->only('name', 'email', 'password');
            $input['password'] = app('hash')->make($input['password']);

            if( $user = $this->user->create($input) ) {

                foreach ($request->input('roles') as $key => $value) {
                    $user->attachRole($value);
                }

                $code = 200;
                $output = [
                    'code' => $code,
                    'user' => $user,
                    'message' => 'Usuário cadastrado com sucesso!!'
                ];
            } else {
                $code = 500;
                $output = [
                    'code' => $code,
                    'message' => 'Erro ao cadastrar o usuário!!'
                ];
            }

        } catch (Exception $e) {
            //dd($e->getMessage());
            $code = 500;
            $output = [
                'code' => $code,
                'message' => 'Erro ao cadastrar o usuário!!'
            ];
        }

        return response()->json($output, $code);

    }
}
