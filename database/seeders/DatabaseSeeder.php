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
        // Truncate existing data to avoid duplicates if needed (optional but good for clean start)
        // Note: For a portfolio, usually we want exactly what's in the seeder.
        User::where('email', 'minjarez.dev@gmail.com')->delete();
        Project::truncate();
        WorkExperience::truncate();
        Technology::truncate();

        // Admin User
        User::factory()->create([
            'name' => 'Oscar Minjarez',
            'email' => 'minjarez.dev@gmail.com',
            'password' => bcrypt('password'), // Or whatever default password
        ]);

        // Projects
        Project::create([
            'title' => 'Nidya SaaS',
            'slug' => 'nidya-saas',
            'summary' => 'Plataforma multi-tenant para la gestión empresarial. Mi especialidad es transformar lógica de negocio compleja en soluciones técnicas mantenibles.',
            'content' => 'Nidya es un ERP/SaaS diseñado para la escalabilidad. Implementa aislamiento de datos riguroso y una arquitectura de backend robusta. Responsable del mantenimiento integral de módulos críticos en producción, asegurando la estabilidad y disponibilidad del sistema.',
            'stack' => ['Laravel', 'Angular', 'PostgreSQL', 'Docker'],
            'image_path' => '/img/nidya.png',
            'is_featured' => true,
            'ari_context' => 'Oscar lideró el desarrollo del Módulo de Inversiones en Nidya SaaS, diseñando la arquitectura de base de datos y la lógica financiera en el Backend con Laravel y PostgreSQL para el cálculo y seguimiento de rendimientos de inversión.',
        ]);

        Project::create([
            'title' => 'Zanate Nunchi | Arquitecto de IA',
            'slug' => 'zanate-nunchi',
            'summary' => 'Mod de Minecraft (Fabric 1.21.6) que integra compañeros virtuales impulsados por LLMs locales (Ollama).',
            'content' => 'Implementación de una arquitectura de Pizarra (Blackboard) y un sistema basado en Observers para el análisis de contexto espacial en tiempo real. Incluye un motor de mapeos dialectales (Anti-drift) que soporta 13 idiomas y 17 variantes regionales, garantizando modismos específicos.',
            'stack' => ['Java', 'Fabric', 'Ollama', 'LLM Local', 'Asynchronous processing'],
            'github_url' => 'https://github.com/OscarMinjarez',
            'image_path' => '/img/zanate.png',
            'is_featured' => true,
            'ari_context' => 'Oscar desarrolló Zanate Nunchi usando Java y Fabric (sin PostgreSQL ni bases de datos relacionales). El proyecto conecta Java con LLMs locales vía Ollama de forma asíncrona. Oscar implementó guardrails de tokens dinámicos y control de spam para lograr cero impacto en los TPS del servidor de Minecraft.',
        ]);

        // Work Experience
        WorkExperience::create([
            'company' => 'Erus',
            'position' => 'Desarrollador Full Stack',
            'start_date' => '2025-09-01',
            'end_date' => null,
            'description' => 'Responsable del mantenimiento integral (Full Stack) de módulos críticos en un sistema ERP. Lideró el desarrollo del Módulo de Inversiones, diseñando la arquitectura de base de datos y la lógica financiera. Integró interfaces dinámicas en Angular conectadas a servicios RESTful robustos.',
            'ari_context' => 'En Erus, Oscar trabaja actualmente como Full Stack. Diagnostica y corrige bugs en tiempo real en módulos de ventas, compras y almacén con Laravel y Angular, manteniendo la integridad de los datos del ERP.',
        ]);

        WorkExperience::create([
            'company' => 'Emcor',
            'position' => 'Full Stack Developer',
            'start_date' => '2024-10-01',
            'end_date' => '2025-01-01',
            'description' => 'Desarrollo de plataforma de comunicación interna mediante arquitectura desacoplada.',
            'ari_context' => 'En Emcor, Oscar desarrolló una plataforma de comunicación interna usando Spring Boot para la lógica de servicios y Vue.js en el cliente.',
        ]);

        WorkExperience::create([
            'company' => 'BeAnalítica',
            'position' => 'Full Stack Developer',
            'start_date' => '2023-06-01',
            'end_date' => '2024-04-01',
            'description' => 'Modelado de bases de datos relacionales en PostgreSQL y diseño de APIs robustas para servicios de asistencia al hogar.',
            'ari_context' => 'En BeAnalítica, Oscar modeló bases de datos relacionales en PostgreSQL y diseñó APIs robustas con NestJS para servicios de asistencia al hogar.',
        ]);

        // Technologies
        $techs = [
            ['name' => 'Laravel', 'category' => 'Backend', 'slug' => 'laravel', 'ari_context' => 'Especialista en desarrollo de ERPs y SaaS.'],
            ['name' => 'Java', 'category' => 'Backend', 'slug' => 'java', 'ari_context' => 'Experiencia en Spring Boot y desarrollo de Mods para Minecraft.'],
            ['name' => 'Spring Boot', 'category' => 'Backend', 'slug' => 'spring-boot', 'ari_context' => 'Arquitecturas desacopladas y servicios robustos.'],
            ['name' => 'NestJS', 'category' => 'Backend', 'slug' => 'nestjs', 'ari_context' => 'Diseño de APIs robustas y modelado PostgreSQL.'],
            ['name' => 'Node.js', 'category' => 'Backend', 'slug' => 'nodejs', 'ari_context' => 'Ecosistema JavaScript para backend.'],
            ['name' => 'Angular', 'category' => 'Frontend', 'slug' => 'angular', 'ari_context' => 'Interfaces fluidas para procesos complejos.'],
            ['name' => 'Vue.js', 'category' => 'Frontend', 'slug' => 'vuejs', 'ari_context' => 'Desarrollo reactivo y moderno.'],
            ['name' => 'JavaScript', 'category' => 'Languages', 'slug' => 'javascript', 'ari_context' => 'Dominio de lógica en frontend y backend.'],
            ['name' => 'PostgreSQL', 'category' => 'Database', 'slug' => 'postgresql', 'ari_context' => 'Modelado relacional y optimización de datos.'],
        ];

        foreach ($techs as $tech) {
            Technology::create($tech);
        }
    }
}
