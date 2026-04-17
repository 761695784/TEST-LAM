@extends('layouts.app')

@section('title', 'Campagnes')
@section('page-title', 'Campagnes Voice')

@section('topbar-actions')
    {{-- Bouton déjà dans le layout --}}
@endsection

@section('content')

{{--  Stats rapides  --}}
<div class="row g-3 mb-4">
    @php
        $statsGlobales = [
            ['label' => 'Total',      'statut' => null,        'icon' => 'bi-collection',    'color' => '#8a93b5', 'bg' => 'rgba(138,147,181,.1)'],
            ['label' => 'En cours',   'statut' => 'RUNNING',   'icon' => 'bi-play-circle',   'color' => '#0fcfb0', 'bg' => 'rgba(15,207,176,.1)'],
            ['label' => 'Planifiées', 'statut' => 'SCHEDULED', 'icon' => 'bi-calendar-event','color' => '#60a5fa', 'bg' => 'rgba(59,130,246,.1)'],
            ['label' => 'Terminées',  'statut' => 'COMPLETED', 'icon' => 'bi-check-circle',  'color' => '#4ade80', 'bg' => 'rgba(34,197,94,.1)'],
        ];
    @endphp

    @foreach($statsGlobales as $s)
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $s['bg'] }}; color:{{ $s['color'] }}">
                <i class="bi {{ $s['icon'] }}"></i>
            </div>
            <div>
                <div class="stat-label">{{ $s['label'] }}</div>
                <div class="stat-value" style="color:{{ $s['color'] }}">
                    @if($s['statut'])
                        {{ \App\Models\Campagne::where('statut', $s['statut'])->count() }}
                    @else
                        {{ \App\Models\Campagne::count() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{--  Barre de filtres  --}}
<form method="GET" action="{{ route('campagnes.index') }}" id="filterForm">
    <div class="filter-bar">
        <i class="bi bi-funnel" style="color:var(--text-muted); font-size:16px;"></i>

        {{-- Recherche --}}
        <div style="flex:1; min-width:200px;">
            <input
                type="text"
                name="recherche"
                class="lam-input"
                placeholder="Rechercher par nom ou pays…"
                value="{{ $filtres['recherche'] ?? '' }}"
                autocomplete="off"
            >
        </div>

        {{-- Filtre statut --}}
        <select name="statut" class="lam-input" style="max-width:180px;" onchange="document.getElementById('filterForm').submit()">
            <option value="">Tous les statuts</option>
            @foreach($statuts as $s)
                <option value="{{ $s }}" {{ ($filtres['statut'] ?? '') === $s ? 'selected' : '' }}>
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

        {{-- Boutons --}}
        <button type="submit" class="btn-lam-ghost">
            <i class="bi bi-search"></i> Filtrer
        </button>

        @if(!empty(array_filter($filtres)))
            <a href="{{ route('campagnes.index') }}" class="btn-lam-ghost">
                <i class="bi bi-x-circle"></i> Réinitialiser
            </a>
        @endif
    </div>
</form>

{{--  Tableau des campagnes  --}}
<div class="lam-card">
    <div class="lam-card-header">
        <div>
            <h2 style="font-size:16px; font-weight:700; margin:0;">Liste des campagnes</h2>
            <p style="font-size:12px; color:var(--text-muted); margin:2px 0 0;">
                {{ $campagnes->total() }} campagne{{ $campagnes->total() > 1 ? 's' : '' }} trouvée{{ $campagnes->total() > 1 ? 's' : '' }}
            </p>
        </div>
        <a href="{{ route('campagnes.create') }}" class="btn-lam">
            <i class="bi bi-plus-lg"></i>
            <span class="d-none d-sm-inline">Nouvelle</span>
        </a>
    </div>

    @if($campagnes->isEmpty())
        <div class="empty-state">
            <i class="bi bi-megaphone"></i>
            <h3>Aucune campagne trouvée</h3>
            <p>
                @if(!empty(array_filter($filtres)))
                    Aucun résultat pour ces filtres.
                    <a href="{{ route('campagnes.index') }}" style="color:var(--accent)">Réinitialiser</a>
                @else
                    Commencez par créer votre première campagne voice.
                @endif
            </p>
            @if(empty(array_filter($filtres)))
                <a href="{{ route('campagnes.create') }}" class="btn-lam" style="margin-top:16px;">
                    <i class="bi bi-plus-lg"></i> Créer une campagne
                </a>
            @endif
        </div>
    @else
        <div style="overflow-x:auto;">
            <table class="lam-table">
                <thead>
                    <tr>
                        <th>Campagne</th>
                        <th>Pays</th>
                        <th>Sender</th>
                        <th>Statut</th>
                        <th>Destinataires</th>
                        <th>Planification</th>
                        <th>Créée le</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campagnes as $campagne)
                    <tr>
                        {{-- Nom --}}
                        <td class="td-primary">
                            <a href="{{ route('campagnes.show', $campagne) }}"
                               style="color:var(--text-primary); text-decoration:none; font-weight:600;"
                               class="hover-accent">
                                {{ $campagne->nom }}
                            </a>
                        </td>

                        {{-- Pays --}}
                        <td>
                            <span style="display:flex; align-items:center; gap:6px;">
                                <i class="bi bi-geo-alt" style="color:var(--text-muted);"></i>
                                {{ $campagne->pays }}
                            </span>
                        </td>

                        {{-- Sender --}}
                        <td>
                            <code style="background:var(--bg-base); padding:2px 8px; border-radius:4px; font-size:12px; color:var(--teal);">
                                {{ $campagne->sender }}
                            </code>
                        </td>

                        {{-- Statut --}}
                        <td>
                            <span class="badge-statut badge-{{ strtolower($campagne->statut) }}">
                                {{ $campagne->labelStatut() }}
                            </span>
                        </td>

                        {{-- Destinataires --}}
                        <td>
                            <span style="display:flex; align-items:center; gap:6px;">
                                <i class="bi bi-people" style="color:var(--text-muted);"></i>
                                <strong style="color:var(--text-primary);">{{ $campagne->destinataires_count }}</strong>
                            </span>
                        </td>

                        {{-- Date planification --}}
                        <td style="font-size:13px;">
                            @if($campagne->date_planification)
                                <span style="color:var(--text-secondary);">
                                    <i class="bi bi-calendar3" style="color:var(--text-muted); margin-right:4px;"></i>
                                    {{ $campagne->date_planification->format('d/m/Y H:i') }}
                                </span>
                            @else
                                <span style="color:var(--text-muted);">—</span>
                            @endif
                        </td>

                        {{-- Date création --}}
                        <td style="font-size:12px; color:var(--text-muted);">
                            {{ $campagne->created_at->format('d/m/Y') }}
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div style="display:flex; gap:6px; justify-content:flex-end; flex-wrap:wrap;">

                                {{-- Voir --}}
                                <a href="{{ route('campagnes.show', $campagne) }}"
                                   class="btn-lam-ghost" style="padding:5px 10px;" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Modifier --}}
                                @if($campagne->estModifiable())
                                    <a href="{{ route('campagnes.edit', $campagne) }}"
                                       class="btn-lam-ghost" style="padding:5px 10px;" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif

                                {{-- Changer statut --}}
                                @if($campagne->estModifiable())
                                    <button
                                        class="btn-lam-ghost btn-statut"
                                        style="padding:5px 10px;"
                                        title="Changer le statut"
                                        data-id="{{ $campagne->id }}"
                                        data-statut="{{ $campagne->statut }}"
                                        data-url="{{ route('campagnes.statut', $campagne) }}"
                                        onclick="ouvrirModalStatut(this)">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                @endif

                                {{-- Supprimer --}}
                                @if($campagne->estSupprimable())
                                    <form method="POST" action="{{ route('campagnes.destroy', $campagne) }}"
                                          onsubmit="return confirm('Supprimer « {{ addslashes($campagne->nom) }} » ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-lam-danger" style="padding:5px 10px;" title="Supprimer">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($campagnes->hasPages())
        <div style="padding:16px 20px; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
            <span style="font-size:12px; color:var(--text-muted);">
                Affichage {{ $campagnes->firstItem() }}–{{ $campagnes->lastItem() }} sur {{ $campagnes->total() }}
            </span>
            {{ $campagnes->links('pagination::bootstrap-5') }}
        </div>
        @endif
    @endif
</div>

{{--  Modal changement de statut  --}}
<div class="modal fade" id="modalStatut" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content" style="background:var(--bg-card); border:1px solid var(--border-light); border-radius:var(--radius);">
            <div class="modal-header" style="border-bottom:1px solid var(--border); padding:16px 20px;">
                <h5 class="modal-title" style="font-family:'Syne',sans-serif; font-size:16px;">
                    <i class="bi bi-arrow-repeat" style="color:var(--accent);"></i>
                    Changer le statut
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:20px;">
                <p style="font-size:13px; color:var(--text-secondary); margin-bottom:16px;">
                    Campagne : <strong id="modalCampagneNom" style="color:var(--text-primary);"></strong>
                </p>
                <label class="lam-label">Nouveau statut</label>
                <select id="selectNouveauStatut" class="lam-input">
                    <option value="DRAFT">Brouillon</option>
                    <option value="SCHEDULED">Planifiée</option>
                    <option value="RUNNING">En cours</option>
                    <option value="COMPLETED">Terminée</option>
                    <option value="CANCELLED">Annulée</option>
                </select>
            </div>
            <div class="modal-footer" style="border-top:1px solid var(--border); padding:14px 20px; gap:8px;">
                <button type="button" class="btn-lam-ghost" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn-lam" id="btnConfirmerStatut" onclick="confirmerStatut()">
                    <i class="bi bi-check-lg"></i> Confirmer
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let modalStatutUrl    = '';
    let modalCampagneNom  = '';
    const bsModal         = new bootstrap.Modal(document.getElementById('modalStatut'));

    function ouvrirModalStatut(btn) {
        modalStatutUrl   = btn.dataset.url;
        modalCampagneNom = btn.closest('tr').querySelector('a').textContent.trim();
        const statutActuel = btn.dataset.statut;

        document.getElementById('modalCampagneNom').textContent = modalCampagneNom;

        const select = document.getElementById('selectNouveauStatut');
        Array.from(select.options).forEach(opt => {
            opt.disabled = opt.value === statutActuel;
            if (opt.value === statutActuel) opt.text += ' (actuel)';
        });
        select.value = statutActuel;
        bsModal.show();
    }

    async function confirmerStatut() {
        const statut = document.getElementById('selectNouveauStatut').value;
        const btn    = document.getElementById('btnConfirmerStatut');

        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> En cours…';

        try {
            const res = await fetch(modalStatutUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ statut })
            });

            const data = await res.json();

            if (res.ok && data.succes) {
                bsModal.hide();
                // Met à jour le badge sans rechargement
                const allBtns = document.querySelectorAll('.btn-statut');
                allBtns.forEach(b => {
                    if (b.dataset.url === modalStatutUrl) {
                        const badge = b.closest('tr').querySelector('.badge-statut');
                        if (badge) {
                            badge.className = 'badge-statut badge-' + data.statut.toLowerCase();
                            badge.textContent = data.label;
                        }
                        b.dataset.statut = data.statut;
                    }
                });
                afficherFlash('Statut mis à jour : ' + data.label, 'success');
            } else {
                afficherFlash(data.erreur || 'Erreur lors du changement de statut.', 'error');
            }
        } catch(e) {
            afficherFlash('Erreur réseau. Veuillez réessayer.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Confirmer';
        }
    }

    function afficherFlash(msg, type) {
        const div = document.createElement('div');
        div.className = `lam-alert lam-alert-${type === 'success' ? 'success' : 'error'}`;
        div.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'}"></i> ${msg}`;
        document.querySelector('.page-content').prepend(div);
        setTimeout(() => {
            div.style.transition = 'opacity .4s';
            div.style.opacity = '0';
            setTimeout(() => div.remove(), 400);
        }, 4000);
    }
</script>
@endpush
