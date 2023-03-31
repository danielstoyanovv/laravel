<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(array $data)
    {
        $this->validateApiCreateRequest($data);
        if ($user = $this->createUser($data)) {
            return response()->json($user, Response::HTTP_CREATED);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if ($request->getMethod() == 'POST') {
                if ($data = json_decode($request->getContent(), true)) {
                    return $this->create($data);
                }
                throw new BadRequestHttpException("No data is send");
            }
        } catch (UnprocessableEntityHttpException $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (BadRequestHttpException $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return response()->json('Something happened', Response::HTTP_FORBIDDEN);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            if ($user = User::findOrFail($id)) {
                return response()->json($user);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if ($request->getMethod() == 'PATCH') {
                if ($user = User::findOrFail($id)) {
                    if ($data = json_decode($request->getContent(), true)) {
                        if ($user = $this->updateUser($data, $user)) {
                            return response()->json($user);
                        }
                    }
                    throw new BadRequestHttpException("No data is send");
                }
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param array $data
     * @return void
     */
    private function validateApiCreateRequest(array $data)
    {
        if (empty($data['name'])) {
            throw new UnprocessableEntityHttpException("'name' is required field");
        }

        if (empty($data['email'])) {
            throw new UnprocessableEntityHttpException("'email' is required field");
        }
        $this->validateEmail($data['email']);

        $this->checkIfEmailExists($data['email']);

        if (empty($data['password'])) {
            throw new UnprocessableEntityHttpException("'password' is required field");
        }

        $this->validatePassword($data['password']);
    }

    private function validatePassword(string $password)
    {
        if (strlen($password) < 6) {
            throw new UnprocessableEntityHttpException("'password' minimum length is 6 characters");
        }
    }

    /**
     * @param string $email
     * @return void
     */
    private function validateEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new UnprocessableEntityHttpException("'email' is not valid");
        }
    }

    /**
     * @param string $email
     * @return void
     */
    private function checkIfEmailExists(string $email)
    {
        if ($user = User::where('email', $email)-> first()) {
            throw new UnprocessableEntityHttpException(
                sprintf("'%s' already exists", $email)
            );
        }
    }

    /**
     * @param array $data
     * @return User
     */
    private function createUser(array $data): User
    {
        $user = User::create(array(
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
            'email' => $data['email']
        ));

        return $user;
    }

    /**
     * @param array $data
     * @param User $user
     * @return User
     */
    private function updateUser(array $data, User $user): User
    {
        if (!empty($data['name']) && $user->getAttributes()['name'] != $data['name']) {
            $user->update(['name' => $data['name']]);
        }

        if (!empty($data['email']) && $user->getAttributes()['email'] != $data['email']) {
            $this->validateEmail($data['email']);
            $this->checkIfEmailExists($data['email']);
            $user->update(['email' => $data['email']]);
        }

        if (!empty($data['password']) && $user->getAttributes()['password'] != Hash::make($data['password'])) {
            $this->validatePassword($data['password']);
            $user->update(['password' => Hash::make($data['password'])]);
        }

        return $user;
    }
}
