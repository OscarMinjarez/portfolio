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
            'stack' => ['Laravel', 'VueJS', 'PostgreSQL', 'Docker'],
            'image_path' => '/img/nidya.png',
            'is_featured' => true,
            'ari_context' => 'Oscar lidera el desarrollo de este último con un pequeño equipo de trabajo amantes del desarrollo de código abierto..',
            'readme_content' => $nidyaReadme,
        ]);

        $zanateReadme = <<<'README'
            # 🤖 Zanate Nunchi - Tu Compañero IA en Minecraft (Ollama Local LLM)

            Un mod revolucionario para **Minecraft Fabric 1.21.6** que introduce a un compañero de aventuras impulsado por **Inteligencia Artificial Local (Ollama)**. No es un asistente aburrido ni un menú de ayuda: es un *jugador más* que reacciona a tu mundo, entiende tu idioma, tiene su propia personalidad y narra tu partida en tiempo real.

            ![Minecraft Version](https://img.shields.io/badge/Minecraft-1.21.6-green)
            ![Fabric Loader](https://img.shields.io/badge/Fabric%20Loader-0.18.2-blue)
            ![Ollama](https://img.shields.io/badge/AI-Ollama%20(Local)-orange)
            ![License](https://img.shields.io/badge/License-CC0-lightgrey)

            ---

            ## ✨ Características Destacadas

            ### 🎭 Personalidad Única y Orgánica
            Zanate Nunchi no es un "bot" genérico. **Cada jugador que se conecta recibe un compañero con una personalidad generada proceduralmente**:
            - **Nombre propio** (ej. "Diego", "Sofía", "Max").
            - **Edad y Género**.
            - **Rasgos de personalidad** (ej. *sarcástico*, *tímido*, *alegre*, *valiente*).
            - **Estilo de habla** (ej. *directo*, *poético*, *informal*).
            Esta personalidad altera **todas** sus respuestas. Si tu bot es sarcástico, se burlará de ti cuando recibas daño; si es protector, se pondrá nervioso.

            <img width="1334" height="674" alt="image" src="https://github.com/user-attachments/assets/39c6afd3-8a0f-4946-8134-8261a6cb9a36" />
            <img width="1600" height="899" alt="image" src="https://github.com/user-attachments/assets/ca023102-4cc7-4c3b-8f0d-3652c3272d20" />

            ### 🧠 Arquitectura de Pizarra (Blackboard) y Contexto Espacial
            El bot no solo responde cuando le hablas. Observa constantemente tu partida de forma silenciosa e inteligente:
            - **Combate**: Detecta cuándo caes en combate, qué mob te derrota, o si estás en una racha de victorias (multiplicadores de encuentros).
            - **Supervivencia**: Sabe si tienes hambre (menos de 8 muslos) o si tu salud es crítica (menos de 4 corazones).
            - **Entorno**: Reacciona a transiciones de día/noche, cambios de bioma, lluvia, tormentas y viajes entre dimensiones (Nether/End).
            - **Alerta Temprana**: Calcula la distancia entre tú y los mobs hostiles cercanos, advirtiéndote si te están flanqueando.
            - **Logros**: Celebra (o critica) cuando desbloqueas advancements.

            ### 🌐 Dialectos Regionales Estrictos (Anti-Drift)
            El mod detecta automáticamente el idioma de tu cliente de Minecraft y obliga al Modelo de Lenguaje (LLM) a hablar en tu **dialecto regional exacto**, prohibiendo el cruce de modismos:
            - 🇲🇽 **Español (México)**: El bot usará "wey", "neta", "chido". Tiene prohibido usar modismos argentinos o españoles.
            - 🇪🇸 **Español (España)**: Usará "tío", "mola", "chaval".
            - 🇦🇷 **Español (Argentina)**: Usará "vos", "che", "boludo".
            - 🇬🇧 **English (UK)** vs 🇺🇸 **English (US)** vs 🇦🇺 **English (AU)**: Distingue perfectamente entre "mate", "dude" y "cobber".
            - *Soporta 13 idiomas y 17 variantes regionales en total.*

            ### ⚡ Motor de LLM Local Optimizado para Gaming
            El mod se comunica con un servidor local de **Ollama** (recomendado `llama3.2`), lo que significa **cero latencia de red, cero costos de API y total privacidad a tus datos**.
            - **Guardrails de Tokens:** El mod ajusta dinámicamente cuántos tokens (palabras) puede generar la IA. 
              - *Situación normal:* Frases conversacionales cortas.
              - *Peligro Inmediato:* La IA entra en modo pánico, generando respuestas ultracortas de latencia casi cero.
            - **Control de Spam y Cooldowns:** Un sistema matemático evita que el bot hable sin parar. Si estás minando, hará un comentario ocasional; si estás en combate, solo reaccionará si tu salud baja de un umbral crítico.
            - **Anti-Corte de Oraciones:** Si la IA excede el límite de tokens, el mod recorta la frase limpia hasta el último signo de puntuación válido para evitar frases a medias.

            ---

            ## 🚀 Instalación y Uso

            ### Requisitos Previos
            1. **Minecraft 1.21.6** con **Fabric Loader 0.18.2+** y **Fabric API 0.128.2+**.
            2. **Java 21**.
            3. **Ollama instalado en tu PC** (o en tu red local).

            ### Configurando la IA (Ollama)
            1. Descarga e instala Ollama desde [ollama.com](https://ollama.com).
            2. Abre tu terminal y descarga el modelo rápido recomendado:
               ```bash
               ollama pull llama3.2
               ```
            3. Asegúrate de que Ollama esté corriendo:
               ```bash
               ollama serve
               ```

            ### Iniciando el Mod
            Coloca el archivo `.jar` del mod en tu carpeta `mods/`. Al iniciar un mundo, la configuración se generará en `config/ollama_bot.json`.
            Si juegas en multijugador, ¡cada jugador en el servidor de Fabric tendrá su propia IA privada susurrándole al oído!

            ---

            ## 📑 Comandos del Mod

            Consulta la documentación completa de comandos aquí:

            [📋 Ver comandos y ejemplos de uso](./COMMANDS.md)

            ---

            ## ⚙️ Configuración (`ollama_bot.json`)

            El mod es altamente personalizable desde su archivo de configuración JSON:

            ```json
            {
              "ollama": {
                "url": "http://localhost:11434",
                "model": "llama3.2",
                "timeoutSeconds": 30
              },
              "cooldowns": {
                "highEventSeconds": 8,
                "spontaneousSeconds": 120
              },
              "history": {
                "maxMessages": 20
              },
              "language": "es"
            }
            ```

            - **url / model:** Ajusta esto si corres Ollama en otra PC de tu red o si quieres usar un modelo más pesado (ej. `llama3:8b`).
            - **cooldowns:** Controla qué tan "callado" es tu bot. Aumenta los segundos si te resulta molesto.
            - **maxMessages:** Cuántos mensajes recuerda la IA antes de olvidarlos (memoria a corto plazo).

            ---

            ## 📁 Estructura para Desarrolladores

            El mod está diseñado con un modelo arquitectónico robusto basado en **Observers** y una **Pizarra Compartida (Blackboard)**.

            ```text
            com/adenium/zanatenunchi/
            ├── ai/
            │   ├── OllamaClient.java         # Comunicación HTTP asíncrona con Ollama AI. Parametriza tokens según peligro.
            │   ├── PersonalityGenerator.java # Forja personalidades únicas persistentes en JSON.
            │   └── PromptManager.java        # Ingenieria de Prompts maestra: inyecta personalidad y reglas dialectales estrictas.
            ├── blackboard/
            │   ├── Blackboard.java           # Almacenamiento en memoria de estados, cooldowns y memoria de los jugadores.
            │   └── BotEvent.java             # Clasificación de eventos (LOW, NORMAL, HIGH).
            ├── controller/
            │   └── BotController.java        # Cerebro central. Decide qué eventos leer, aplica guardrails anti-corte y delega a IA.
            ├── lang/
            │   └── ...Provider.java          # Cadenas hardcodeadas estructuradas y deterministicas para velocidades Ultrabajas.
            ├── observers/
            │   ├── ChatObserver.java         # Lee el chat y extrae nombres.
            │   ├── CombatObserver.java       # Manejo de Health y entidades de daño de Minecraft.
            │   ├── LanguageObserver.java     # Lee Options del cliente de MC para setear provider.
            │   ├── PlayerStatusObserver.java # Inventario, TickEvents, Hambre.
            │   └── WorldObserver.java        # BiomeKeys, DimensionKeys, Weather.
            └── util/
                └── LanguageManager.java      # Mapeos dialectales estrictos (anti-drift).
            ```

            ## 🛠️ Compilación desde el Código Fuente
            1. Clona el repositorio.
            2. Compila el `.jar`:
               ```bash
               ./gradlew build
               ```
            3. Ejecuta el cliente en el entorno de desarrollo de Fabric:
               ```bash
               ./gradlew runClient
               ```

            ---

            ## 🤝 Contribuciones
            ¡Los PRs para añadir más dialectos, mejores triggers en los observers o interacciones de IA (como hacer que el bot sugiera crafteos) son totalmente bienvenidos!

            ## 📄 Licencia
            Distribuido bajo la Licencia **CC0**. Libre para la comunidad.

            ---
            *Desarrollado con ❤️ para llevar una experiencia Next-Gen RPG a Minecraft Clásico.*
            README;

        Project::create([
            'title' => 'Zanate Nunchi | Compañero de IA',
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
