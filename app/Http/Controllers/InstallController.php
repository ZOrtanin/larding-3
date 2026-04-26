<?php

namespace App\Http\Controllers;

use App\Services\DatabaseConnectionValidator;
use App\Services\EnvironmentFileEditor;
use App\Services\InstallationRunner;
use App\Services\InstallationState;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;

class InstallController extends Controller
{
    public function __construct(
        private readonly InstallationState $installationState,
        private readonly DatabaseConnectionValidator $databaseConnectionValidator,
        private readonly EnvironmentFileEditor $environmentFileEditor,
        private readonly InstallationRunner $installationRunner,
    ) {
    }

    public function index(): View
    {
        $requirements = [
            [
                'label' => 'PHP 8.2+',
                'passed' => version_compare(PHP_VERSION, '8.2.0', '>='),
                'value' => PHP_VERSION,
            ],
            [
                'label' => 'PDO',
                'passed' => extension_loaded('pdo'),
                'value' => extension_loaded('pdo') ? 'enabled' : 'missing',
            ],
            [
                'label' => 'PDO MySQL',
                'passed' => extension_loaded('pdo_mysql'),
                'value' => extension_loaded('pdo_mysql') ? 'enabled' : 'missing',
            ],
            [
                'label' => 'Mbstring',
                'passed' => extension_loaded('mbstring'),
                'value' => extension_loaded('mbstring') ? 'enabled' : 'missing',
            ],
            [
                'label' => 'OpenSSL',
                'passed' => extension_loaded('openssl'),
                'value' => extension_loaded('openssl') ? 'enabled' : 'missing',
            ],
            [
                'label' => 'Fileinfo',
                'passed' => extension_loaded('fileinfo'),
                'value' => extension_loaded('fileinfo') ? 'enabled' : 'missing',
            ],
        ];

        $permissions = [
            [
                'label' => 'storage',
                'path' => storage_path(),
                'passed' => is_writable(storage_path()),
            ],
            [
                'label' => 'bootstrap/cache',
                'path' => base_path('bootstrap/cache'),
                'passed' => is_writable(base_path('bootstrap/cache')),
            ],
        ];

        $allChecksPassed = collect($requirements)
            ->merge($permissions)
            ->every(fn (array $check): bool => $check['passed']);

        return view('install.index', [
            'allChecksPassed' => $allChecksPassed,
            'installationCompleted' => $this->installationState->isInstalled(),
            'permissions' => $permissions,
            'requirements' => $requirements,
        ]);
    }

    public function database(Request $request): View
    {
        return view('install.database', [
            'defaults' => [
                'app_url' => old('app_url', config('app.url')),
                'connection' => old('connection', env('DB_CONNECTION', 'mysql')),
                'host' => old('host', env('DB_HOST', '127.0.0.1')),
                'port' => old('port', env('DB_PORT', '3306')),
                'database' => old('database', env('DB_DATABASE', '')),
                'username' => old('username', env('DB_USERNAME', '')),
                'password' => old('password', env('DB_PASSWORD', '')),
            ],
        ]);
    }

    public function storeDatabase(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_url' => ['required', 'url', 'max:255'],
            'connection' => ['required', 'in:mysql,mariadb,pgsql,sqlsrv'],
            'host' => ['required', 'string', 'max:255'],
            'port' => ['required', 'integer', 'min:1', 'max:65535'],
            'database' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $this->databaseConnectionValidator->validate($validated);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\InvalidArgumentException $exception) {
            throw ValidationException::withMessages([
                'database' => $exception->getMessage(),
            ]);
        }

        $this->environmentFileEditor->update([
            'DB_CONNECTION' => $validated['connection'],
            'DB_HOST' => $validated['host'],
            'DB_PORT' => (string) $validated['port'],
            'DB_DATABASE' => $validated['database'],
            'DB_USERNAME' => $validated['username'],
            'DB_PASSWORD' => (string) ($validated['password'] ?? ''),
        ]);

        Artisan::call('config:clear');

        $request->session()->put('install.database_configured', true);
        $request->session()->put('install.database', [
            'app_url' => $validated['app_url'],
            'connection' => $validated['connection'],
            'host' => $validated['host'],
            'port' => (string) $validated['port'],
            'database' => $validated['database'],
            'username' => $validated['username'],
        ]);

        return redirect()
            ->route('install.initialize')
            ->with('status', 'database-configured');
    }

    public function initialize(Request $request): View|RedirectResponse
    {
        if (! $request->session()->get('install.database_configured')) {
            return redirect()->route('install.database');
        }

        return view('install.initialize', [
            'databaseConfigured' => (bool) $request->session()->get('install.database_configured'),
            'initialized' => (bool) $request->session()->get('install.initialized', false),
        ]);
    }

    public function runInitialization(Request $request): RedirectResponse
    {
        if (! $request->session()->get('install.database_configured')) {
            //echo $request;
            return redirect()->route('install.database');
            //return $request;
        }

        $this->installationRunner->initializeDatabase();

        $request->session()->put('install.initialized', true);



        return redirect()
            ->route('install.admin')
            ->with('status', 'installation-initialized');
    }

    public function admin(Request $request): View|RedirectResponse
    {
        if (! $request->session()->get('install.database_configured')) {
            return redirect()->route('install.database');
        }

        if (! $request->session()->get('install.initialized')) {
            return redirect()->route('install.initialize');
        }

        return view('install.admin', [
            'defaults' => [
                'name' => old('name', 'Administrator'),
                'email' => old('email', 'admin@example.com'),
            ],
        ]);
    }

    public function storeAdmin(Request $request): RedirectResponse
    {
        if (! $request->session()->get('install.database_configured')) {
            return redirect()->route('install.database');
        }

        if (! $request->session()->get('install.initialized')) {
            return redirect()->route('install.initialize');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $role = Role::query()->where('slug', 'super_admin')->first();

        if (! $role) {
            throw ValidationException::withMessages([
                'email' => 'Роль супер-администратора не найдена. Сначала выполните инициализацию базы.',
            ]);
        }

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
            'role_id' => $role->id,
        ]);

        Setting::setValue(InstallationState::INSTALLED_KEY, '1');

        $this->environmentFileEditor->update([
            'APP_URL' => (string) $request->session()->get('install.database.app_url', config('app.url')),
            'APP_INSTALLED' => 'true',
        ]);

        $request->session()->forget([
            'install.database_configured',
            'install.database',
            'install.initialized',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'installation-finished');
    }
}
