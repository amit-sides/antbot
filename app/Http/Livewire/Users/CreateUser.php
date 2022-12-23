<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class CreateUser extends Component
{
    public User $users ;
    public $title = 'Users';

    protected $rules = [
        'users.name' => 'required|string|min:6',
        'users.email' => 'required|string|max:500|unique:users',
    ];

    public function mount()
    {
        $this->users = new User();
    }

    public function save()
    {
        $this->validate();

        $this->users->password = time();

        $this->users->save();

        return redirect()->to('/user');
    }

    public function render()
    {
        return view('livewire.users.create-user')->layoutData([
            'title' => $this->title,
        ]);
    }
}
