<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadPizzaTypesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_uploads_valid_csv_file()
    {
        // Create CSV string (no extra indentation, clean headers/rows)
        $csvContent = <<<CSV
pizza_type_id,name,category,ingredients
calabrese,Calabrese Supreme,Supreme,"Nduja Salami,Pancetta,Tomatoes"
CSV;

        $path = storage_path('app/test_pizza_types.csv');
        file_put_contents($path, $csvContent);

        $file = new \Illuminate\Http\UploadedFile(
            $path,
            'pizza_types.csv',
            'text/csv',
            null,
            true
        );

        $response = $this->postJson('/api/upload/pizza-types', [
            'file' => $file,
        ]);

        $response->dump();

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Pizza types uploaded.',
        ]);
    }


    /** @test */
    public function it_rejects_invalid_file()
    {
        $file = UploadedFile::fake()->create('not_a_csv.pdf', 100, 'application/pdf');

        $response = $this->postJson('/api/upload/pizza-types', [
            'file' => $file
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }
}
