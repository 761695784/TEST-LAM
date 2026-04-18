@extends('layouts.app')

@section('title', 'Modifier le destinataire')
@section('page-title', 'Modifier le destinataire')

@section('content')

<div style="max-width:560px;">

    @if($errors->any())
        <div class="lam-alert lam-alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Erreurs :</strong>
                <ul style="margin:6px 0 0; padding-left:18px; font-size:13px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="lam-card">
        <div class="lam-card-header">
            <div>
                <h2 style="font-size:16px; font-weight:700; margin:0;">Modifier le destinataire</h2>
                <p style="font-size:12px; color:var(--text-muted); margin:2px 0 0;">
                    Campagne : <strong style="color:var(--teal);">{{ $campagne->nom }}</strong>
                </p>
            </div>
            <a href="{{ route('campagnes.show', $campagne) }}" class="btn-lam-ghost">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <div class="lam-card-body">
            <form method="POST" action="{{ route('campagnes.destinataires.update', [$campagne, $destinataire]) }}">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- Numéro --}}
                    <div class="col-12">
                        <label class="lam-label">Numéro de téléphone <span style="color:var(--accent);">*</span></label>
                        <input type="text" name="numero_telephone"
                               class="lam-input @error('numero_telephone') border-danger @enderror"
                               value="{{ old('numero_telephone', $destinataire->numero_telephone) }}"
                               placeholder="+221 77 000 00 00"
                               required>
                        @error('numero_telephone')<div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    {{-- Statut appel --}}
                    <div class="col-md-6">
                        <label class="lam-label">Statut d'appel <span style="color:var(--accent);">*</span></label>
                        <select name="statut_appel"
                                class="lam-input @error('statut_appel') border-danger @enderror"
                                onchange="toggleMotifEchec(this.value)"
                                required>
                            @foreach($statuts as $s)
                                <option value="{{ $s }}" {{ old('statut_appel', $destinataire->statut_appel) === $s ? 'selected' : '' }}>
                                    {{ match($s) {
                                        'PENDING'   => 'En attente',
                                        'SENT'      => 'Envoyé',
                                        'ANSWERED'  => 'Répondu',
                                        'FAILED'    => 'Échoué',
                                        'NO_ANSWER' => 'Sans réponse',
                                        default => $s
                                    } }}
                                </option>
                            @endforeach
                        </select>
                        @error('statut_appel')<div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    {{-- Durée appel --}}
                    <div class="col-md-6">
                        <label class="lam-label">Durée d'appel (secondes)</label>
                        <input type="number" name="duree_appel"
                               class="lam-input @error('duree_appel') border-danger @enderror"
                               value="{{ old('duree_appel', $destinataire->duree_appel) }}"
                               min="0" placeholder="ex: 45">
                        @error('duree_appel')<div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    {{-- Motif d'échec --}}
                    <div class="col-12" id="wrapMotifEchec"
                         style="{{ old('statut_appel', $destinataire->statut_appel) === 'FAILED' ? '' : 'display:none;' }}">
                        <label class="lam-label">
                            Motif d'échec <span style="color:var(--accent);">*</span>
                        </label>
                        <input type="text" name="motif_echec"
                               class="lam-input @error('motif_echec') border-danger @enderror"
                               value="{{ old('motif_echec', $destinataire->motif_echec) }}"
                               placeholder="ex: Numéro non attribué, Réseau indisponible…">
                        @error('motif_echec')<div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
                    </div>

                </div>

                <div style="display:flex; gap:10px; margin-top:28px; padding-top:20px; border-top:1px solid var(--border);">
                    <button type="submit" class="btn-lam">
                        <i class="bi bi-check-lg"></i> Enregistrer
                    </button>
                    <a href="{{ route('campagnes.show', $campagne) }}" class="btn-lam-ghost">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleMotifEchec(statut) {
        const wrap  = document.getElementById('wrapMotifEchec');
        const input = wrap.querySelector('input');
        if (statut === 'FAILED') {
            wrap.style.display = 'block';
            input.required = true;
        } else {
            wrap.style.display = 'none';
            input.required = false;
        }
    }
</script>
@endpush
