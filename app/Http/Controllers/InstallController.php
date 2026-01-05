<?php

namespace App\Http\Controllers;

use App\Services\InstallerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class InstallController extends Controller
{
    protected $installer;

    public function __construct(InstallerService $installer)
    {
        $this->installer = $installer;
    }

    public function welcome()
    {
        return view('install.welcome');
    }

    public function requirements()
    {
        $requirements = $this->installer->checkRequirements();
        $allMet = !in_array(false, array_column($requirements['extensions'], 'status'));
        $allMet = $allMet && $requirements['php']['status'];

        return view('install.requirements', compact('requirements', 'allMet'));
    }

    public function permissions()
    {
        $permissions = $this->installer->checkPermissions();
        $allWritable = !in_array(false, array_column($permissions, 'is_writable'));

        return view('install.permissions', compact('permissions', 'allWritable'));
    }

    public function environment()
    {
        return view('install.environment');
    }

    public function saveEnvironment(Request $request)
    {
        $data = $request->validate([
            'APP_NAME' => 'required|string',
            'APP_URL' => 'required|url',
            'DB_HOST' => 'required|string',
            'DB_PORT' => 'required|string',
            'DB_DATABASE' => 'required|string',
            'DB_USERNAME' => 'required|string',
            'DB_PASSWORD' => 'nullable|string',
        ]);

        // Force safe drivers during install
        $data['CACHE_STORE'] = 'file';
        $data['SESSION_DRIVER'] = 'file';
        $data['QUEUE_CONNECTION'] = 'sync';

        $this->installer->updateEnv($data);

        return redirect()->route('install.database');
    }

    public function database()
    {
        return view('install.database');
    }

    public function migrate()
    {
        try {
            Artisan::call('migrate:fresh', ['--force' => true]);
            Artisan::call('db:seed', ['--class' => 'RoleAndPermissionSeeder', '--force' => true]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function admin()
    {
        return view('install.admin');
    }

    public function saveAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
            'is_verified' => true,
        ]);

        $user->assignRole('admin');

        return redirect()->route('install.finish');
    }

    public function finish()
    {
        $this->installer->markAsInstalled();
        return view('install.finish');
    }
}
