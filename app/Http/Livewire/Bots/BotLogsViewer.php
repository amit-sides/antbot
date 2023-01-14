<?php

namespace App\Http\Livewire\Bots;

use App\Models\Bot;
use Illuminate\Support\Facades\File;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Component;
use SplFileInfo;

class BotLogsViewer extends Component
{
    public Bot $bot;

    public $file=0;
    public $page=1;
    public $total;
    public $perPage = 200;
    public $paginator;
    public $title = 'Logs viewer';

    protected $queryString=['page'];

    public function render()
    {
        if ($this->bot->user_id != auth()->user()->id) {
            return abort(403, 'Unauthorized action.');
        }
        $files = $this->getLogFiles();

        $log = collect(file($files[$this->file]->getPathname(), FILE_IGNORE_NEW_LINES));

        $this->total = intval(floor($log->count() / $this->perPage)) + 1;

        $log = $log->slice(($this->page - 1) * $this->perPage, $this->perPage)->values();

        return view('livewire.logs.logs-viewer')
              ->layoutData([
                  'title' => $this->title,
              ])
              ->withFiles($files)
              ->withLog($log);
    }

    protected function getLogFiles()
    {
        $logs_path = config('antbot.paths.logs_path');
        $directory =  "{$logs_path}/{$this->bot->exchange_id}/";

        return collect(File::allFiles($directory))
            ->sortByDesc(function (SplFileInfo $file) {
                return $file->getMTime();
            })->values();
    }

    public function truncateFile()
    {
        $files = $this->getLogFiles();
        $file_name = $files[$this->file]->getFilename();
        $logs_path = config('antbot.paths.logs_path');
        $file_path =  "{$logs_path}/{$this->bot->exchange_id}/{$file_name}";
        $cmd = "echo \"\" > {$file_path}";

        exec($cmd, $op);

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'File log have been truncated.'
        ]);;

    }

    public function refreshLog($url)
    {
        return redirect($url);
    }

    public function goto($page)
    {
        $this->page = $page;
    }

    public function updatingFile()
    {
        $this->page = 1;
    }
}
