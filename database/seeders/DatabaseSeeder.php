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
        $nidyaReadme = <<<'README'
            # Nidya — Backend

            > Sistema de punto de venta (POS) e inventario para negocios de abarrotes. Proyecto de código libre.

            🌐 *[Read in English](README.en.md)*

            ## ¿Qué es Nidya?

            Nidya es un sistema integral de gestión para tiendas de abarrotes y negocios similares. Permite administrar:

            - 🏪 **Tiendas y sucursales** — Modelo "Concept Store" (multi-tienda) con base de almacenes compartida por sucursal
            - 📦 **Catálogo de productos** — Con categorías, variantes, atributos e imágenes
            - 📊 **Inventario** — Stock por almacén, ajustes, transferencias entre almacenes, movimientos
            - 💰 **Ventas** — Punto de venta con descuentos, impuestos, múltiples métodos de pago
            - 👥 **Usuarios y roles** — Sistema de permisos granulares por módulo
            - 🏷️ **Clientes** — Directorio de clientes con historial
            - 📋 **Bitácora** — Registro de auditoría de todas las operaciones que mutan la base de datos (creaciones, actualizaciones, eliminaciones, autenticación)

            ## Stack

            | Tecnología | Versión |
            |-----------|------|
            | PHP | ≥ 8.2 |
            | Laravel | 12.x |
            | PostgreSQL | — |
            | Laravel Sanctum | 4.x |
            | Vue 3 + Vite | — |
            | Pest | 4.x |

            ## Documentación

            - 📐 [Arquitectura del backend (ES)](docs/ARCHITECTURE.md) — Patrones, estructura, modelo de datos, API
            - 📐 [Backend Architecture (EN)](docs/ARCHITECTURE.en.md) — Patterns, structure, data model, API

            ## Patrón de Arquitectura

            ### Flujo de una petición

            Request → Controller → Service → Action (si hay mutación)
            ↓
            Resultado → Response JSON


            ### Reglas de diseño

            | Capa | Responsabilidad | Puede mutar DB |
            |------|----------------|:--------------:|
            | **Controller** | Validación HTTP, enrutamiento, respuesta JSON | ❌ |
            | **Service** | Consultas, filtros, orquestación | ❌ |
            | **Action** | Mutaciones (create, update, delete) | ✅ |
            | **Model** | Relaciones, casts, scopes, helpers de dominio | Solo en helpers propios |

            ### Controllers
            Los controllers **solo inyectan Services**, nunca Actions directamente.

            ### Services
            Los Services contienen la **lógica de lectura** (queries con filtros, eager loading) y delegan las mutaciones a Actions.

            ### Actions
            Las Actions son clases invocables (`__invoke`) que encapsulan una única operación de escritura. Pueden usar `DB::transaction()` para operaciones complejas.

            ## Modelo de Datos
            Todos los modelos usan **UUIDs** como primary key (`HasUuids` trait).

            ## Jerarquía de Permisos y Arquitectura de Seguridad
            El sistema opera bajo una jerarquía de tres niveles (RBAC distribuido por tienda):
            1.  **Nivel 1: Superusuario Global (`is_superuser`)**
            2.  **Nivel 2: Administrador de Tienda (Store Admin)**
            3.  **Nivel 3: Empleado con Permisos Parciales (Vendedor/Cajero)**

            El Método Central para validación es `hasPermissionInStore` en el modelo `User.php`.
            README;

        Project::create([
            'title' => 'Nidya SaaS',
            'slug' => 'nidya-saas',
            'summary' => 'Plataforma multi-tenant para la gestión empresarial. Mi especialidad es transformar lógica de negocio compleja en soluciones técnicas mantenibles.',
            'content' => 'Nidya es un ERP/SaaS diseñado para la escalabilidad. Implementa aislamiento de datos riguroso y una arquitectura de backend robusta. Responsable del mantenimiento integral de módulos críticos en producción, asegurando la estabilidad y disponibilidad del sistema.',
            'stack' => ['Laravel', 'Angular', 'PostgreSQL', 'Docker'],
            'image_path' => '/img/nidya.png',
            'is_featured' => true,
            'ari_context' => 'Oscar lideró el desarrollo del Módulo de Inversiones en Nidya SaaS, diseñando la arquitectura de base de datos y la lógica financiera en el Backend con Laravel y PostgreSQL para el cálculo y seguimiento de rendimientos de inversión.',
            'readme_content' => $nidyaReadme,
        ]);

        $zanateReadme = <<<'README'
            # 🤖 Zanate Nunchi - Tu Compañero IA en Minecraft (Ollama Local LLM)

            Un mod revolucionario para **Minecraft Fabric 1.21.6** que introduce a un compañero de aventuras impulsado por **Inteligencia Artificial Local (Ollama)**. No es un asistente aburrido ni un menú de ayuda: es un *jugador más* que reacciona a tu mundo, entiende tu idioma, tiene su propia personalidad y narra tu partida en tiempo real.

            // ... (PEGA AQUÍ EL RESTO DE TU MARKDOWN EXACTAMENTE COMO ME LO MANDASTE) ...

            *Desarrollado con ❤️ para llevar una experiencia Next-Gen RPG a Minecraft Clásico.*
            README;

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
            'readme_content' => $zanateReadme, // <-- Aquí inyectamos todo el bloque
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
            ['name' => 'Laravel', 'category' => 'Backend', 'slug' => 'laravel', 'ari_context' => 'Su arma principal en PHP. Especialista en transformar lógica de negocio compleja en soluciones mantenibles para sistemas empresariales ERP/SaaS.'],
            ['name' => 'Java', 'category' => 'Backend', 'slug' => 'java', 'ari_context' => 'Lo usa para arquitecturas robustas y experimentación con IA, como el sistema de Pizarra (Blackboard) que armó para su mod de Minecraft en Fabric 1.21.6.'],
            ['name' => 'Spring Boot', 'category' => 'Backend', 'slug' => 'spring-boot', 'ari_context' => 'Usado para arquitecturas desacopladas y servicios de comunicación interna (como lo hizo en Emcor).'],
            ['name' => 'NestJS', 'category' => 'Backend', 'slug' => 'nestjs', 'ari_context' => 'Diseño de APIs robustas conectadas a PostgreSQL para servicios escalables (su etapa en BeAnalítica).'],
            ['name' => 'Angular & Vue.js', 'category' => 'Frontend', 'slug' => 'frontend-frameworks', 'ari_context' => 'Su visión es Fullstack. Construye la columna vertebral en el backend pero tiene la capacidad de integrar interfaces fluidas y reactivas en Angular o Vue.'],
            ['name' => 'PostgreSQL', 'category' => 'Database', 'slug' => 'postgresql', 'ari_context' => 'Modelado relacional estricto, aislamiento de datos en sistemas multi-tenant y optimización.'],
            ['name' => 'Ollama & LLMs Locales', 'category' => 'AI', 'slug' => 'ai-local', 'ari_context' => 'No solo consume APIs; integra IA en flujos prácticos, optimiza guardrails de tokens dinámicos y mapeos dialectales (Anti-drift) sin latencia de red.'],
            ['name' => 'Educación', 'category' => 'General', 'slug' => 'educacion', 'ari_context' => 'Licenciado en Tecnologías de la Información por el ITSON (Instituto Tecnológico de Sonora), egresado en mayo de 2025. Además maneja Inglés técnico.'],
        ];

        foreach ($techs as $tech) {
            Technology::create($tech);
        }
    }
}
