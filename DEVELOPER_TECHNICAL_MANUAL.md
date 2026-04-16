# BRIMS Developer Technical Manual

## 1. Purpose

This manual is a developer-focused reference for the BRIMS codebase. It documents architecture, core modules, runtime behavior, local environment setup, testing workflow, and safe extension patterns.

This is intentionally code-oriented and complements the operational guides in the `docs/` directory.

## 2. Technology Stack (Current in Repository)

- PHP: 8.4
- Laravel: 13.2.0
- Filament: 5.4.2
- Livewire: 4.2.2
- Tailwind CSS: 4.2.2
- Vite: 6.x
- Pest: 4.x
- PHPUnit: 12.x
- Database (default local setup): MariaDB
- Runtime local environment: Laravel Sail with Docker

Primary package references are in `composer.json` and runtime metadata from the application info MCP server.

## 3. High-Level Architecture

BRIMS is a Laravel monolith with Filament-based multi-panel UI and project-scoped domain logic.

Core architectural layers:

- UI layer: Filament panels and resources under `app/Filament`
- Domain/data layer: Eloquent models under `app/Models`
- Access control: policies and role/permission integration (`app/Policies`, Spatie permission)
- HTTP endpoints for PDFs and utility workflows: `app/Http/Controllers`
- Session-sensitive tenancy and authorization context: middleware + model scopes
- Scheduled maintenance tasks: console commands + scheduler

### 3.1 Multi-Panel Structure

Three Filament panels are registered in `bootstrap/providers.php`:

- `App\Providers\Filament\AppPanelProvider`
- `App\Providers\Filament\AdminPanelProvider`
- `App\Providers\Filament\ProjectPanelProvider`

Panel responsibilities:

- App panel (`/`): default entry, login/profile/team/project selection context
- Admin panel (`/admin`): system-level administration
- Project panel (`/project`): tenant/project-scoped operational workflows

## 4. Bootstrap and Request Lifecycle

### 4.1 Application Bootstrap

`bootstrap/app.php` configures:

- Web routes from `routes/web.php`
- Console routes from `routes/console.php`
- Middleware priority modification: `SetUserTeam` is prepended relative to `SubstituteBindings`

### 4.2 Custom Middleware

`app/Http/Middleware/SetUserTeam.php` is central to permission context:

- If a user is authenticated and `currentProject` exists in session, it sets team permission context via `setPermissionsTeamId(session('currentProject')->id)`
- It clears cached `roles` and `permissions` relations on the authenticated user each request cycle to avoid stale permission state

This middleware is a critical piece of project/tenant permission correctness.

## 5. Routing Overview

`routes/web.php` currently exposes:

- Signed account setup route: `/newaccount/{user}` to Livewire component `SetNewAccountPassword`
- Authenticated PDF/report routes:
  - `/schedule/{week}`
  - `/labels/print`
  - `/storage-allocations/{storageAllocation}`

Most business CRUD workflows are implemented through Filament resources rather than traditional controller routes.

## 6. Filament Module Map

### 6.1 Panel Domains

- `app/Filament/Admin`: users, teams, unit definitions, study design, physical units, utilities
- `app/Filament/App`: dashboard, login/profile, top-level team/project navigation
- `app/Filament/Project`: project workflows (subjects, studies, specimens, manifests, storage, roles, publications)

### 6.2 Notable Project Workflows (Filament pages/resources)

Project panel includes pages/resources for:

- Subject enrollment and management
- Study configuration and assay management
- Specimen logging and lifecycle actions
- Label queue and printing workflows
- Storage allocation and storage reports
- Manifest creation/transfer/receive workflows
- Project role/permission and member assignment

Representative classes:

- `app/Filament/Project/Pages/LogPrimarySpecimens.php`
- `app/Filament/Project/Pages/LogDerivativeSpecimens.php`
- `app/Filament/Project/Pages/LabelQueue.php`
- `app/Filament/Project/Resources/Specimens/SpecimenResource.php`
- `app/Filament/Project/Resources/StorageAllocations/StorageAllocationResource.php`
- `app/Filament/Project/Resources/Manifests/ManifestResource.php`

## 7. Domain Model and Data Access Patterns

### 7.1 Core Models

The model set is broad and research-workflow oriented. Key entities include:

- Identity and org: `User`, `Team`, `Project`, `ProjectMember`, `Role`, `Permission`
- Study model: `Study`, `Arm`, `Event`, `Subject`, `SubjectEvent`
- Specimen chain: `Specimentype`, `Specimen`, `SpecimenLog`, `StorageAllocation`, `StorageLog`, `Location`, `Labware`, `Manifest`, `ManifestItem`
- Supporting entities: `Site`, `Protocol`, `AssayDefinition`, `Assay`, `Publication`

### 7.2 Scope-Based Context Isolation

Model scopes are used for contextual filtering, for example:

- `Project` is scoped by `ProjectScope`
- `Specimen` is scoped by `SpecimenScope`
- `Team` is scoped by `TeamScope`

This complements session-driven permission context from middleware and panel tenancy.

### 7.3 Relationship-Heavy Eloquent Style

The codebase uses rich Eloquent relationships and explicit relation methods. Example patterns in use:

- `belongsToMany` with pivot metadata (`project_member`, `manifest_items`)
- `hasManyThrough` and `hasOneThrough` for cross-entity traversal
- enum casting for domain states (for example specimen status)

### 7.4 Specimen Lifecycle Encapsulation

`app/Models/Specimen.php` encapsulates state transitions in model methods:

- `logStored`, `logUsed`, `logOut`, `logReturn`, `logIntoManifest`, `logOutOfManifest`, `logTransferred`, `logReceived`
- each method updates status and writes audit entries via `createAuditLog`

This keeps domain status transitions close to the entity and avoids scattering status logic.

## 8. Authentication, Authorization, and Tenancy

### 8.1 Authentication

- Default `web` guard with session driver (`config/auth.php`)
- Filament app panel custom login page (`App\Filament\App\Pages\Login`)
- multi-factor authentication enabled in app panel provider

### 8.2 Authorization

- Policy classes exist for major domain models (`app/Policies`)
- `AppServiceProvider` defines a `Gate::before` override granting full authorization to `SuperAdmin`
- Spatie permission is wired to custom `Permission` and `Role` models in `AppServiceProvider`

### 8.3 Panel Access Rules

`User::canAccessPanel` controls panel entry:

- project panel requires `currentProject` in session
- admin panel requires system role `SysAdmin` or `SuperAdmin`

### 8.4 Tenant Model in Project Panel

`ProjectPanelProvider` sets tenant model to `Project::class` and applies Shield tenant middleware synchronization.

## 9. HTTP Controllers and PDF Generation

Non-Filament HTTP endpoints focus on generated documents:

- `ScheduleController`: weekly follow-up schedule PDF
- `LabelController`: barcode label PDF generation (with optional explicit id list)
- `StorageAllocationReportController`: storage allocation report PDF

PDF stack:

- `codedge/laravel-fpdf` package
- local helper classes under `app/Library` (`PDF_Label`, `PDF_Code128`)

## 10. Frontend and Asset Pipeline

- Vite config in `vite.config.js`
- Tailwind v4 integrated through `@tailwindcss/vite`
- Primary frontend entry currently configured: `resources/js/app.js`
- Filament panel providers register render hooks to include Vite assets where needed

## 11. Console Commands and Scheduling

### 11.1 Custom Commands

- `app:delete-old-exports`: removes old export folders from exports disk
- `app:deactivate-inactive-users`: deactivates users inactive for 3+ months

### 11.2 Scheduler

Defined in `routes/console.php`:

- exports cleanup: hourly
- inactive user deactivation: daily

## 12. Test Strategy and Execution

### 12.1 Test Framework

- Pest is primary test framework
- Test suites in `phpunit.xml`: `Unit`, `Feature`, `Browser`
- `tests/Pest.php` applies `RefreshDatabase` to Feature and Browser tests and enables Firefox browser test execution

### 12.2 Current Test Layout

- `tests/Feature`: Filament resources/pages/workflow coverage
- `tests/Browser`: browser-level behavioral checks
- `tests/Unit`: focused domain logic tests

### 12.3 Running Tests (Sail)

Examples:

- Full compact suite:
  - `vendor/bin/sail artisan test --compact`
- Single file:
  - `vendor/bin/sail artisan test --compact tests/Feature/Filament/LoginTest.php`
- Filtered test:
  - `vendor/bin/sail artisan test --compact --filter=ManifestStatus`

## 13. Local Development Workflow

### 13.1 Start Services

- `vendor/bin/sail up -d`

### 13.2 Typical Commands

- Artisan: `vendor/bin/sail artisan <command>`
- Composer: `vendor/bin/sail composer <command>`
- NPM: `vendor/bin/sail npm <command>`
- PHP: `vendor/bin/sail php <script>`

### 13.3 Frontend Build/Dev

- Dev server: `vendor/bin/sail npm run dev`
- Production build: `vendor/bin/sail npm run build`

### 13.4 Code Style

- Format PHP changes with Pint:
  - `vendor/bin/sail bin pint --dirty --format agent`

## 14. Directory Reference (Developer-Oriented)

- `app/Filament`: panel UI and business workflow screens
- `app/Models`: domain entities and lifecycle logic
- `app/Policies`: model authorization rules
- `app/Http`: middleware and PDF/report controllers
- `app/Services`: service integrations (for example REDCap)
- `app/Library`: custom PDF-related library classes
- `app/actions`: discrete business action classes
- `database/migrations`: schema evolution history (43 migrations)
- `routes/web.php`: custom web routes outside Filament
- `routes/console.php`: scheduled tasks
- `tests`: unit/feature/browser tests
- `docs`: user and operational documentation set

## 15. Extension Guidelines for Contributors

When adding features, prefer consistency with existing patterns:

1. If a feature is panel CRUD/workflow, implement in the appropriate Filament panel subtree.
2. Keep domain state transitions on models or focused action classes.
3. Add or update a policy for new protected models/actions.
4. Preserve project/team context handling (session `currentProject` + scoped queries).
5. Add Pest tests for behavior and regressions.
6. Run Pint and targeted tests before opening a PR.

## 16. Known Coupling and Risk Areas

Areas to treat carefully during refactors:

- Session key `currentProject` as a cross-cutting context dependency
- Permission team id injection in middleware and relation cache invalidation
- Complex specimen status transitions and related audit logging
- Filament panel navigation/access rules that depend on custom user methods
- PDF generation paths where formatting logic and business query logic are mixed

## 17. Recommended Next Developer Docs (Optional Future Additions)

Potential follow-up docs that can be added later if needed:

- ERD and relationship diagram for specimen/storage/manifests
- Panel-by-panel resource ownership matrix
- End-to-end sequence diagrams for specimen and shipment workflows
- CI pipeline and release/deployment runbook

---

Maintainer note: This manual reflects repository state as of 2026-04-02.
