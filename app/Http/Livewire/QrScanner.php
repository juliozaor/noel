<?php

namespace App\Http\Livewire;

use App\Models\Members;
use App\Models\Profile;
use App\Models\QrCodes;
use Livewire\Component;

class QrScanner extends Component
{
    public $qrcode;
    public $reservation;
    public $isValid;
    public $user;
    public $result;
    public function mount()
    {
        $this->qrcode = '';
    }
    public function updateQrcodeValue($newValue)
    {
        $this->qrcode = $newValue;
    }

    public function getInformationQR()
    {
        if($this->qrcode == ''){
            session()->flash(
                'message',
                'No se escaneo ningun QR'
            );
            $this->emit(
                'ReservationInfoUpdated',
                [
                    'qrcode' => $this->qrcode,
                ]
            );
            return;
        }
        if ($this->qrcode) {
            $this->reservation = QrCodes::with('reservation.programming')->where('code', $this->qrcode)->first();
            if ($this->reservation) {
                $this->isValid = $this->reservation->status_qr == 1 ? true : false;
                if ($this->reservation->is_user == 1) {
                    $profile = Profile::where('document', $this->reservation->document)->with('user')->first();
                    $this->user = [
                        'name' => $profile->user->name,
                        'document' => $profile->document,
                    ];
                } else {
                    $member = Members::where('document', $this->reservation->document)->first();
                    $this->user = [
                        'name' =>  $member->name,
                        'document' => $member->document
                    ];
                }
            }
            $this->emit(
                'ReservationInfoUpdated',
                [
                    'reservation' => $this->reservation,
                    'user' => $this->user,
                    'isValid' => $this->isValid,
                ]
            );
        } else {
            $this->emit(
                'ReservationInfoUpdated',
                [
                    'qrcode' => $this->qrcode,
                ]
            );
            $this->reset([
                'reservation',
                'user',
                'isValid'
            ]);
            session()->flash(
                'message',
                'No se encontro información, consulte nuevamente el QR'
            );
        }
    }
    public function render()
    {
        return view('livewire.qr-scanner');
    }

    public function save($id)
    {
      QrCodes::findOrFail($id)->update(['status_qr' => 0]);
      $this->isValid = false;
      $this->emit(
        'ReservationInfoUpdated',
        [
            'qrcode' => $this->qrcode,
        ]
    );
    $this->getInformationQR();
    }
}
