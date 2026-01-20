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

class UserEdit extends Component
{
    public User $user;
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
    
    public function mount(User $user)
    {
        // Cek hanya superadmin yang bisa akses
        $currentUser = Auth::user();
        if (!$currentUser || $currentUser->role->name !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }
        
        $this->user = $user;
        
        // Set nilai form dari user
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->fakultas_id = $user->fakultas_id;
        $this->prodi_id = $user->prodi_id;
        
        // Load data
        $this->roles = Role::whereIn('name', ['admin_univ', 'admin_fakultas', 'admin_prodi', 'asesor_fakultas', 'asesor_prodi'])->get();
        $this->fakultas = Fakultas::orderBy('nama_fakultas')->get();
        
        // Load prodis jika user adalah admin_prodi
        if ($user->role->name === 'admin_prodi' && $user->prodi_id) {
            $this->prodis = Prodi::where('fakultas_id', $user->fakultas_id)
                ->orderBy('nama_prodi')
                ->get();
        }
    }
    
    public function updated($propertyName)
    {
        if ($propertyName === 'role_id') {
            $this->reset(['fakultas_id', 'prodi_id', 'prodis']);
        }
        
        if ($propertyName === 'fakultas_id' && $this->fakultas_id) {
            $this->prodis = Prodi::where('fakultas_id', $this->fakultas_id)
                ->orderBy('nama_prodi')
                ->get();
        }
    }
    
    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
            'role_id' => ['required', 'exists:roles,id'],
        ];
        
        // Password optional untuk update
        if ($this->password) {
            $rules['password'] = ['required', 'string', 'confirmed', Rules\Password::defaults()];
        }
        
        // Validasi conditional berdasarkan role
        if ($this->role_id) {
            $role = Role::find($this->role_id);
            if ($role && $role->name === 'admin_fakultas') {
                $rules['fakultas_id'] = ['required', 'exists:fakultas,id'];
            }
            
            if ($role && $role->name === 'admin_prodi') {
                $rules['prodi_id'] = ['required', 'exists:prodi,id'];
            }
        }
        
        return $rules;
    }
    
    public function messages()
    {
        return [
            'fakultas_id.required' => 'Fakultas harus dipilih untuk Admin Fakultas.',
            'prodi_id.required' => 'Program Studi harus dipilih untuk Admin Prodi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
    
    public function update()
    {
        $this->validate();
        
        try {
            // Dapatkan role yang dipilih
            $role = Role::find($this->role_id);
            
            // Tentukan fakultas_id dan prodi_id berdasarkan role
            $fakultas_id = null;
            $prodi_id = null;
            
            if ($role->name === 'admin_fakultas') {
                $fakultas_id = $this->fakultas_id;
                $prodi_id = null;
            } 
            elseif ($role->name === 'admin_prodi') {
                // Untuk admin_prodi, dapatkan fakultas dari prodi yang dipilih
                $prodi = Prodi::find($this->prodi_id);
                if ($prodi) {
                    $fakultas_id = $prodi->fakultas_id;
                    $prodi_id = $this->prodi_id;
                }
            }
            // Untuk admin_univ, asesor_fakultas, asesor_prodi: fakultas_id = null, prodi_id = null
            
            // Update user
            $this->user->update([
                'name' => $this->name,
                'email' => $this->email,
                'role_id' => $this->role_id,
                'fakultas_id' => $fakultas_id,
                'prodi_id' => $prodi_id,
            ]);
            
            // Update password jika diisi
            if ($this->password) {
                $this->user->update([
                    'password' => Hash::make($this->password),
                ]);
            }
            
            session()->flash('success', 'User berhasil diperbarui!');
            
            // Redirect ke halaman daftar user
            return $this->redirect(route('admin.users.index'), navigate: true);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.admin.user-edit', [
            'roles' => $this->roles,
            'fakultas' => $this->fakultas,
            'prodis' => $this->prodis,
        ])->layout('layouts.app');
    }
}