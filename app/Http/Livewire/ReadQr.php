<?php

namespace App\Http\Livewire;

use App\Models\Members;
use App\Models\Profile;
use App\Models\QrCodes;
use App\Models\User;
use Livewire\Component;

class ReadQr extends Component
{
    public $token;
    public $dates;
    public $name;
    public $document;
    public $qrCodes;
    public $isValid = false;

    public function mount(){
        $this->qrCodes = QrCodes::where('code', $this->token)->first();
        if($this->qrCodes){
            $this->isValid = $this->qrCodes->status_qr == 1 ? true : false;
            if ($this->qrCodes->is_user == 1) {
                $profile = Profile::where('document', $this->qrCodes->document)->with('user')->first();
                    $this->name = $profile->user->name;
                    $this->document = $profile->document;
            }else {
                $member = Members::where('document', $this->qrCodes->document)->first();
                $this->name = $member->name;
                $this->document = $member->document;
            }
        }
       
    }

    public function render()
    {
        return view('livewire.read-qr');
    }

    public function save($id)
    {
      QrCodes::findOrFail($id)->update(['status_qr' => 0]);
      $this->isValid = false;
    }

}
