<div align="center">

<img src="docs/readme/banner.svg" alt="AIQ · Plataforma y asesor IA — sitio, catálogo de plásticos y zona de clientes con un asesor técnico multimodal" width="100%">

<br/>

**El catálogo de AIQ, su CMS y una zona privada donde cada cliente consulta a un asesor técnico con IA.**

<img alt="Proyecto cliente" src="https://img.shields.io/badge/proyecto-cliente-FB0D1B?style=for-the-badge&labelColor=151414">
<img alt="Estado: en evolución" src="https://img.shields.io/badge/estado-en_evoluci%C3%B3n-0C58A1?style=for-the-badge&labelColor=151414">
<img alt="Producción: no verificada" src="https://img.shields.io/badge/producci%C3%B3n-no_verificada-D8DDE6?style=for-the-badge&labelColor=151414">

<br/><br/>

<img alt="Laravel 12" src="https://img.shields.io/badge/Laravel-12.55-FF2D20?style=flat-square&logo=laravel&logoColor=white">
<img alt="PHP ^8.2" src="https://img.shields.io/badge/PHP-%5E8.2-777BB4?style=flat-square&logo=php&logoColor=white">
<img alt="MySQL" src="https://img.shields.io/badge/MySQL-Eloquent-4479A1?style=flat-square&logo=mysql&logoColor=white">
<img alt="Vue 3.4" src="https://img.shields.io/badge/Vue-3.4-4FC08D?style=flat-square&logo=vuedotjs&logoColor=white">
<img alt="Inertia" src="https://img.shields.io/badge/Inertia-2.0-9553E9?style=flat-square&logo=inertia&logoColor=white">
<img alt="Tailwind CSS 3.4" src="https://img.shields.io/badge/Tailwind-3.4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white">
<img alt="Vite 5" src="https://img.shields.io/badge/Vite-5.3-646CFF?style=flat-square&logo=vite&logoColor=white">
<img alt="Google Gemini" src="https://img.shields.io/badge/Google_Gemini-multimodal-8E75B2?style=flat-square&logo=googlegemini&logoColor=white">
<img alt="n8n" src="https://img.shields.io/badge/n8n-webhooks-EA4B71?style=flat-square&logo=n8n&logoColor=white">
<img alt="PHPUnit 11" src="https://img.shields.io/badge/PHPUnit-11.5-3C9CD7?style=flat-square">

</div>

<img src="docs/readme/divider.svg" alt="" width="100%">

## 📑 Contenido

- [El problema](#-el-problema)
- [La solución](#-la-solución)
- [Funcionalidades](#-funcionalidades)
- [Arquitectura](#️-arquitectura)
- [Flujos de trabajo](#-flujos-de-trabajo)
- [Modelo de datos](#️-modelo-de-datos)
- [Criterios y decisiones](#-criterios-y-decisiones)
- [Stack](#-stack)
- [Estructura del repo](#️-estructura-del-repo)
- [Puesta en marcha](#-puesta-en-marcha)
- [Pruebas y calidad](#-pruebas-y-calidad)
- [Despliegue](#-despliegue)
- [Estado y pendientes](#-estado-y-pendientes)
- [Licencia y autoría](#-licencia-y-autoría)

## 🧩 El problema

AIQ trabaja en el rubro plástico: masterbatches, bobinas y termoformados. Sus clientes consultan por procesos, aplicaciones y requisitos técnicos, y esas respuestas dependían de que un asesor estuviera disponible. Al mismo tiempo, el sitio institucional y el catálogo necesitaban actualizarse sin tocar las vistas, y la documentación técnica interna (fichas, hojas de seguridad, certificados) no debía circular sin control.

## 💡 La solución

Un monolito Laravel que reúne el sitio público, un CMS completo y una **zona privada de clientes** con un **asesor técnico** que conserva cada conversación, acepta fotos y responde a partir de la base de conocimiento de AIQ.

| Rol | Qué hace |
|---|---|
| **Visitante** | Recorre el catálogo, busca productos, lee novedades, pide presupuesto y se suscribe al newsletter |
| **Cliente** | Se registra (queda pendiente de aprobación), entra a la zona privada y conversa con el asesor |
| **Personal interno** | Gestiona contenido, catálogo, medios, metadatos, mensajes y suscriptores |
| **Administrador** | Aprueba clientes y su vigencia, importa contactos, carga PDFs de conocimiento y configura las integraciones de IA |

## ✨ Funcionalidades

<details open>
<summary><b>Sitio público</b></summary>

- Inicio, empresa, contacto, catálogo por categorías y subcategorías, fichas de producto, paletas, bobinas y termoformados.
- Buscador, novedades y `sitemap`.
- Formularios de contacto y presupuesto con envío de correo; suscripción al newsletter.
- Enlaces de contacto por WhatsApp armados con la configuración comercial.
</details>

<details open>
<summary><b>Zona de clientes y asesor</b></summary>

- Registro con **aprobación administrativa**, habilitación y acceso ilimitado o con fecha de inicio y vencimiento.
- Guard de sesión propio (`logincliente`), separado del personal interno.
- Asesor AIQ con conversaciones persistentes, memoria de hasta 60 mensajes por hilo y adjuntos JPG, PNG o WebP de hasta 6 MB.
- Dos caminos de IA: webhook de **n8n** (con recuperación semántica externa) o consulta directa a **Gemini**.
- El cliente puede ocultar un chat de su vista; la administración lo conserva.
- Las fichas técnicas, hojas de seguridad, certificados y documentos internos no se entregan desde el asistente.
</details>

<details open>
<summary><b>Panel administrativo</b></summary>

- CMS de sliders, logos, inicio, empresa, contacto, redes, novedades, bobinas, termoformados, categorías, subcategorías, características, productos, descargas y metadatos.
- Mensajes de formularios, suscriptores y usuarios internos.
- Dashboard con actividad del asistente, tendencias de uso e historial de conversaciones.
- Importación de contactos de clientes desde XLSX, XLS, CSV y TXT.
- Base de conocimiento: carga de PDF (hasta 50 MB) y seguimiento del estado de procesamiento.
- Integraciones de IA editables desde el panel, **cifradas** en base de datos; cambiarlas exige rol `Administrador` y la contraseña actual.
</details>

<img src="docs/readme/divider.svg" alt="" width="100%">

## 🏗️ Arquitectura

<img src="docs/readme/flujo.svg" alt="El cliente consulta o envía una foto desde la zona privada, Laravel comprueba que la cuenta esté habilitada y vigente, aplica las políticas de adjuntos y documentos internos, pregunta a n8n o a Gemini y guarda la respuesta en el hilo" width="100%">

**Monolito Laravel MVC**: Blade es la capa principal de presentación, con páginas Vue montadas por Inertia y Vite para el build. Las llamadas a la IA se hacen durante la propia petición HTTP; no hay colas propias.

```mermaid
flowchart TB
    subgraph usuarios["Usuarios"]
        VIS(["Visitante"])
        CLI(["Cliente"])
        ADM(["Personal y administrador"])
    end
    subgraph app["Laravel 12"]
        WEB["Sitio público<br/>Blade"]
        ZONA["Zona de clientes<br/>guard logincliente"]
        PANEL["Panel admin<br/>Blade + Vue/Inertia"]
        ASE["ClienteDashboardController<br/>asesor"]
        CFG["AiIntegrationSetting<br/>valores cifrados"]
        CB["Callback de conocimiento<br/>token con hash_equals"]
    end
    subgraph externos["Servicios externos"]
        N8N["n8n<br/>webhooks"]
        VEC[("Almacén vectorial<br/>externo")]
        GEM["Google Gemini<br/>API multimodal"]
        MAIL["Correo<br/>mailer de Laravel"]
    end
    DB[("MySQL")]
    DISK[("Disco public<br/>adjuntos y PDFs")]

    VIS --> WEB
    CLI --> ZONA
    ADM --> PANEL
    ZONA --> ASE
    ASE --> CFG
    ASE -->|"con webhook"| N8N
    ASE -->|"sin webhook"| GEM
    N8N --> VEC
    PANEL -->|"PDF de conocimiento"| N8N
    N8N -->|"estado del documento"| CB
    WEB --> MAIL
    WEB --> DB
    ZONA --> DB
    PANEL --> DB
    ASE --> DISK
    PANEL --> DISK
```

La recuperación semántica no vive en Laravel: la aplicación le manda a n8n el tipo de asistente, el filtro de metadatos y el almacén esperado (`supabase_pgvector`). Embeddings, chunks y workflows no están versionados en este repositorio.

## 🔄 Flujos de trabajo

### Una consulta al asesor

```mermaid
sequenceDiagram
    autonumber
    participant C as Cliente
    participant L as Laravel
    participant DB as MySQL
    participant N as n8n
    participant G as Gemini
    C->>L: consulta y foto opcional
    L->>L: acceso habilitado y vigente, imagen válida
    L->>DB: guarda el mensaje del cliente en el hilo
    L->>DB: lee hasta 60 mensajes de historial
    alt pide un documento interno
        L-->>C: respuesta fija, sin entregar el documento
    else hay webhook de n8n
        L->>N: consulta, historial, contexto, instrucciones e imagen
        N-->>L: respuesta en JSON
    else hay clave de Gemini
        L->>G: consulta multimodal directa
        G-->>L: respuesta
    else sin configuración
        L-->>C: aviso de configuración pendiente
    end
    L->>DB: guarda solicitud y respuesta con su estado
    L-->>C: respuesta del asesor
```

### Documento de la base de conocimiento

```mermaid
stateDiagram-v2
    [*] --> uploaded: el administrador sube el PDF
    uploaded --> processing: se notifica al webhook de n8n
    uploaded --> error: el webhook falla
    processing --> ready: callback ready, completed o indexed
    processing --> error: callback error o failed
    ready --> [*]
    error --> [*]
```

El callback valida el token en tiempo constante, valida el payload y quita claves y tokens conocidos antes de guardar la respuesta cruda.

<img src="docs/readme/divider.svg" alt="" width="100%">

## 🗃️ Modelo de datos

```mermaid
erDiagram
    CATEGORIAS ||--o{ SUBCATEGORIAS : agrupa
    PRODUCTOS ||--o{ RELACION_PRODUCTOS : clasifica
    CATEGORIAS ||--o{ RELACION_PRODUCTOS : incluye
    SUBCATEGORIAS ||--o{ RELACION_PRODUCTOS : incluye
    PRODUCTOS ||--o{ PRELACIONES : relaciona
    CARACTERISTICAS ||--o{ PRODUCTOS : describe
    LOGINCLIENTES ||--o{ CLIENTE_IMPORTADOS : importa
    LOGINCLIENTES ||--o{ CLIENTE_AI_CHATS : conversa
    LOGINCLIENTES ||--o{ CLIENTE_AI_REQUESTS : solicita
    CLIENTE_AI_CHATS ||--o{ CLIENTE_AI_CHAT_MESSAGES : contiene
    CLIENTE_AI_REQUESTS ||--o| CLIENTE_AI_CHAT_MESSAGES : responde
    USERS ||--o{ AI_KNOWLEDGE_DOCUMENTS : sube
    USERS ||--o{ AI_INTEGRATION_SETTINGS : actualiza
    PRODUCTOS {
        bigint id
        string titulo
        string slug
        string pdf
        json galeria
    }
    LOGINCLIENTES {
        bigint id
        string email
        string company
        boolean is_enabled
        boolean access_unlimited
        timestamp access_expires_at
    }
    CLIENTE_AI_CHAT_MESSAGES {
        bigint id
        string role
        string status
        string attachment_path
    }
    AI_KNOWLEDGE_DOCUMENTS {
        bigint id
        string assistant_type
        string status
        string n8n_document_id
    }
    AI_INTEGRATION_SETTINGS {
        bigint id
        string setting_key
        boolean is_secret
    }
```

Las claves foráneas de la zona de clientes borran en cascada; las referencias a usuarios internos usan `nullOnDelete`.

## 🧭 Criterios y decisiones

| Decisión | Por qué |
|---|---|
| Guards separados para personal y clientes | Un cliente nunca comparte sesión ni permisos con el panel; su acceso tiene vigencia propia |
| Dos caminos de IA (n8n o Gemini directo) | El RAG se orquesta afuera cuando existe; sin él, el asesor sigue funcionando con Gemini multimodal |
| Persistir chats, mensajes y solicitudes | Contexto por hilo para el cliente y trazabilidad para la administración |
| Integraciones cifradas y editables desde el panel | Se cambian sin tocar el servidor, con respaldo en variables de entorno y contraseña actual para modificarlas |
| Ocultar el chat en vez de borrarlo | El cliente limpia su vista y AIQ conserva el historial; el borrado definitivo es un comando aparte con `--dry-run` |
| Documentos internos fuera del asistente | Fichas técnicas, hojas de seguridad y certificados no se reparten por chat |
| Sin pagos | `mercadopago/dx-php` figura como dependencia, pero no se usa en el código |

## 🧱 Stack

| Capa | Tecnología | Versión |
|---|---|---|
| Backend | PHP · Laravel · Sanctum · Breeze | ^8.2 · 12.55.1 · 4.3.1 · 2.4.1 |
| Importación | Laravel Excel · PhpSpreadsheet | 4.0.2 · 5.9.0 |
| Frontend | Blade · Vue · @inertiajs/vue3 · Inertia Laravel · Tailwind CSS · Axios | 3.4.30 · 1.2.0 · 2.0.22 · 3.4.4 · 1.7.2 |
| Build | Vite | 5.3.2 |
| Datos | MySQL (SQLite en memoria para pruebas) | — |
| IA | Google Gemini (modelo por `GEMINI_MODEL`) · webhooks de n8n | — |
| Calidad | PHPUnit · Laravel Pint | 11.5.55 · 1.29.0 |

## 🗂️ Estructura del repo

```text
app/
├── Console/Commands/      clientes:purge-inactive-data
├── Http/Controllers/      sitio (PageController), admin/, Cliente/ (zona y asesor), Auth/
├── Imports/               importación de clientes desde planillas
├── Mail/                  contacto, presupuesto y newsletter
├── Models/                catálogo, contenido, clientes, chats e integraciones
└── Support/               AiqAssistantInstructions (instrucciones del asesor)
database/migrations/       esquema del catálogo, del CMS y de la zona de clientes
resources/views/           Blade del sitio, del panel y de la zona de clientes
resources/js/              páginas y componentes Vue con Inertia
routes/                    web.php, api.php (Sanctum y callback de n8n), auth.php
tests/                     Feature (auth, importación, callback) y Unit
PRODUCT.md                 usuarios, propósito y principios de diseño
```

## 🚀 Puesta en marcha

Requisitos: PHP 8.2 o superior, Composer 2, Node.js con npm y MySQL.

```bash
composer install
npm ci
cp .env.example .env && php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve          # en otra terminal: npm run dev
```

| Grupo | Variables (sin valores en el repo) |
|---|---|
| Base de datos | `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` |
| Administrador inicial | `AIQ_ADMIN_NAME`, `AIQ_ADMIN_USERNAME`, `AIQ_ADMIN_EMAIL`, `AIQ_ADMIN_PASSWORD` (el seeder solo crea la cuenta si están) |
| Correo | `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` |
| IA | `GEMINI_API_KEY`, `GEMINI_MODEL`; los webhooks de n8n se cargan desde el panel o desde `config/services.php` |

Para producción: `npm run build`.

## 🧪 Pruebas y calidad

```bash
php artisan test --compact
./vendor/bin/pint --test
```

- Pruebas de autenticación, registro, contraseñas, perfil, correo, **callback de conocimiento** (tokens inválidos y que no se guarden secretos) e **importación** XLSX/XLS/CSV; 28 métodos de prueba en `tests/` por conteo estático.
- La última verificación documentada (PHP 8.5.5, SQLite en memoria) dio 30 pruebas y 89 aserciones en verde y un build de producción correcto; no se volvió a correr para este README.
- Pint todavía marca diferencias en archivos heredados. No hay análisis estático, cobertura, E2E ni CI.

## 🚢 Despliegue

No documentado en el repositorio: no hay Dockerfile, Compose ni workflows. La aplicación necesita PHP, MySQL, un servidor web con HTTPS y escritura en `storage` y `bootstrap/cache`.

- Los adjuntos y PDFs van al disco `public`: hay que revisar su exposición en cada servidor.
- `clientes:purge-inactive-data` (con `--dry-run`) elimina cuentas deshabilitadas, chats vencidos y sus adjuntos; no está programado en el scheduler, se corre a mano o con un cron externo.

## 📌 Estado y pendientes

- **Último commit:** 12 de agosto de 2026 (`fix ia`), rama `main`, 3 commits. Hay cambios sin commitear en autenticación, controladores del panel y pruebas.
- Funcional y en evolución. Si está en producción **no está verificado** desde el repositorio.
- Deuda conocida: el middleware `verified` del panel no tiene efecto porque `User` no implementa `MustVerifyEmail`; solo las integraciones exigen el rol `Administrador`; formato con Pint en archivos heredados; la dependencia de Mercado Pago sin uso.

## 📄 Licencia y autoría

Software propietario de AIQ. Todos los derechos reservados: no se distribuye ni se publica sin autorización. Datos de clientes, conversaciones, adjuntos, PDFs internos y claves de integración nunca forman parte del repositorio.

Desarrollado por **Ing. Santiago Abasto**, único autor del historial de git.

<div align="center">
<br/>
<img src="docs/readme/divider.svg" alt="" width="100%">
<sub>AIQ · sitio, catálogo y asesor técnico con IA para la industria plástica</sub>
</div>
