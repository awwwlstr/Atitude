<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kejujuran',
                'description' => 'Membentuk pribadi yang lurus hati, dapat dipercaya dalam perkataan maupun perbuatan, dan menjunjung tinggi kebenaran.',
                'icon' => 'bi-shield-check',
                'color' => 'success',
            ],
            [
                'name' => 'Disiplin',
                'description' => 'Menanamkan ketaatan pada tata tertib, manajemen waktu yang efektif, serta konsistensi dalam menjalankan kewajiban.',
                'icon' => 'bi-clock-history',
                'color' => 'primary',
            ],
            [
                'name' => 'Tanggung Jawab',
                'description' => 'Kesadaran seseorang atas perbuatan yang disengaja maupun tidak, serta kesiapan menerima konsekuensi tugas.',
                'icon' => 'bi-briefcase',
                'color' => 'warning',
            ],
            [
                'name' => 'Kerja Sama',
                'description' => 'Kemampuan bergotong royong, menghargai pendapat orang lain, dan bersinergi demi mencapai tujuan bersama.',
                'icon' => 'bi-people',
                'color' => 'info',
            ],
            [
                'name' => 'Sopan Santun',
                'description' => 'Etika budi pekerti luhur, tutur kata yang santun, dan penghormatan kepada sesama manusia di lingkungan masyarakat.',
                'icon' => 'bi-heart-fill',
                'color' => 'danger',
            ],
            [
                'name' => 'Kemandirian',
                'description' => 'Kemampuan bertindak tanpa bergantung berlebihan pada orang lain, berinisiatif, dan mampu memecahkan masalah sendiri.',
                'icon' => 'bi-person-check',
                'color' => 'indigo',
            ],
            [
                'name' => 'Kepedulian',
                'description' => 'Rasa empati, kepekaan terhadap kebutuhan lingkungan sekitar, dan keikhlasan membantu sesama yang membutuhkan.',
                'icon' => 'bi-hand-thumbs-up',
                'color' => 'teal',
            ],
            [
                'name' => 'Percaya Diri',
                'description' => 'Keyakinan teguh atas potensi diri, keberanian mengutarakan ide positif, dan optimisme dalam menghadapi tantangan.',
                'icon' => 'bi-lightning-charge',
                'color' => 'purple',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'icon' => $cat['icon'],
                    'color' => $cat['color'],
                ]
            );
        }
    }
}
