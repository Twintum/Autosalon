<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\CarModel;

class ModelTest extends TestCase
{
    /**
     * Проверка рендеринга страницы моделей
     */
    public function test_model_page_is_displayed(): void
    {
        $user = User::factory()->admin()->create();
        $response = $this
            ->actingAs($user)
            ->get(route('model.index'));

        $response->assertStatus(200);
    }

    /**
     * Проверка создания новой модели
     */
    public function test_successful_upload()
    {
        $user = User::factory()->admin()->create();

        Storage::fake('public');
        $file = UploadedFile::fake()->image('photo.jpg');
        $data = [
            'mark_id' => 1,
            'model' => 'Test Model',
            'price' => 10000,
            'photo' => $file,
            'color' => 'Red',
            'fuel_tank' => 50,
            'mileage' => 10000,
            'year' => 2020,
            'transmission' => 'automatic',
            'drive' => 'FWD',
            'discount' => 10,
        ];
        $response = $this->actingAs($user)->post(route('model.upload'), $data);
        $this->assertDatabaseHas('car_models', [
            'model' => 'Test Model',
            'price' => 10000,
            'color' => 'Red',
            'fuel_tank' => 50,
            'mileage' => 10000,
            'year' => 2020,
            'transmission' => 'automatic',
            'drive' => 'FWD',
            'discount' => 10,
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Модель успешно добавлена');
    }

    /**
     * Проверка ошибки валидации при создании
     */
    public function test_validation_errors()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->post(route('model.upload'), []);
        $response->assertSessionHasErrors([
            'mark_id',
            'model',
            'price',
            'photo',
            'color',
            'fuel_tank',
            'mileage',
            'year',
            'transmission',
            'drive',
        ]);
    }

    /**
     * Проверка ошибки при неправильном формате файла
     */
    public function test_invalid_file_type()
    {
        $user = User::factory()->admin()->create();

        $file = UploadedFile::fake()->create('document.pdf');
        $data = [
            'mark_id' => 1,
            'model' => 'Test Model',
            'price' => 10000,
            'photo' => $file,
            'color' => 'Red',
            'fuel_tank' => 50,
            'mileage' => 10000,
            'year' => 2020,
            'transmission' => 'automatic',
            'drive' => 'FWD',
            'discount' => 10,
        ];
        $response = $this->actingAs($user)->post(route('model.upload'), $data);
        $response->assertSessionHasErrors(['photo']);
    }

    /**
     * Проверка ошибки при слишком большом файле
     */
    public function test_file_too_large()
    {
        $user = User::factory()->admin()->create();

        $file = UploadedFile::fake()->create('photo.jpg', 3000);
        $data = [
            'mark_id' => 1,
            'model' => 'Test Model',
            'price' => 10000,
            'photo' => $file,
            'color' => 'Red',
            'fuel_tank' => 50,
            'mileage' => 10000,
            'year' => 2020,
            'transmission' => 'automatic',
            'drive' => 'FWD',
            'discount' => 10,
        ];
        $response = $this->actingAs($user)->post(route('model.upload'), $data);
        $response->assertSessionHasErrors(['photo']);
    }

    /**
     * Проверка успешного удаления модели
     */
    public function test_successful_destroy()
    {
        $user = User::factory()->admin()->create();

        $carModel = CarModel::factory()->create();
        $data = [
            'id' => $carModel->id,
        ];
        $response = $this->actingAs($user)->delete(route('model.destroy'), $data);
        $this->assertDatabaseMissing('car_models', [
            'id' => $carModel->id,
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Модель успешно удалена');
    }

    /**
     * Проверка ошибки валидации при удалении
     */
    public function test_delete_validation_errors()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->delete(route('model.destroy'), []);
        $response->assertSessionHasErrors(['id']);
    }

    /**
     * Проверка ошибки неправильного айди
     */
    public function test_destroy_non_existing_model()
    {
        $user = User::factory()->admin()->create();

        $data = [
            'id' => 999999,
        ];
        $response = $this->actingAs($user)->delete(route('model.destroy'), $data);
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Модель успешно удалена');
        $this->assertDatabaseMissing('car_models', [
            'id' => 999999,
        ]);
    }

    /**
     * Проверка ошибки невалидного айди
     */
    public function test_destroy_with_invalid_id()
    {
        $user = User::factory()->admin()->create();

        $data = [
            'id' => 'invalid_id',
        ];
        $response = $this->actingAs($user)->delete(route('model.destroy'), $data);
        $response->assertSessionHasErrors(['id']);
    }
}
