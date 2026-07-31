<?php

namespace App\Livewire;

use App\Enums\SubmissionStatus;
use App\Enums\SubmissionType;
use App\Models\Submission;
use App\Models\SubmissionCategory;
use App\Models\Umkm;
use App\Models\Wisata;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;


class SubmissionForm extends Component
{
    use WithFileUploads;

    // Captcha token for submission
    public string $recaptchaToken = '';

    // Pre-filled when embedded on detail pages
    public ?string $recipientType = null;  // 'wisata'|'umkm'|'profil'|null
    public ?int    $recipientId   = null;
    public ?string $recipientName = null;  // display label

    // Form fields
    public string  $type          = '';
    public string  $title         = '';
    public string  $description   = '';
    public ?int    $categoryId    = null;
    public string  $identityMode  = 'anonymous';
    public string  $reporterName  = '';
    public string  $reporterPhone = '';
    public string  $reporterEmail = '';
    public ?string $incidentDate  = null;
    public ?string $locationDescription = null;
    public $attachment = null;

    // For standalone form: allow selecting recipient
    public string  $recipientChoice = 'pemerintah'; // pemerintah|wisata|umkm

    // Success state
    public bool    $submitted     = false;
    public ?string $trackingCode  = null;
    public ?string $trackingPin   = null; // shown once, then gone

    public function mount(
        ?string $recipientType = null,
        ?int    $recipientId   = null,
        ?string $recipientName = null,
    ): void {
        $this->recipientType = $recipientType;
        $this->recipientId   = $recipientId;
        $this->recipientName = $recipientName;

        // If pre-filled, lock the recipient choice
        if ($recipientType) {
            $this->recipientChoice = $recipientType;
        }
    }

    public function updatedRecipientChoice(): void
    {
        // Reset recipient when standalone user changes type
        if (! $this->recipientType) {
            $this->recipientId = null;
        }
    }

    protected function rules(): array
    {
        return [
            'type'                => ['required', 'in:' . implode(',', array_column(SubmissionType::cases(), 'value'))],
            'title'               => ['required', 'string', 'min:5', 'max:255'],
            'description'         => ['required', 'string', 'min:20'],
            'categoryId'          => ['nullable', 'exists:submission_categories,id'],
            'identityMode'        => ['required', 'in:anonymous,identified'],
            'reporterName'        => ['required_if:identityMode,identified', 'nullable', 'string', 'max:255'],
            'reporterPhone'       => ['nullable', 'string', 'max:20'],
            'reporterEmail'       => ['nullable', 'email', 'max:255'],
            'incidentDate'        => ['nullable', 'date', 'before_or_equal:today'],
            'locationDescription' => ['nullable', 'string', 'max:255'],
            'attachment'          => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf,mp4'],
        ];
    }

    protected function messages(): array
    {
        return [
            'type.required'             => 'Pilih jenis aspirasi.',
            'title.required'            => 'Judul wajib diisi.',
            'title.min'                 => 'Judul minimal 5 karakter.',
            'description.required'      => 'Deskripsi wajib diisi.',
            'description.min'           => 'Deskripsi minimal 20 karakter.',
            'reporterName.required_if'  => 'Nama wajib diisi jika memilih identitas teridentifikasi.',
            'reporterEmail.email'       => 'Format email tidak valid.',
            'attachment.max'            => 'Ukuran file maksimal 5MB.',
            'attachment.mimes'          => 'Format file: JPG, PNG, PDF, atau MP4.',
            'incidentDate.before_or_equal' => 'Tanggal kejadian tidak boleh di masa depan.',
        ];
    }

    public function submitWithToken(string $token): void
    {
        $this->recaptchaToken = $token;

        $this->submit();
    }

    public function submit(): void
    {
        $this->validate();

        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret'   => config('services.recaptcha.secret_key'),
                'response' => $this->recaptchaToken,
                'remoteip' => request()->ip(),
            ]
        );

        $result = $response->json();

        // Configure the best threshold
        if (
            !($result['success'] ?? false)
            || ($result['score'] ?? 0) < 0.5
            || ($result['action'] ?? '') !== 'submission'
        ) {
            $this->addError('captcha', 'Verifikasi keamanan gagal.');
            return;
        }

        // Resolve recipient
        $recipientType = null;
        $recipientId   = null;

        if ($this->recipientType) {
            // Embedded: already set
            $recipientType = $this->recipientType;
            $recipientId   = $this->recipientId;
        } elseif ($this->recipientChoice === 'wisata' && $this->recipientId) {
            $recipientType = 'wisata';
            $recipientId   = $this->recipientId;
        } elseif ($this->recipientChoice === 'umkm' && $this->recipientId) {
            $recipientType = 'umkm';
            $recipientId   = $this->recipientId;
        }
        // else: pemerintah → null recipient

        // Upload attachment
        $attachmentPath = null;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('submissions', 'public');
        }

        // Generate tracking credentials
        $pin             = Submission::generatePin();
        $trackingCode    = Submission::generateTrackingCode();

        Submission::create([
            'tracking_code'        => $trackingCode,
            'tracking_pin_hash'    => Hash::make($pin),
            'type'                 => $this->type,
            'title'                => $this->title,
            'description'          => $this->description,
            'status'               => SubmissionStatus::Diterima->value,
            'priority'             => 'normal',
            'category_id'          => $this->categoryId,
            'recipient_type'       => $recipientType,
            'recipient_id'         => $recipientId,
            'identity_mode'        => $this->identityMode,
            'reporter_name'        => $this->identityMode === 'identified' ? $this->reporterName : null,
            'reporter_phone'       => $this->reporterPhone ?: null,
            'reporter_email'       => $this->reporterEmail ?: null,
            'location_description' => $this->locationDescription,
            'incident_date'        => $this->incidentDate,
            'attachment'           => $attachmentPath,
            'submitted_at'         => now(),
        ]);

        $this->trackingCode = $trackingCode;
        $this->trackingPin  = $pin;
        $this->submitted    = true;
    }

    public function render()
    {
        $categories = SubmissionCategory::where('active', true)->orderBy('nama')->get();

        $wisataOptions = $this->recipientChoice === 'wisata' && ! $this->recipientType
            ? Wisata::where('publish', true)->orderBy('nama')->pluck('nama', 'id')
            : collect();

        $umkmOptions = $this->recipientChoice === 'umkm' && ! $this->recipientType
            ? Umkm::where('publish', true)->orderBy('nama')->pluck('nama', 'id')
            : collect();

        return view('livewire.submission-form', compact('categories', 'wisataOptions', 'umkmOptions'));
    }
}
