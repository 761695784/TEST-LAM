@extends('layouts.app')

@section('title', 'Modifier — ' . $campagne->nom)
@section('page-title', 'Modifier la campagne')

@section('content')

<div style="max-width:760px;">

    @if($errors->any())
        <div class="lam-alert lam-alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul style="margin:6px 0 0; padding-left:18px; font-size:13px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Alerte si non modifiable --}}
    @unless($campagne->estModifiable())
        <div class="lam-alert lam-alert-error">
            <i class="bi bi-lock-fill"></i>
            Cette campagne est <strong>{{ $campagne->labelStatut() }}</strong> et ne peut plus être modifiée.
        </div>
    @endunless

    <div class="lam-card">
        <div class="lam-card-header">
            <div>
                <h2 style="font-size:16px; font-weight:700; margin:0;">{{ $campagne->nom }}</h2>
                <div style="margin-top:4px;">
                    <span class="badge-statut badge-{{ strtolower($campagne->statut) }}">
                        {{ $campagne->labelStatut() }}
                    </span>
                </div>
            </div>
            <a href="{{ route('campagnes.show', $campagne) }}" class="btn-lam-ghost">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <div class="lam-card-body">
            <form method="POST" action="{{ route('campagnes.update', $campagne) }}">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    <div class="col-md-6">
                        <label class="lam-label">Nom <span style="color:var(--accent);">*</span></label>
                        <input type="text" name="nom"
                               class="lam-input @error('nom') border-danger @enderror"
                               value="{{ old('nom', $campagne->nom) }}"
                               {{ !$campagne->estModifiable() ? 'disabled' : '' }} required>
                        @error('nom')<div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="lam-label">Pays <span style="color:var(--accent);">*</span></label>
                        <input type="text" name="pays"
                               class="lam-input @error('pays') border-danger @enderror"
                               value="{{ old('pays', $campagne->pays) }}"
                               {{ !$campagne->estModifiable() ? 'disabled' : '' }} required>
                        @error('pays')<div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="lam-label">Sender <span style="color:var(--accent);">*</span></label>
                        <input type="text" name="sender"
                               class="lam-input @error('sender') border-danger @enderror"
                               value="{{ old('sender', $campagne->sender) }}"
                               {{ !$campagne->estModifiable() ? 'disabled' : '' }} required>
                        @error('sender')<div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="lam-label">Statut <span style="color:var(--accent);">*</span></label>
                        <select name="statut"
                                class="lam-input @error('statut') border-danger @enderror"
                                onchange="toggleDatePlanification(this.value)"
                                {{ !$campagne->estModifiable() ? 'disabled' : '' }} required>
                            @foreach($statuts as $s)
                                <option value="{{ $s }}" {{ old('statut', $campagne->statut) === $s ? 'selected' : '' }}>
                                    {{ match($s) {
                                        'DRAFT'     => 'Brouillon',
                                        'SCHEDULED' => 'Planifiée',
                                        'RUNNING'   => 'En cours',
                                        'COMPLETED' => 'Terminée',
                                        'CANCELLED' => 'Annulée',
                                        default => $s
                                    } }}
                                </option>
                            @endforeach
                        </select>
                        @error('statut')<div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="col-12" id="wrapDatePlanification"
                         style="{{ old('statut', $campagne->statut) === 'SCHEDULED' ? '' : 'display:none;' }}">
                        <label class="lam-label">Date de planification <span style="color:var(--accent);">*</span></label>
                        <input type="datetime-local" name="date_planification"
                               class="lam-input @error('date_planification') border-danger @enderror"
                               value="{{ old('date_planification', $campagne->date_planification?->format('Y-m-d\TH:i')) }}"
                               {{ !$campagne->estModifiable() ? 'disabled' : '' }}>
                        @error('date_planification')<div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="lam-label">Message vocal <span style="color:var(--accent);">*</span></label>
                        <textarea name="message" rows="5"
                                  class="lam-input @error('message') border-danger @enderror"
                                  id="message"
                                  {{ !$campagne->estModifiable() ? 'disabled' : '' }}
                                  required>{{ old('message', $campagne->message) }}</textarea>
                        <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">
                            <span id="charCount">0</span> caractère(s)
                        </div>
                        @error('message')<div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
                    </div>

                </div>

                @if($campagne->estModifiable())
                <div style="display:flex; gap:10px; margin-top:28px; padding-top:20px; border-top:1px solid var(--border);">
                    <button type="submit" class="btn-lam">
                        <i class="bi bi-check-lg"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('campagnes.show', $campagne) }}" class="btn-lam-ghost">Annuler</a>
                </div>
                @endif

            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleDatePlanification(statut) {
        const wrap  = document.getElementById('wrapDatePlanification');
        const input = wrap.querySelector('input');
        if (statut === 'SCHEDULED') {
            wrap.style.display = 'block';
            input.required = true;
        } else {
            wrap.style.display = 'none';
            input.required = false;
        }
    }

    const msgArea   = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    function updateCount() { charCount.textContent = msgArea.value.length; }
    msgArea?.addEventListener('input', updateCount);
    updateCount();
</script>
@endpush
