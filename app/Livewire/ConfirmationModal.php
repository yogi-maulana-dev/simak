<?php

namespace App\Livewire;

use Livewire\Component;

class ConfirmationModal extends Component
{
    public $show = false;
    public $title = 'Konfirmasi';
    public $message = 'Apakah Anda yakin ingin melanjutkan?';
    public $confirmButtonText = 'Ya';
    public $cancelButtonText = 'Tidak';
    public $action;
    public $actionParams = [];

    protected $listeners = ['showConfirmationModal'];

    public function showConfirmationModal($action, $params = [], $title = null, $message = null)
    {
        $this->action = $action;
        $this->actionParams = $params;
        
        if ($title) {
            $this->title = $title;
        }
        
        if ($message) {
            $this->message = $message;
        }
        
        $this->show = true;
    }

    public function confirm()
    {
        $this->dispatch($this->action, ...$this->actionParams);
        $this->resetModal();
    }

    public function cancel()
    {
        $this->resetModal();
    }

    private function resetModal()
    {
        $this->show = false;
        $this->title = 'Konfirmasi';
        $this->message = 'Apakah Anda yakin ingin melanjutkan?';
        $this->confirmButtonText = 'Ya';
        $this->cancelButtonText = 'Tidak';
        $this->action = null;
        $this->actionParams = [];
    }

    public function render()
    {
        return view('livewire.confirmation-modal');
    }
}