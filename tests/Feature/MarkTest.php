<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MarkTest extends TestCase
{
    /**
     * Проверка рендеринга страницы марок
     */
    public function test_mark_page_is_displayed(): void
    {
        $user = User::factory()->admin()->create();
        $response = $this
            ->actingAs($user)
            ->get('/admin/marks');

        $response->assertStatus(200);
    }
    /**
     * Проверка создания новой марки
     */
    public function test_(): void
    {
        $user = User::factory()->admin()->create();

    }
}
