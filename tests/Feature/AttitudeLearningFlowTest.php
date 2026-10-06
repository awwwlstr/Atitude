<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Material;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\MaterialSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttitudeLearningFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UserSeeder::class,
            CategorySeeder::class,
            MaterialSeeder::class,
        ]);
    }

    public function test_public_pages_accessible()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->get('/materi');
        $response->assertStatus(200);

        $response = $this->get('/kategori');
        $response->assertStatus(200);

        $response = $this->get('/tentang');
        $response->assertStatus(200);

        $publishedMaterial = Material::where('status', 'published')->first();
        $response = $this->get('/materi/' . $publishedMaterial->slug . '/preview');
        $response->assertStatus(200);
    }

    public function test_user_can_register_and_login()
    {
        $response = $this->post('/register', [
            'name' => 'Budi Baru',
            'username' => 'budibaru',
            'email' => 'budibaru@attitude.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/user/dashboard');
        $this->assertAuthenticated();

        $user = User::where('email', 'budibaru@attitude.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('user', $user->role);
    }

    public function test_user_can_access_learning_and_submit_quiz()
    {
        $user = User::where('email', 'ahmad@attitude.com')->first();
        $material = Material::where('status', 'published')->has('questions')->first();

        $response = $this->actingAs($user)->get("/user/materi/{$material->id}/belajar");
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get("/user/materi/{$material->id}/soal");
        $response->assertStatus(200);

        $answers = [];
        foreach ($material->questions as $q) {
            $opt = $q->options->firstWhere('is_correct', true);
            if ($opt) {
                $answers[$q->id] = $opt->id;
            }
        }

        $response = $this->actingAs($user)->post("/user/materi/{$material->id}/submit", [
            'answers' => $answers,
        ]);

        $response->assertRedirect(route('user.hasil', ['material_id' => $material->id]));

        $this->assertDatabaseHas('progress', [
            'user_id' => $user->id,
            'material_id' => $material->id,
            'status' => 'completed',
        ]);
    }

    public function test_pembuat_materi_workflow()
    {
        $pembuat = User::where('role', 'pembuat_materi')->first();
        $category = Category::first();

        // 1. Pembuat creates draft material
        $response = $this->actingAs($pembuat)->post('/pembuat/materi', [
            'title' => 'Materi Karakter Baru',
            'category_id' => $category->id,
            'description' => 'Deskripsi pembelajaran karakter baru',
            'content' => 'Penjelasan konsep modul baru',
            'action_type' => 'save_draft',
        ]);

        $material = Material::where('title', 'Materi Karakter Baru')->first();
        $this->assertNotNull($material);
        $this->assertEquals('draft', $material->status);

        // 2. Add content to material
        $response = $this->actingAs($pembuat)->post("/pembuat/materi/{$material->id}/upload", [
            'type' => 'text',
            'title' => 'Modul 1 Pengantar',
            'content' => 'Uraian modul pengantar',
        ]);
        $response->assertSessionHas('success');

        // 3. Add question
        $response = $this->actingAs($pembuat)->post("/pembuat/materi/{$material->id}/soal", [
            'question' => 'Pertanyaan simulasi sikap?',
            'type' => 'multiple_choice',
            'points' => 20,
            'options' => ['Opsi A', 'Opsi B', 'Opsi C', 'Opsi D'],
            'correct_option' => 0,
        ]);
        $response->assertSessionHas('success');

        // 4. Submit for review
        $response = $this->actingAs($pembuat)->post("/pembuat/materi/{$material->id}/submit");
        $material->refresh();
        $this->assertEquals('pending', $material->status);
    }

    public function test_admin_review_and_approval_workflow()
    {
        $admin = User::where('role', 'admin')->first();
        $pendingMaterial = Material::where('status', 'pending')->first();

        $this->assertNotNull($pendingMaterial);

        // 1. Admin can view review page
        $response = $this->actingAs($admin)->get("/admin/materi/{$pendingMaterial->id}");
        $response->assertStatus(200);

        // 2. Admin asks for revision
        $response = $this->actingAs($admin)->post("/admin/materi/{$pendingMaterial->id}/revision", [
            'revision_note' => 'Mohon tambahkan lebih banyak contoh praktis.',
        ]);
        $pendingMaterial->refresh();
        $this->assertEquals('revision', $pendingMaterial->status);
        $this->assertEquals('Mohon tambahkan lebih banyak contoh praktis.', $pendingMaterial->revision_note);

        // 3. Admin approves & publishes
        $response = $this->actingAs($admin)->post("/admin/materi/{$pendingMaterial->id}/approve");
        $pendingMaterial->refresh();
        $this->assertEquals('published', $pendingMaterial->status);
        $this->assertNotNull($pendingMaterial->approved_at);
    }

    public function test_role_authorization_protection()
    {
        $user = User::where('role', 'user')->first();

        // User cannot access Admin dashboard
        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertRedirect('/user/dashboard');

        // User cannot access Pembuat dashboard
        $response = $this->actingAs($user)->get('/pembuat/dashboard');
        $response->assertRedirect('/user/dashboard');
    }
}
