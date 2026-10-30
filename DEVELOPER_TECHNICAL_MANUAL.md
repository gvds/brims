# BRIMS Developer Technical Manual

## 1. Purpose

This manual is a developer-focused reference for the BRIMS codebase. It documents architecture, core modules, runtime behavior, local environment setup, testing workflow, and safe extension patterns.

This is intentionally code-oriented and complements the operational guides in the `docs/` directory.

## 2. Technology Stack (Current in Repository)

- PHP: 8.5 (`composer.json` requires `^8.4`)
- Laravel: 13.x (13.39 installed)
- Filament: 5.x (5.11 installed)
- Livewire: 4.x (4.4 installed)
- Tailwind CSS: 4.x (4.3 installed)
- Vite: 6.x
- Pest: 5.x (with `pest-plugin-browser`, `pest-plugin-laravel`, `pest-plugin-livewire`)
- PHPUnit: 13.x
- Node.js: 24 (local); 22+ is enough for production builds
- Database (local): MariaDB 12; tests use in-memory SQLite
- Local runtime environment: Lerd (config in `.lerd.yaml`; site served at `https://brims.test`)

Notable Filament-ecosystem packages: `bezhansalleh/filament-shield` (roles/permissions UI), `marcelweidum/filament-passkeys`, `stechstudio/filament-impersonate`, `croustibat/filament-jobs-monitor`, `achyutn/filament-log-viewer`, `filament/spatie-laravel-google-fonts-plugin`.

Other runtime packages: `spatie/laravel-permission`, `codedge/laravel-fpdf`, `league/flysystem-aws-s3-v3`, `bradietilley/country-enums`.

Dev tooling: Laravel Boost (MCP server + AI guidelines), Pint, Rector (`rector.php`), Pail, Pao.

Primary package references are in `composer.json` and `package.json`; check installed versions with `composer show --direct`.

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

Three Filament panels are registered in `bootstrap/providers.php` (after `AppServiceProvider`):

- `App\Providers\Filament\AdminPanelProvider`
- `App\Providers\Filament\AppPanelProvider`
- `App\Providers\Filament\ProjectPanelProvider`

Panel responsibilities:

- App panel (`/`, default panel): login (custom `Login` page, MFA, passkeys, password reset), profile, calendar, team and project lists. A render hook on `PAGE_START` **removes `currentProject` from the session** whenever an App panel page is rendered, so returning to the main panel always leaves project context.
- Admin panel (`/admin`): system-level administration (users, teams, institutions, unit definitions, physical units, study designs, label specifications). Hosts the Jobs Monitor and Log Viewer plugins. Its `login()` aborts with 404; authentication happens in the App panel.
- Project panel (`/project`): tenant (`Project`)-scoped operational workflows. Also 404s on its own login. Uses Filament Shield for the roles/permissions UI (navigation group "Authorisation").

All panels run in SPA mode (with `spaUrlExceptions` so cross-panel links do full page loads), use `resourceCreatePageRedirect('index')` / `resourceEditPageRedirect('index')`, and use the same web/auth middleware stack. The App and Project providers register a `panels::head.end` render hook that includes `resources/js/app.js` via `@vite`.

## 4. Bootstrap and Request Lifecycle

### 4.1 Application Bootstrap

`bootstrap/app.php` configures:

- Web routes from `routes/web.php`
- Console routes from `routes/console.php`
- Middleware priority modification: `SetUserTeam` is prepended relative to `SubstituteBindings`

### 4.2 Custom Middleware

`app/Http/Middleware/SetUserTeam.php` is central to permission context:

- Before the request: if a user is authenticated and `currentProject` exists in session, it sets team permission context via `setPermissionsTeamId(session('currentProject')->id)`
- After the response is built: it clears the cached `roles` and `permissions` relations on the authenticated user to avoid stale permission state leaking between project contexts

The middleware is not part of the panels' middleware stacks. It is applied explicitly to the `schedule` and `labels.print` routes in `routes/web.php`, and prepended to the priority list ahead of `SubstituteBindings`. In the Project panel, team context is synchronised by Shield's `SyncShieldTenant` tenant middleware (see 8.4).

This is a critical piece of project/tenant permission correctness.

## 5. Routing Overview

`routes/web.php` currently exposes:

- Signed account setup route: `/newaccount/{user}` (`newaccount`) to Livewire component `App\Livewire\SetNewAccountPassword`
- Authenticated PDF/report routes (`auth` middleware):
  - `/schedule/{week}` (`schedule`) and `/labels/print` (`labels.print`): additionally wrapped in `SetUserTeam` and `can:Manage:Subject`
  - `/storage-allocations/{storageAllocation}` (`storage-allocation-report`): `auth` only (no `SetUserTeam`, no ability check at route level)

All other routes (about 75 of the 83 application routes) are generated by the Filament panels.

Most business CRUD workflows are implemented through Filament resources rather than traditional controller routes.

## 6. Filament Module Map

### 6.1 Panel Domains

- `app/Filament/Admin`: resources for users, teams, institutions, unit definitions, physical units (with virtual unit relation managers), study designs and label specifications; dashboard page; widgets `ActiveUsersChart`, `ProjectsChart`, `StatsOverview`
- `app/Filament/App`: pages `Dashboard`, `Calendar`, `EditProfile`, `Login`; resources `Projects` and `Teams`; widgets `EventsDue`, `EventsOverdue`
- `app/Filament/Project`: pages `Dashboard`, `LabelQueue`, `LogPrimarySpecimens`, `LogDerivativeSpecimens`; resources `Subjects`, `Studies`, `Specimens`, `Manifests`, `StorageAllocations`, `Publications`, `Roles` (Shield) and `Projects` (project configuration: members, events, assays, specimen types, sites, labware, etc., as relation managers); widgets `ProjectEventsDue`, `ProjectEventsOverdue`, `ProjectProgress`
- `app/Filament/Imports` and `app/Filament/Exports`: Filament importer/exporter classes (subjects, subject events, specimens, study specimens, manifest items). These run on the queue, so a queue worker is required for them to work. Export files are written to the `exports` disk (`storage/exports`)

Resources follow the Filament 5 layout: `XResource.php` plus `Pages/`, `Schemas/`, `Tables/` and `RelationManagers/` subfolders.

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

- Identity and org: `User`, `UserSubstitute`, `Team`, `Institution`, `Programme`, `ProgrammeProject`, `Project`, `ProjectMember`, `Role`, `Permission`
- Study model: `StudyDesign`, `Study`, `Arm`, `Event`, `Subject`, `SubjectEvent`, `StudySpecimen`
- Specimen chain: `Specimentype`, `Specimen`, `SpecimenLog`, `StorageAllocation`, `StorageLog`, `StorageConsolidation`, `Relocation`, `Labware`, `Manifest`, `ManifestItem`
- Storage infrastructure: `UnitDefinition`, `PhysicalUnit`, `VirtualUnit`, `Section`, `Location`
- Supporting entities: `Site`, `Protocol`, `AssayDefinition`, `Assay`, `Publication`, `LabelSpecification`

Domain states are backed enums in `app/Enums`: `SpecimenStatus`, `ManifestStatus`, `SubjectStatus`, `EventStatus`, `LabelStatus`, `PublicationStatus`, `StorageType`, `StorageDestinations`, `SystemRoles` and `TeamRoles`.

### 7.2 Scope-Based Context Isolation

Global scopes in `app/Models/Scopes`, attached with `#[ScopedBy]`, enforce contextual filtering. Every scope returns no rows when there is no authenticated user.

| Scope | Model | Behaviour |
|---|---|---|
| `ProjectScope` | `Project` | Limits to `currentProject` when set; SuperAdmin/SysAdmin see all, others see projects of their team or where they are a member |
| `TeamScope` | `Team` | SuperAdmin/SysAdmin see all, others only their own team |
| `StudyScope` | `Study` | Limited to `currentProject` (SuperAdmin bypasses) |
| `SubjectScope` | `Subject` | Limited to `currentProject`; non-admin members see subjects at their site that they own or whose owner they substitute for (project role `Admin` sees all) |
| `SubjectEventScope` | `SubjectEvent` | Limited to the project and, for non-SuperAdmins, the member's site |
| `SpecimenScope` | `Specimen` | Limited to the project (via specimen type) and, for non-SuperAdmins, the member's site |
| `AssayDefinitionScope` | `AssayDefinition` | Limited to the user's team (SuperAdmin bypasses) |
| `PhysicalUnitScope` | `PhysicalUnit` | Limited to the user's team's institution (SuperAdmin/SysAdmin bypass) |

Because most scopes read `session('currentProject')`, code running outside a web session (queued jobs, console commands, tests) must account for the missing session.

This complements session-driven permission context from middleware and panel tenancy.

### 7.3 Relationship-Heavy Eloquent Style

The codebase uses rich Eloquent relationships and explicit relation methods. Example patterns in use:

- `belongsToMany` with pivot metadata (`project_member`, `manifest_items`)
- `hasManyThrough` and `hasOneThrough` for cross-entity traversal
- enum casting for domain states (for example specimen status)

### 7.4 Specimen Lifecycle Encapsulation

`app/Models/Specimen.php` encapsulates state transitions in model methods:

- `logStored`, `logUsed`, `logOut`, `logReturn`, `logIntoManifest`, `logOutOfManifest`, `logTransferred`, `logReceived`
- each method writes an audit entry (`SpecimenLog`, previous status, new status, `changed_by`) via `createAuditLog` and then updates the status via `setStatus`

This keeps domain status transitions close to the entity and avoids scattering status logic.

`app/actions/LogSpecimenStatus.php` (note the lowercase directory, namespace `App\actions`) is an invokable action used by the log pages for bulk barcode input. It validates that every barcode exists in the current project, is at the user's site and is in an eligible status for the target (`Used`, `InStorage` or `LoggedOut`) before applying the transition.

## 8. Authentication, Authorization, and Tenancy

### 8.1 Authentication

- Default `web` guard with session driver (`config/auth.php`); production sessions are stored in the database and expire on browser close
- Filament app panel custom login page (`App\Filament\App\Pages\Login`)
- App-authenticator multi-factor authentication (recoverable) and passkeys (`PasskeysPlugin`) enabled in the app panel provider; secrets are stored on `User` via the `getAppAuthenticationSecret` family of methods
- `App\Listeners\UpdateLastLoginAt` (auto-discovered) listens for `Login`, setting `last_login` and re-activating the user
- `LogoutResponse` (bound in `AppServiceProvider`) customises the post-logout redirect
- Account creation sends `UserAccountCreated` mail with a signed link to `/newaccount/{user}` where the user sets a password
- `stechstudio/filament-impersonate` provides user impersonation

### 8.2 Authorization

- Policy classes exist for major domain models (`app/Policies`)
- `AppServiceProvider` defines a `Gate::before` override granting full authorization to `SuperAdmin`
- Spatie permission is wired to custom `Permission` and `Role` models in `AppServiceProvider` (teams mode, keyed on project id)
- `evaluate_permission()` in `app/Helpers/helpers.php` (autoloaded via `composer.json`) is the shared check used by policies/resources: it passes for `SysAdmin`, for team admins in the App panel, for team admins of the current project's team, or when the user holds the Spatie permission
- Permission names follow the Shield `Action:Model` convention (for example `Manage:Subject`)
- `Permissions.txt` in the repo root lists the permission set
- `AppServiceProvider` also configures every Filament `TextInput` to trim whitespace
- System roles (`SystemRoles` enum: `SuperAdmin`, `User`, `SysAdmin`, `CryoAdmin`) are stored on `users.system_role`; per-project roles are Spatie roles assigned through `ProjectMember`

### 8.3 Panel Access Rules

`User::canAccessPanel` controls panel entry:

- project panel requires `currentProject` in session
- admin panel requires system role `SysAdmin` or `SuperAdmin`
- the app panel is open to any authenticated user

### 8.4 Tenant Model in Project Panel

`ProjectPanelProvider` sets the tenant model to `Project::class` (the tenant menu is hidden because project selection happens in the App panel) and applies Shield's `SyncShieldTenant` as persistent tenant middleware, so Livewire requests keep the correct permission team.

## 9. HTTP Controllers and PDF Generation

Non-Filament HTTP endpoints focus on generated documents:

- `ScheduleController`: weekly follow-up schedule PDF
- `LabelController`: barcode label PDF generation (with optional explicit id list)
- `StorageAllocationReportController`: storage allocation report PDF

PDF stack:

- `codedge/laravel-fpdf` package
- local helper classes under `app/Library` (`PDF_Label`, `PDF_Code128`)

`LabelController` prints queued labels (`LabelStatus::Queued`) for the current project and the user plus anyone they substitute for. It validates the requested `labelFormat` against `LabelSpecification` and terminates the script with `exit` on an invalid format, which makes it awkward to test (see 12.4).

### 9.1 REDCap Integration

`app/Services/REDCap.php` is a static-method service (cURL against the REDCap API plus a separate `REDCAP_DB_*` database connection configured in `.env`) used to set up a project's REDCap link (arms, events, user tokens) and to create REDCap records for enrolled subjects. See `docs/10-redcap-integration.md` for the operational side.

## 10. Frontend and Asset Pipeline

- Vite config in `vite.config.js`
- Tailwind v4 integrated through `@tailwindcss/vite`
- Primary frontend entry currently configured: `resources/js/app.js` (which imports `resources/css/app.css`)
- The App and Project panel providers register render hooks to include Vite assets
- Fonts are provided by the Spatie Google Fonts Filament plugin; custom Blade views live under `resources/views/filament` (for example the project `topbar.blade.php`)

## 11. Console Commands and Scheduling

### 11.1 Custom Commands

- `app:delete-old-exports`: removes export folders older than 24 hours from the `exports` disk
- `app:deactivate-inactive-users`: deactivates active users whose `last_login` is older than 3 months

### 11.2 Scheduler

Defined in `routes/console.php`:

- exports cleanup: hourly
- inactive user deactivation: daily

The scheduler needs `php artisan schedule:run` every minute and the queue (imports/exports, mail, notifications) needs a worker; see `DEPLOYMENT.md`. Locally, Lerd runs the `queue` and `vite` workers declared in `.lerd.yaml`.

## 12. Test Strategy and Execution

### 12.1 Test Framework

- Pest is primary test framework
- Test suites in `phpunit.xml`: `Unit`, `Feature`, `Browser`
- `phpunit.xml` forces in-memory SQLite, array cache/session/mail and the `sync` queue, so tests do not need MariaDB or a running queue worker
- `tests/Pest.php` applies `LazilyRefreshDatabase` to Feature and Browser tests and enables Firefox browser test execution (Playwright; `playwright.config.ts`)
- `tests/TestCase.php` creates a `SuperAdmin` user and a `Team` in `setUp()` (`$this->adminuser`, `$this->team`) for every Feature/Browser test; remember `SuperAdmin` bypasses most scopes and gates, so authorisation tests should create lower-privileged users explicitly
- Xdebug debugging is configured in `.vscode/launch.json`

### 12.2 Current Test Layout

- `tests/Feature`: Filament resources/pages, relation managers, scopes, policies, importers and workflow coverage (about 435 tests; `tests/Feature/Filament` holds resource/page tests)
- `tests/Browser`: browser-level behavioral checks (Log Primary Specimens, subject event status)
- `tests/Unit`: focused domain logic tests (exporter, manifest status, project)

### 12.3 Running Tests

Examples (Lerd puts the right `php` on the path, so no wrapper is needed):

- Full compact suite:
  - `php artisan test --compact`
- Single file:
  - `php artisan test --compact tests/Feature/Filament/LoginTest.php`
- Filtered test:
  - `php artisan test --compact --filter=ManifestStatus`
- Skip browser tests (no Playwright/Firefox needed):
  - `php artisan test --compact --exclude-testsuite=Browser`

### 12.4 Known Test Issues (as of 2026-10-29)

A run of the Unit and Feature suites showed these failures, which predate this manual update and should be fixed or the tests updated:

- `LabelQueueTest` "returns a PDF download when printing selected subject events" kills the PHP process ("Premature end of PHP process"), which aborts a full `php artisan test` run. This is likely related to `LabelController` calling `exit`/streaming the FPDF output directly.
- `UserResourceTest`: the create-page required-field test expects a `required` rule on `data.institution`, and the `evaluate_permission(null)` test fails because the helper's `User $authUser` parameter is not nullable.
- `TeamResourceTest` (REDCap leader options): Mockery mock error when rendering the action modal.
- `SubjectEnrollmentTest` (first event marked logged): date assertion compares `Y-m-d` with a datetime string.
- `VirtualUnitsRelationManagerTest` (two tests): `PhysicalUnit` factory usage violates the now-mandatory `institution_id`.

## 13. Local Development Workflow

Local development uses Lerd rather than Laravel Sail. `.lerd.yaml` declares PHP 8.5, Node 24, the `brims.test` domain (HTTPS), the services Mailpit, MariaDB 12 and RustFS (S3-compatible storage), and the `queue` and `vite` workers. The `docker/` directory and `vendor/bin/sail` are leftovers from the Sail era and are not used (`boost.json` has `"sail": false`).

### 13.1 Start Services

- Link/start the site and services through Lerd (the site is served at `https://brims.test`)
- Alternative without Lerd: `composer run dev` starts `artisan serve`, a queue listener, Pail and Vite together

### 13.2 Typical Commands

- Artisan: `php artisan <command>`
- Composer: `composer <command>`
- NPM: `npm <command>`

### 13.3 Frontend Build/Dev

- Dev server: `npm run dev`
- Production build: `npm run build`
- If you see "Unable to locate file in Vite manifest", run `npm run build` or start the dev server

### 13.4 Code Style

- Format PHP changes with Pint:
  - `vendor/bin/pint --dirty --format agent`
- Rector (with Laravel rules) is configured in `rector.php`

### 13.5 AI Assistant Tooling

- `CLAUDE.md`, `AGENTS.md` and `.github/copilot-instructions.md` contain the Laravel Boost guidelines used by AI coding assistants; Boost skills live under `.github/skills`. Treat these as generated by `php artisan boost:install`/`boost:update`. (`.github/copilot-instructions.md` still lists older package versions, such as Laravel 12 and Telescope, and is stale until regenerated.)
- Laravel Boost also exposes an MCP server (database schema/query, docs search, logs, URLs)

## 14. Directory Reference (Developer-Oriented)

- `app/Filament`: panel UI and business workflow screens
- `app/Models`: domain entities and lifecycle logic
- `app/Policies`: model authorization rules
- `app/Http`: middleware, PDF/report controllers and `Responses/LogoutResponse`
- `app/Models/Scopes`: global scopes (see 7.2)
- `app/Enums`: backed enums for statuses and roles
- `app/Services`: service integrations (REDCap)
- `app/Library`: custom PDF-related library classes
- `app/actions`: discrete business action classes (lowercase directory)
- `app/Helpers/helpers.php`: global helper functions (autoloaded)
- `app/Livewire`, `app/Mail`, `app/Listeners`, `app/Rules`: account setup component, account mail, login listener, validation rules
- `database/migrations`: schema evolution history (49 migrations)
- `routes/web.php`: custom web routes outside Filament
- `routes/console.php`: scheduled tasks
- `tests`: unit/feature/browser tests
- `docs`: user and operational documentation set (numbered guides plus `docs/manual`)
- `DEPLOYMENT.md`: production deployment guide (Nginx, PHP-FPM, MariaDB, queue worker, scheduler)

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
- PDF generation paths where formatting logic and business query logic are mixed (`LabelController` also uses `exit`)
- Global scopes that read `session('currentProject')` and `Auth::user()`: they behave differently (or return nothing) in queues, console commands and tests without a session/user
- `SuperAdmin` bypasses the `Gate` and most scopes, so tests running as the default `adminuser` can mask authorisation bugs
- The App panel clears `currentProject` on every page render; flows that depend on it must set it again when entering the Project panel
- The `Documentation` navigation item in the App panel links to `/docs`, but no route or `public/docs` entry exists in the repository, so that link depends on the server configuration

## 17. Recommended Next Developer Docs (Optional Future Additions)

Potential follow-up docs that can be added later if needed:

- ERD and relationship diagram for specimen/storage/manifests
- Panel-by-panel resource ownership matrix
- End-to-end sequence diagrams for specimen and shipment workflows
- CI pipeline and release/deployment runbook

---

Maintainer note: This manual reflects repository state as of 2026-10-29.
