# Liberu Real Estate — Laravel API

This repository is the **server-side Laravel backend** for Liberu Real Estate. It hosts the shared core/domain capabilities and HTTP API adapters used by [project-real-estate-laravel-filament](https://github.com/liberusoftware/project-real-estate-laravel-filament), without Filament or Livewire modules, panels, components or frontend assets.

Nuxt, Next.js, Flutter, React Native and Dart applications run as separate frontends. They communicate with this backend over HTTP/JSON to access data and execute domain operations. Laravel remains responsible for persistence, validation, authentication, tenant authorization, policies, jobs and business rules; the client owns rendering, navigation and platform-specific interactions. One API deployment can serve multiple web, mobile and desktop clients.

## Matching clients and presentation technologies

The repository names and deployment boundaries follow the [Liberu project and presentation catalogue](https://github.com/liberusoftware/documentation/blob/main/projects/REPOSITORIES.md) and [presentation implementation guides](https://github.com/liberusoftware/documentation/blob/main/guides/presentation/README.md).

| Client | Matching project | Integration guide | Role |
| --- | --- | --- | --- |
| Nuxt · Vue | [project-real-estate-nuxt-vue](https://github.com/liberusoftware/project-real-estate-nuxt-vue) | [Nuxt · Vue](https://github.com/liberusoftware/documentation/blob/main/guides/presentation/NUXT.md) | Independent Vue web application, including SSR. |
| Next.js · React | [project-real-estate-next-react](https://github.com/liberusoftware/project-real-estate-next-react) | [Next.js · React](https://github.com/liberusoftware/documentation/blob/main/guides/presentation/NEXTJS.md) | Independent React web application with server and client rendering. |
| React Native · Expo | [project-real-estate-mobile-react](https://github.com/liberusoftware/project-real-estate-mobile-react) | [React Native · Expo](https://github.com/liberusoftware/documentation/blob/main/guides/presentation/REACT-NATIVE.md) | Native iOS and Android client. |
| Flutter · Mobile | [project-real-estate-mobile-flutter](https://github.com/liberusoftware/project-real-estate-mobile-flutter) | [Flutter · Mobile](https://github.com/liberusoftware/documentation/blob/main/guides/presentation/FLUTTER.md) | Dart/Flutter client for iOS and Android. |
| Dart · Desktop | [project-real-estate-dart-desktop](https://github.com/liberusoftware/project-real-estate-dart-desktop) | [Dart · Desktop](https://github.com/liberusoftware/documentation/blob/main/guides/presentation/FLUTTER.md) | Dart/Flutter host for Linux, macOS and Windows. |
| Dart · Mobile | [project-real-estate-dart-mobile](https://github.com/liberusoftware/project-real-estate-dart-mobile) | [Dart · Mobile](https://github.com/liberusoftware/documentation/blob/main/guides/presentation/FLUTTER.md) | Separate Dart mobile application with a Flutter host. |

Other Laravel-hosted presentations are [Inertia + Vue](https://github.com/liberusoftware/project-real-estate-laravel-inertia-vue), [Inertia + React](https://github.com/liberusoftware/project-real-estate-laravel-inertia-react), and [Filament](https://github.com/liberusoftware/project-real-estate-laravel-filament). Inertia uses Laravel routes and sessions with its own page protocol; it is an alternative application composition, rather than a standalone REST client. See the [Inertia guide](https://github.com/liberusoftware/documentation/blob/main/guides/presentation/INERTIA.md). [Livewire standards](https://github.com/liberusoftware/documentation/blob/main/standards/LIVEWIRE.md) describe another server-driven UI choice; those UI adapters are not included here.

Client repositories may be presentation skeletons. Their existence does not establish feature parity or a finished integration: select and verify the contracts and modules needed by each client.

## Domain and API composition

- Derived from [project-real-estate-laravel-filament at `09bd8234a5ac`](https://github.com/liberusoftware/project-real-estate-laravel-filament/tree/09bd8234a5ac8b49d920839b4f574134ee93289b).
- Includes **99 distinct local core/domain/API packages** from the corresponding source, with their existing models, services, policies, migrations and API contracts where available. See [source-modules.json](source-modules.json) for package identities, source paths, revision and exclusions.
- Composer uses local `modules/*` and `packages/*` path packages. Their original package types are preserved for module validation; the Liberu relocation plugin is disabled so Composer installs dependency copies under `vendor/` while the source packages remain in place. Existing external core dependencies remain version-aligned with the source manifest.
- Filament and Livewire packages are excluded and explicitly conflict with this host. Embedded panel resources and model panel interfaces are removed. Laravel Pulse is also excluded because its dashboard requires Livewire. Tenancy resolves from scoped `TenantState`; authenticated API middleware must validate team membership and establish/clear that context on every request and job.
- Laravel Jetstream/Fortify and Socialstream may remain as dependencies for shared identity models, team contracts or OAuth integration. Jetstream UI routes are disabled; Fortify views are disabled.
- Module capabilities, default enablement and implementation maturity come from the source. Inspect module manifests, routes and OpenAPI files before enabling a capability. Configure `MODULES_ENABLED` / `MODULES_DISABLED` where the module manager is used; enabling a module is not a substitute for authorization.

This is an API-focused derivation of the current source, not a promise that every documented product feature already has an implemented endpoint. The [product scope](https://github.com/liberusoftware/documentation/blob/main/projects/real-estate/README.md), [API architecture](https://github.com/liberusoftware/documentation/blob/main/architecture/API.md), [module architecture](https://github.com/liberusoftware/documentation/blob/main/architecture/MODULES.md), and [API module index](https://github.com/liberusoftware/documentation/blob/main/modules/api/README.md) explain the intended boundaries.

## Run the backend

Use the PHP and Laravel versions in [composer.json](composer.json), Composer 2, and the database/cache services required by your chosen modules.

```bash
cp .env.example .env
composer install
php artisan key:generate
# Configure DB_* and cache/queue settings before running migrations.
php artisan migrate
php artisan route:list --path=api
php artisan serve
```

A freshly resolved API-only `composer.lock` is included for reproducible installs. The Filament application's lock file is deliberately not reused. Visit `/up` for the host health endpoint. No Node build or frontend development server is needed by this API host.

For browser clients, set `CORS_ALLOWED_ORIGINS` to explicit frontend origins and configure Sanctum stateful domains, cookie domain, HTTPS and CSRF handling for first-party session authentication. For mobile/desktop or other token clients, use scoped Sanctum bearer tokens over HTTPS, with an explicit token issuance/revocation flow. Never embed application secrets in frontend bundles. Check each module's actual routes, permissions, tenant scoping and response schemas; send `Accept: application/json` and handle validation/authentication errors.

Frontend API base URLs point to this backend deployment. Configure them in the matching client according to its integration guide. Authentication and CORS configuration alone do not implement missing product endpoints.

## Validation and contribution

See [validation.json](validation.json) for the completed host checks and their scope. Run `php scripts/check-api-boundary.php`, `composer validate --no-check-publish`, `php artisan route:list --path=api`, `php scripts/smoke-api.php`, and the owning domain/API module tests after resolving dependencies. Review source-module changes against the recorded revision and keep API contract tests aligned with client integrations. See [Laravel standards](https://github.com/liberusoftware/documentation/blob/main/standards/LARAVEL.md) and [testing standards](https://github.com/liberusoftware/documentation/blob/main/standards/TESTING.md).

## License

See the license file inherited from the matching source project. Retained packages preserve their individual license declarations and notices.
