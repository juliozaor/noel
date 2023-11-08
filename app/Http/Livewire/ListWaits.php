<?php

namespace App\Http\Livewire;

use App\Mail\ReservationVerification;
use App\Models\Programming;
use App\Models\QrCodes;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class ListWaits extends Component
{
    use WithPagination;
    
    protected $users;
    public $search = '';
    public $sort = 'users.name';
    public $direction = 'asc';
    public $count = 0;
    public $cant = 10;
    public $page = 1;
    public $addUser = [];
    public $totalQuota = 0;

    public $quotaAvailable;
    public $programmingId;

    protected $listeners = ['available'];
    public function previousPage()
    {

        $this->searchUsers();
        if ($this->page > 1) {
            $this->page--;
        }
    }

    public function nextPage()
    {
        $this->searchUsers();
        if ($this->page < $this->users->lastPage()) {
            $this->page++;
        }
    }

    public function gotoPage($page,)
    {
        $this->page = $page;
        $this->searchUsers();
    }
    public function updatingSearch()
    {
        $this->page =1;
    }

    public function available($params)
    {
        $this->programmingId = $params['programmationId'] ?? null;
        $this->quotaAvailable = $params['quotaAvailable'] ?? null;
    }

    public function updateUserQuotas()
    {
        $totalQuota = 0;

        foreach ($this->addUser as $key => $value) {
            if ($value) {
                $reservation = Reservation::find($key);
                if ($reservation) {
                    $totalQuota += $reservation->quota;
                }
            }
        }

        $this->totalQuota = $totalQuota;
    }

    private function searchUsers()
    {
        $roleID = 2;
        $usersQuery = User::join('reservations', 'users.id', '=', 'reservations.user_id')
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('reservations.programming_id', 1)
            ->whereHas('roles', function ($query) use ($roleID) {
                $query->where('role_id', $roleID);
            })
            ->where('profiles.document','<>', '')
            ->select(
                'reservations.reservation_date',
                'profiles.document',
                'users.name',
                'reservations.id',
                'reservations.quota',
                'profiles.cell'
            )
            ->orderBy($this->sort, $this->direction);

        if (!empty($this->search)) {
            $search = '%' . $this->search . '%';
            $usersQuery->where(function ($query) use ($search) {
                $query->orWhere('users.name', 'like', $search)
                    ->orWhere('profiles.document', 'like', $search)
                    ->orWhere('profiles.cell', 'like', $search);
            });
        }

        $this->count = $usersQuery->count();
        $this->users = $usersQuery->paginate($this->cant, ['*'], 'waitPage', $this->page);
    }


    public function order($sort)
    {

        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function registerQuota()
    {

        if (count($this->addUser) <> 0) {
            if ($this->totalQuota <= $this->quotaAvailable) {
                foreach ($this->addUser as $key => $value) {
                    if ($value) {
                        $reservation = Reservation::findOrFail($key);
                        if ($reservation) {
                            $reservation->programming_id = $this->programmingId;
                            $reservation->save();
                            $this->confirmReservation($reservation->id);
                            $this->sendEmail($reservation->id);
                            //Enviar correo los qr (funcion)

                        }
                        
                        $this->emitTo('tablet-register', 'render');
                        $this->emitTo('list-users-register', 'render');
                        $this->emit('alert', 'Las reservas fueron asignadas con éxito','success');

                    }
                }
            } else {
                $this->emit('alert', 'no hay cupo disponible','warning');
            }
        } else {
            $this->emit('alert', 'no ha seleccionado usuarios','warning');
        }
    }

    public function editRegisterWait($document, $id)
    {
        $params = [
            'document' => $document,
            'programmationId' => 1,
            'reservationId' => $id
        ];

        $this->emitTo('create-waiting', 'openEdit',  $params);
        $this->emitTo('register-user-in-event', 'close');
    }

    public function render()
    {
        $this->searchUsers();
        return view('livewire.list-waits', [
            'users' => $this->users
        ]);
    }

    public function confirmReservation($reservationId)
    {
        $reservationUpdate = Reservation::findOrFail($reservationId);
        $membersCount = $reservationUpdate->member()->count();

        $reservationUpdate->quota = $membersCount + 1;
        $reservationUpdate->confirmed = 1;
        $reservationUpdate->confirmation_date = now();
        $reservationUpdate->save();


        if ($reservationUpdate->programming_id <> 1) {
            $programming = Programming::findOrFail($reservationUpdate->programming_id);
            $totalReservationsQuota = $programming->reservation()->sum('quota');
            $newQuotaAvailable = $programming->quota - $totalReservationsQuota;


            $programming->update([
                'quota_available' => $newQuotaAvailable
            ]);
        }
    }

    public function sendEmail($reservationId)
    {
        $codes = [];
        $reservationUpdate = Reservation::where('id',$reservationId)
        ->with('member')
        ->with('programming')
        ->with(['user' => function ($query) {
            $query->with('profile');
        }])
        ->first();

        if ($reservationUpdate) {
            $reservationUpdate->qrCodes()->delete();

       foreach ($reservationUpdate->member as $member) {
        $cod = Str::uuid();
        $qr = config('app.url'). '/admin/events/qr/' . $cod;
        $qrCode = new QrCodes([
            'document' => $member->document,
            'code_qr' => $qr,
            'code' => $cod,
            'reservation_id' => $reservationId
        ]);
        $qrCode->save();

        $dateUser = [
            'name' => $member->name,
            'qr' => $qr,
            'isUser' => 0
        ];

        $codes[] = $dateUser;
       }
        
        $codU = Str::uuid();
        $qrU = config('app.url'). '/admin/events/qr/' . $codU;
        $qrCode = new QrCodes([
            'document' => $reservationUpdate->user->profile->document,
            'is_user' => 1,
            'code_qr' => $qrU,
            'code' => $codU,
            'reservation_id' => $reservationId
        ]);
        $qrCode->save();

        $dateUserU = [
            'name' => $reservationUpdate->user->name,
            'qr' => $qrU,
            'isUser' => 1,
            'quota' => $reservationUpdate->quota,
            'date' => $reservationUpdate->programming->initial_date,
            'time' => $reservationUpdate->programming->initial_time
        ];

        $codes[] = $dateUserU;
        if ($reservationUpdate->programming_id != 1) {
            $this->confirmReservation($reservationId);
            $correo = new ReservationVerification($codes);
            $respose = Mail::to($reservationUpdate->user->email)->send($correo);
        }

    }
    }
}
