<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Technology;
use App\Models\User;
use App\Models\WorkExperience;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tu acceso para el panel
        User::factory()->create([
            'name' => 'Oscar Minjarez',
            'email' => 'oscar@admin.com',
        ]);

        // Projects
        Project::create([
            'title' => 'Nidya SaaS',
            'slug' => 'nidya-saas',
            'summary' => 'Plataforma multi-tenant open-source. Cada instancia corre de forma aislada en su propio droplet de Digital Ocean para máxima seguridad y rendimiento.',
            'content' => 'Detalle completo de la arquitectura aquí...',
            'stack' => ['Laravel 13', 'Vue.js', 'Docker', 'Digital Ocean'],
            'image_path' => '/img/nidya.png',
            'is_featured' => true,
            'ari_context' => 'Mi cliente inicial fue mi tío en entorno de producción. Además, el proyecto sirve como base para mentorear a nuevos talentos en contribución open-source.',
        ]);

        Project::create([
            'title' => 'Zanate Nunchi',
            'slug' => 'zanate-nunchi',
            'summary' => 'Mod de Minecraft enfocado en procesamiento de contexto con modelos de lenguaje. Integración asíncrona total.',
            'content' => 'Detalles de la implementación del mod...',
            'stack' => ['Java', 'Fabric', 'Ollama', 'LLM Local'],
            'image_path' => '/img/zanate.png',
            'is_featured' => true,
            'ari_context' => 'Procesamiento de contextos masivos asíncronos sin depender de APIs de terceros. Todo se maneja localmente.',
        ]);

        // Work Experience
        WorkExperience::create([
            'company' => 'Google Deepmind',
            'position' => 'Senior AI Researcher',
            'start_date' => '2023-01-01',
            'description' => 'Working on Advanced Agentic AI systems.',
            'ari_context' => 'Aquí es donde aprendí sobre la importancia de la autonomía en agentes IA.',
        ]);

        // Technologies
        Technology::create([
            'name' => 'Laravel',
            'slug' => 'laravel',
            'category' => 'Frontend/Backend',
            'logo_path' => '/logos/laravel.svg',
            'ari_context' => 'Mi framework favorito para el backend.',
        ]);

        Technology::create([
            'name' => 'Vue.js',
            'slug' => 'vuejs',
            'category' => 'Frontend',
            'logo_path' => '/logos/vuejs.svg',
            'ari_context' => 'Excelente reactividad y facilidad de uso.',
        ]);
    }
}
