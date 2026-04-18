@extends('layouts.app')

@section('title', 'Nouvelle campagne')
@section('page-title', 'Nouvelle campagne')

@section('content')

<div style="max-width:760px;">

    {{-- Erreurs de validation --}}
    @if($errors->any())
        <div class="lam-alert lam-alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul style="margin:6px 0 0; padding-left:18px; font-size:13px;">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="lam-card">
        <div class="lam-card-header">
            <div>
                <h2 style="font-size:16px; font-weight:700; margin:0;">Créer une campagne voice</h2>
                <p style="font-size:12px; color:var(--text-muted); margin:2px 0 0;">Remplissez les informations de la campagne</p>
            </div>
            <a href="{{ route('campagnes.index') }}" class="btn-lam-ghost">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <div class="lam-card-body">
            <form method="POST" action="{{ route('campagnes.store') }}">
                @csrf

                <div class="row g-4">

                    {{-- Nom --}}
                    <div class="col-md-6">
                        <label class="lam-label" for="nom">Nom de la campagne <span style="color:var(--accent);">*</span></label>
                        <input type="text" id="nom" name="nom"
                               class="lam-input @error('nom') border-danger @enderror"
                               value="{{ old('nom') }}"
                               placeholder="ex: Promo Ramadan 2025"
                               required>
                        @error('nom')
                            <div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pays --}}
                    <div class="col-md-6">
                        <label class="lam-label" for="pays">Pays <span style="color:var(--accent);">*</span></label>
                        <input type="text" id="pays" name="pays"
                               class="lam-input @error('pays') border-danger @enderror"
                               value="{{ old('pays') }}"
                               placeholder="ex: Sénégal"
                               required>
                        @error('pays')
                            <div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Sender --}}
                    <div class="col-md-6">
                        <label class="lam-label" for="sender">
                            Sender (expéditeur) <span style="color:var(--accent);">*</span>
                        </label>
                        <input type="text" id="sender" name="sender"
                               class="lam-input @error('sender') border-danger @enderror"
                               value="{{ old('sender') }}"
                               placeholder="ex: LAM_SEN"
                               maxlength="100"
                               required>
                        @error('sender')
                            <div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Statut --}}
                    <div class="col-md-6">
                        <label class="lam-label" for="statut">Statut <span style="color:var(--accent);">*</span></label>
                        <select id="statut" name="statut"
                                class="lam-input @error('statut') border-danger @enderror"
                                onchange="toggleDatePlanification(this.value)"
                                required>
                            @foreach($statuts as $s)
                                <option value="{{ $s }}" {{ old('statut', 'DRAFT') === $s ? 'selected' : '' }}>
                                    {{ match($s) {
                                        'DRAFT'     => 'Brouillon',
                                        'SCHEDULED' => 'Planifiée',
                                        'RUNNING'   => 'En cours',
                                        'COMPLETED' => 'Terminée',
                                        'CANCELLED' => 'Annulée',
                                        default     => $s
                                    } }}
                                </option>
                            @endforeach
                        </select>
                        @error('statut')
                            <div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Date de planification --}}
                    <div class="col-12" id="wrapDatePlanification"
                         style="{{ old('statut', 'DRAFT') === 'SCHEDULED' ? '' : 'display:none;' }}">
                        <label class="lam-label" for="date_planification">
                            Date de planification
                            <span style="color:var(--accent);" id="starDatePlan">*</span>
                        </label>
                        <input type="datetime-local" id="date_planification" name="date_planification"
                               class="lam-input @error('date_planification') border-danger @enderror"
                               value="{{ old('date_planification') }}"
                               min="{{ now()->format('Y-m-d\TH:i') }}">
                        @error('date_planification')
                            <div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Message --}}
                    <div class="col-12">
                        <label class="lam-label" for="message">
                            Message vocal <span style="color:var(--accent);">*</span>
                        </label>
                        <textarea id="message" name="message"
                                  class="lam-input @error('message') border-danger @enderror"
                                  placeholder="Entrez le contenu du message vocal qui sera diffusé aux destinataires…"
                                  rows="5"
                                  required>{{ old('message') }}</textarea>
                        <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">
                            <span id="charCount">0</span> caractère(s)
                        </div>
                        @error('message')
                            <div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- Actions --}}
                <div style="display:flex; gap:10px; margin-top:28px; padding-top:20px; border-top:1px solid var(--border);">
                    <button type="submit" class="btn-lam">
                        <i class="bi bi-check-lg"></i> Créer la campagne
                    </button>
                    <a href="{{ route('campagnes.index') }}" class="btn-lam-ghost">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleDatePlanification(statut) {
        const wrap = document.getElementById('wrapDatePlanification');
        const input = document.getElementById('date_planification');
        const star  = document.getElementById('starDatePlan');

        if (statut === 'SCHEDULED') {
            wrap.style.display = 'block';
            input.required = true;
        } else {
            wrap.style.display = 'none';
            input.required = false;
            input.value = '';
        }
    }

    // Compteur de caractères
    const msgArea   = document.getElementById('message');
    const charCount = document.getElementById('charCount');

    function updateCount() {
        charCount.textContent = msgArea.value.length;
    }

    msgArea.addEventListener('input', updateCount);
    updateCount();
</script>
@endpush
