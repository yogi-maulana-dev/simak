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

    // Properti untuk menyimpan nama role yang dipilih (untuk keperluan tampilan)
    public $selectedRoleName = null;

    public function mount(User $user)
    {
        $currentUser = Auth::user();

        if (!$currentUser || $currentUser->role?->name !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }

        $this->user = $user;

        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->fakultas_id = $user->fakultas_id;
        $this->prodi_id = $user->prodi_id;

        // Set nama role awal
        $this->updateSelectedRoleName();

        $this->roles = Role::whereIn('name', [
            'admin_univ',
            'admin_fakultas',
            'admin_prodi',
            'asesor_fakultas',
            'asesor_prodi'
        ])->get();

        $this->fakultas = Fakultas::orderBy('nama_fakultas')->get();

        // Load prodi jika user adalah admin_prodi dan memiliki prodi
        if ($user->role?->name === 'admin_prodi' && $user->prodi_id) {
            $this->prodis = Prodi::where('fakultas_id', $user->fakultas_id)
                ->orderBy('nama_prodi')
                ->get();
        }
    }

    /**
     * Hook yang dipanggil setiap kali ada properti yang berubah
     */
    public function updated($propertyName)
    {
        if ($propertyName === 'role_id') {
            $this->reset(['fakultas_id', 'prodi_id', 'prodis']);
            $this->updateSelectedRoleName();
        }

        if ($propertyName === 'fakultas_id' && $this->fakultas_id) {
            $this->prodi_id = ''; // Reset prodi_id saat fakultas berubah
            $this->prodis = Prodi::where('fakultas_id', $this->fakultas_id)
                ->orderBy('nama_prodi')
                ->get();
        }
    }

    /**
     * Update properti selectedRoleName berdasarkan role_id yang dipilih
     */
    protected function updateSelectedRoleName()
    {
        if ($this->role_id) {
            $role = Role::find($this->role_id);
            $this->selectedRoleName = $role?->name;
        } else {
            $this->selectedRoleName = null;
        }
    }

    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
            'role_id' => ['required', 'exists:roles,id'],
        ];

        if ($this->password) {
            $rules['password'] = ['required', 'string', 'confirmed', Rules\Password::defaults()];
        }

        if ($this->role_id) {
            $role = Role::find($this->role_id);
            if ($role && $role->name === 'admin_fakultas') {
                $rules['fakultas_id'] = ['required', 'exists:fakultas,id'];
            }
            if ($role && $role->name === 'admin_prodi') {
                $rules['fakultas_id'] = ['required', 'exists:fakultas,id'];
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
            $role = Role::find($this->role_id);
            if (!$role) {
                session()->flash('error', 'Role tidak ditemukan.');
                return;
            }

            $fakultas_id = null;
            $prodi_id = null;

            if ($role->name === 'admin_fakultas') {
                $fakultas_id = $this->fakultas_id;
            } elseif ($role->name === 'admin_prodi') {
                $prodi = Prodi::find($this->prodi_id);
                if (!$prodi) {
                    session()->flash('error', 'Prodi tidak ditemukan.');
                    return;
                }
                $fakultas_id = $prodi->fakultas_id;
                $prodi_id = $this->prodi_id;
            }

            $this->user->update([
                'name' => $this->name,
                'email' => $this->email,
                'role_id' => $this->role_id,
                'fakultas_id' => $fakultas_id,
                'prodi_id' => $prodi_id,
            ]);

            if ($this->password) {
                $this->user->update([
                    'password' => Hash::make($this->password),
                ]);
            }

            session()->flash('success', 'User berhasil diperbarui!');
            return $this->redirect(route('admin.users.index'), navigate: true);

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.user-edit')->layout('layouts.app');
    }
}