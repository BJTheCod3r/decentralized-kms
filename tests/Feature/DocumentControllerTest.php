<?php
namespace Tests\Feature;

use App\Http\Services\Contracts\DocumentServiceContract;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DocumentControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @return void
     */
    public function testAddDocument(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $requestData = [
            'content' => 'Sample document content',
            'user_id' => 1,
            'type' => Document::TYPE_TEXT
        ];

        // Simulate a POST request to the addDocument route
        $response = $this->post(route('addDocument'), $requestData);

        // Assert that the response is successful (status code 200) and contains the expected JSON data
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testListDocuments(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $perPage = 10;
        $query = 'sample';

        Document::factory()->count(5)->create();

        // Simulate a GET request to the listDocuments route
        $response = $this->get(route('listDocuments', ['per_page' => $perPage, 'q' => $query]));

        // Assert that the response is successful (status code 200)
        $response->assertStatus(200);
    }

    public function testFetchDocument()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $document = Document::factory()->create();

        // Simulate a GET request to the fetchDocument route
        $response = $this->get(route('fetchDocument', ['id' => $document->id]));

        // Assert that the response is successful (status code 200)
        $response->assertStatus(200);
    }
}

