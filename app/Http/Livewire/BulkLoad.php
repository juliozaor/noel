<?php

namespace App\Http\Livewire;

use App\Imports\CollaboratorsImport;
use App\Imports\ProgrammingsImport;
use App\Imports\UsersImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class BulkLoad extends Component
{
    use WithFileUploads;
    public $openBulk = false;
    public $title = '';
    public $file;
    public $view;
    public $label;

    public function close()
    {
        $this->openBulk = false;
    }

    public function uploadFile()
    {


        if (!$this->file) {
            //session()->flash('error', 'No se ha cargado ningún archivo.');
            $this->emit('alert', 'No se ha cargado ningún archivo','warning');
            return;
        }
        $extension = $this->file->getClientOriginalExtension();
        if (!in_array($extension, ['xlsx', 'xls', 'csv', 'txt'])) {
            //  session()->flash('error', 'Solo se permiten archivos PDF, DOC y DOCX.');
            $this->emit('alert', 'Solo se permiten archivos xlsx, xls y csv.','warning');
            return;
        }
        $tempPath = $this->file->getRealPath();
        try {


            if ($this->view == 'programmings') {
                Excel::import(new ProgrammingsImport, $this->file);
                $this->emitTo('tablet-programming', 'render');
            }
            if ($this->view == 'users') {
                Excel::import(new UsersImport, $this->file);
                $this->emitTo('users', 'render');
            }

            if ($this->view == 'collaborators') {
                Excel::import(new CollaboratorsImport, $this->file);
            }

            $this->openBulk = false;
            $this->emit('alert', 'Archivo cargado','success');
            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.


            }
        }
    }
    public function render()
    {
        return view('livewire.bulk-load');
    }
}
