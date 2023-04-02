<?php

namespace Tests\Feature;

use Tests\TestCase;

class UsersApiTest extends TestCase
{
    public function test_create_user()
    {
        $response = $this->postJson('/api/users', [
            "name" => "Test User",
            "email" => "test-user-". rand() . "@test.com",
            "password" => 12345
        ]);

        $response->assertStatus(422);

        $response = $this->postJson('/api/users', [
            "name" => "Test User",
            "email" => "testUser-". rand() . "@test.com",
            "password" => 123456
        ]);

        $response->assertStatus(201);
    }

    public function test_get_users()
    {
        $response = $this->get('/api/users');

        $response->assertStatus(200);
    }

    public function test_get_user()
    {
        $response = $this->postJson('/api/users', [
            "name" => "Test User",
            "email" => "testUser-". rand() . "@test.com",
            "password" => 123456
        ]);

        $response->assertStatus(201);

        $user = json_decode($response->getContent(), true);
        if ($user['id']) {
            $response = $this->get('/api/users/' . $user['id']);
            $response->assertStatus(200);
        }
    }

    public function test_update_user()
    {
        $response = $this->postJson('/api/users', [
            "name" => "Test User",
            "email" => "testUser-". rand() . "@test.com",
            "password" => 123456
        ]);

        $response->assertStatus(201);

        $user = json_decode($response->getContent(), true);
        if ($user['id']) {
            $response = $this->patchJson('/api/users/' . $user['id'], [
                "name" => "Test"
            ]);
            $response->assertStatus(200);
        }
    }
}
