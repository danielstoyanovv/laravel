<?php

namespace App\Http\Service;

use App\Models\User;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Illuminate\Support\Facades\Hash;

class UsersService
{
    /**
     * @param array $data
     * @return void
     */
    public function validateApiCreateRequest(array $data): void
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
    private function validateEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new UnprocessableEntityHttpException("'email' is not valid");
        }
    }

    /**
     * @param string $email
     * @return void
     */
    private function checkIfEmailExists(string $email): void
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
    public function createUser(array $data): User
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
    public function updateUser(array $data, User $user): User
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
