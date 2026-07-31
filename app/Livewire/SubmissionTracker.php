<?php

namespace App\Livewire;

use App\Models\Submission;
use Livewire\Component;

class SubmissionTracker extends Component
{
    public string  $code      = '';
    public string  $pin       = '';
    public bool    $searched  = false;
    public bool    $found     = false;
    public ?Submission $submission = null;
    public ?string $errorMsg  = null;

    public function track(): void
    {
        $this->validate([
            'code' => ['required', 'string'],
            'pin'  => ['required', 'digits:4'],
        ], [
            'code.required' => 'Masukkan kode pelacakan.',
            'pin.required'  => 'Masukkan PIN 4 digit.',
            'pin.digits'    => 'PIN harus 4 digit angka.',
        ]);

        $this->searched  = true;
        $this->errorMsg  = null;
        $this->submission = null;
        $this->found     = false;

        $submission = Submission::where('tracking_code', strtoupper(trim($this->code)))
            ->with(['category', 'recipient'])
            ->first();

        if (! $submission) {
            $this->errorMsg = 'Kode pelacakan tidak ditemukan.';
            return;
        }

        if (! $submission->verifyPin($this->pin)) {
            $this->errorMsg = 'PIN tidak sesuai.';
            return;
        }

        $this->submission = $submission;
        $this->found      = true;
    }

    public function render()
    {
        return view('livewire.submission-tracker');
    }
}
