<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_task()
{
    $user = User::factory()->create();
    $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/tasks', [
            'title' => 'Nova tarefa de teste',
            'status' => 'pending',
        ]);

    $response->assertStatus(201)
             ->assertJsonFragment(['title' => 'Nova tarefa de teste']);
}

    public function test_user_can_list_own_tasks()
    {
        $user = User::factory()->create();
        Task::factory()->count(3)->create(['user_id' => $user->id]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_user_can_update_task()
{
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->putJson("/api/tasks/{$task->id}", [
            'title' => 'Atualizada',
            'status' => 'completed',
        ]);

    $response->assertStatus(200)
             ->assertJsonFragment(['title' => 'Atualizada']);
}


    public function test_user_can_delete_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Tarefa removida.']);
    }
}
