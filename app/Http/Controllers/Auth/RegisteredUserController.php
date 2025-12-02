<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the user type selection view.
     */
    public function selectUserType(): View
    {
        return view('auth.user-type-selection');
    }

    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $userType = $request->query('type', 'supplier'); // Default to supplier

        // Validate user type
        if (!in_array($userType, ['client', 'supplier'])) {
            return redirect()->route('register.select-type');
        }

        return view('auth.register', compact('userType'));
    }

    /**
     * Show supplier registration form.
     */
    public function showSupplierForm(): View
    {
        $categories = \App\Models\Category::all();
        $user = auth()->user();

        // Si el usuario ya tiene un registro de proveedor, pre-llenar los datos
        $supplier = null;
        if ($user->role === 'supplier') {
            $supplier = \App\Models\Supplier::where('user_id', $user->id)->first();
        }

        return view('auth.supplier-registration', compact('categories', 'supplier'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['required', 'string', 'in:client,supplier'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->user_type, // Map user_type to role field
        ]);

        event(new Registered($user));

        Auth::login($user);

        // If supplier, redirect to additional registration form
        if ($request->user_type === 'supplier') {
            return redirect()->route('supplier.registration-form');
        }

        // If client, go directly to dashboard
        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Complete supplier registration with additional information.
     */
    public function completeSupplierRegistration(Request $request): RedirectResponse
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'location' => ['required', 'string', 'max:255'],
            'price_range' => ['required', 'string'],
            'description' => ['nullable', 'string', 'max:1000'],
            'documents.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10MB max
            'identification_photo' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'], // 5MB max
        ]);

        $user = Auth::user();

        // Handle file uploads
        $documentsPath = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('supplier-documents', 'public');
                $documentsPath[] = [
                    'name' => $document->getClientOriginalName(),
                    'type' => $document->getClientOriginalExtension(),
                    'path' => asset('storage/' . $path),
                ];
            }
        }

        $identificationPath = null;
        if ($request->hasFile('identification_photo')) {
            $identificationPath = $request->file('identification_photo')->store('supplier-identifications', 'public');
        }

        // Verificar si ya existe un registro de proveedor
        $existingSupplier = \App\Models\Supplier::where('user_id', $user->id)->first();

        if ($existingSupplier) {
            // Actualizar registro existente (para proveedores rechazados que vuelven a intentar)
            $updateData = [
                'category_id' => $request->category_id,
                'location' => $request->location,
                'price_range' => $request->price_range,
                'description' => $request->description,
                'status' => 'pending',
                'rejection_reason' => null, // Limpiar el motivo de rechazo anterior
            ];

            // Solo actualizar documentos si se subieron nuevos
            if (!empty($documentsPath)) {
                $updateData['documents'] = $documentsPath;
            }
            if ($identificationPath) {
                $updateData['identification_photo'] = $identificationPath;
            }

            $existingSupplier->update($updateData);
        } else {
            // Crear nuevo registro de proveedor
            \App\Models\Supplier::create([
                'user_id' => $user->id,
                'category_id' => $request->category_id,
                'location' => $request->location,
                'price_range' => $request->price_range,
                'description' => $request->description,
                'documents' => $documentsPath,
                'identification_photo' => $identificationPath,
                'status' => 'pending',
            ]);
        }

        // Update user status to pending approval
        $user->update(['approved' => false]);

        return redirect()->route('auth.pending-approval');
    }

    /**
     * Show pending approval page.
     */
    public function showPendingApproval(): View
    {
        return view('auth.pending-approval');
    }

    /**
     * Show supplier rejected page with rejection reason.
     */
    public function showSupplierRejected(): View
    {
        $user = auth()->user();
        $supplier = \App\Models\Supplier::where('user_id', $user->id)->first();

        // Si no es proveedor o no tiene registro de proveedor, redirigir
        if ($user->role !== 'supplier' || !$supplier) {
            return redirect()->route('dashboard');
        }

        // Si no está rechazado, redirigir según su estado
        if ($supplier->status !== 'rejected') {
            if ($supplier->status === 'pending') {
                return redirect()->route('auth.pending-approval');
            }
            return redirect()->route('dashboard');
        }

        return view('auth.supplier-rejected', compact('supplier'));
    }
}
