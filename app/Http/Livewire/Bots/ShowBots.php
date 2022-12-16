<?php

namespace App\Http\Livewire\Bots;

use App\Models\Bot;
use Livewire\Component;
use Livewire\WithPagination;

class ShowBots extends Component
{
    use WithPagination;

    public $search = '';
    public $deleteId = 0;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.bots.show-bots', [
            'records' => Bot::where('symbol', 'like', '%'.$this->search.'%')
                ->mine()
                ->with('exchange', 'grid')
                ->paginate(5)
        ]);
    }

    public function changeBotStatus(Bot $bot)
    {
        \Log::info($bot->started_at . ' PID: ' . $bot->pid);
        if ($bot->started_at && $bot->pid > 0) {
            $bot->started_at = NULL;
            $bot->pid = 0;
        } else {
            $bot->started_at = now();
            $bot->pid = 50000;
        }
        $bot->save();
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    public function destroy()
    {
        if ($this->deleteId > 0) {
            $record = Bot::find($this->deleteId);
            if(auth()->user()->id == $record->user_id){
                $record->delete();
                session()->flash('message', 'Bot successfully deleted.');
            }
            $this->deleteId = 0;
        }
    }
}
