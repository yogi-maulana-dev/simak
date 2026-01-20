<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Role;
use App\Models\Fakultas;
use App\Models\Prodi;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Session;

class UserCreate extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role_id = '';
    public $fakultas_id = '';
    public $prodi_id = '';
    
    public $prodis = [];
    public $fakultas = [];
    public $roles = [];
    
    public $isSubmitting = false; // Tambah state untuk loading
    
    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->role->name !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }
        
        $this->roles = Role::whereIn('name', ['admin_univ', 'admin_fakultas', 'admin_prodi', 'asesor_fakultas', 'asesor_prodi'])->get();
        $this->fakultas = Fakultas::orderBy('nama_fakultas')->get();
    }
    
    public function updated($property)
    {
        if ($property === 'role_id') {
            $this->fakultas_id = '';
            $this->prodi_id = '';
            $this->prodis = [];
        }
        
        if ($property === 'fakultas_id') {
            $this->prodi_id = '';
            
            if ($this->fakultas_id) {
                $this->prodis = Prodi::where('fakultas_id', $this->fakultas_id)
                    ->orderBy('nama_prodi')
                    ->get();
            } else {
                $this->prodis = [];
            }
        }
    }
    
    protected function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
        ];
        
        if ($this->role_id) {
            $role = Role::find($this->role_id);
            
            if ($role) {
                if ($role->name === 'admin_fakultas') {
                    $rules['fakultas_id'] = ['required', 'exists:fakultas,id'];
                }
                
                if ($role->name === 'admin_prodi') {
                    $rules['fakultas_id'] = ['required', 'exists:fakultas,id'];
                    $rules['prodi_id'] = ['required', 'exists:prodi,id'];
                }
            }
        }
        
        return $rules;
    }
    
    public function messages()
    {
        return [
            'fakultas_id.required' => 'Fakultas harus dipilih.',
            'prodi_id.required' => 'Program Studi harus dipilih.',
            'prodi_id.exists' => 'Program Studi yang dipilih tidak valid.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
    
    public function save()
    {
        $this->isSubmitting = true; // Set loading state
        
        try {
            $this->validate();
            
            $role = Role::find($this->role_id);
            
            $fakultas_id = null;
            $prodi_id = null;
            
            if ($role->name === 'admin_fakultas') {
                $fakultas_id = $this->fakultas_id;
                $prodi_id = null;
            } 
            elseif ($role->name === 'admin_prodi') {
                $fakultas_id = $this->fakultas_id;
                $prodi_id = $this->prodi_id;
            }
            
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role_id' => $this->role_id,
                'fakultas_id' => $fakultas_id,
                'prodi_id' => $prodi_id,
                'email_verified_at' => now(),
            ]);
            
            // Reset form
            $this->reset([
                'name', 'email', 'password', 'password_confirmation',
                'role_id', 'fakultas_id', 'prodi_id'
            ]);
            $this->prodis = [];
            $this->isSubmitting = false;
            
            // 1. SET FLASH MESSAGE untuk index page
            Session::flash('success', 'User berhasil ditambahkan!');
            
            // 2. REDIRECT ke index dengan flash message
            return redirect()->route('admin.users.index');
            
        } catch (\Exception $e) {
            $this->isSubmitting = false;
            
            // Set error message
            $this->addError('save', 'Gagal menambahkan user: ' . $e->getMessage());
            
            // Atau gunakan session flash untuk error
            Session::flash('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        $selectedRole = null;
        if ($this->role_id) {
            $selectedRole = Role::find($this->role_id);
        }
        
        return view('livewire.admin.user-create', [
            'roles' => $this->roles,
            'fakultas' => $this->fakultas,
            'prodis' => $this->prodis,
            'selectedRole' => $selectedRole,
            'isSubmitting' => $this->isSubmitting,
        ])->layout('layouts.app');
    }
}