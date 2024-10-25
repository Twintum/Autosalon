<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\{User, Mark};

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
            ->get(route('mark.index'));

        $response->assertStatus(200);
    }

    /**
     * Проверка создания новой марки
     */
    public function test_upload_mark(): void
    {
        $user = User::factory()->admin()->create();

        Storage::fake('public');
        $file = UploadedFile::fake()->image('photo.svg');
        $data = [
            'name' => 'Test Mark',
            'photo' => $file,
        ];
        $response = $this->actingAs($user)->post(route('mark.upload'), $data);
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Марка успешно добавлена');
        $this->assertDatabaseHas('marks', [
            'name' => 'Test Mark',
        ]);
    }

    /**
     * Проверка удаления модели
     */
    public function test_mark_can_be_hidden()
    {
        $user = User::factory()->admin()->create();
        $mark = Mark::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('mark.destroy'), ['id' => $mark->id]);
        $response->assertRedirect()
            ->assertSessionHas('success', 'Марка успешно скрыта');

        $this->assertFalse($mark->fresh()->visibility == true);
    }

    /**
     * Проверка ошибки удаления модели при неправильном ID
     */
    public function test_mark_not_found()
    {
        // Создаем пользователя с правами администратора
        $user = User::factory()->admin()->create();

        // Отправляем POST-запрос на метод destroy с несуществующим ID
        $response = $this->actingAs($user)
            ->delete(route('mark.destroy'), ['id' => 9999]);

        // Проверяем, что редирект произошел и сообщение об ошибке присутствует
        $response->assertRedirect()
            ->assertSessionHas('error', 'Марка не найдена');
    }

    /**
     * Проверка успешное редактирование марок
     */
    public function testSuccessfulMarkUpdate()
    {
        $user = User::factory()->admin()->create();
        $mark = Mark::factory()->create();

        Storage::fake('public');
        $file = UploadedFile::fake()->image('photo.svg');
        $data = [
            'id' => $mark->id,
            'name' => 'Updated Mark Name',
            'photo' => $file,
        ];
        $response = $this->actingAs($user)
            ->patch(route('mark.update'), $data);
        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('marks', [
            'id' => $mark->id,
            'name' => 'Updated Mark Name',
        ]);
    }

    /**
     * Проверка ошибку валидации при редактировании марки
     */
    public function testFailedMarkUpdate()
    {
        $user = User::factory()->admin()->create();
        $mark = Mark::factory()->create();

        $data = [
            'id' => $mark->id,
            'name' => '',
        ];
        $response = $this->actingAs($user)
            ->patch(route('mark.update'), $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('marks', [
            'id' => $mark->id,
            'name' => '',
        ]);
    }

    /**
     * Проверка ошибки на неправильный формат загружаемого файла при редактировании марок
     */
    public function testFailedMarkUpdateWithInvalidFile()
    {
        $user = User::factory()->admin()->create();
        $mark = Mark::factory()->create();

        Storage::fake('public');
        $file = UploadedFile::fake()->image('photo.png');
        $data = [
            'id' => $mark->id,
            'name' => 'Updated Mark Name',
            'photo' => $file,
        ];
        $response = $this->actingAs($user)
            ->patch(route('mark.update'), $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['photo']);
        $this->assertDatabaseMissing('marks', [
            'id' => $mark->id,
            'name' => 'Updated Mark Name',
        ]);

        // Проверяем, что файл не был загружен
        Storage::disk('public')->assertMissing('marks/' . $file->hashName());
    }
}
