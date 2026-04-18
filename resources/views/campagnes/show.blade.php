@extends('layouts.app')

@section('title', $campagne->nom)
@section('page-title', $campagne->nom)

@section('topbar-actions')
    @if($campagne->estModifiable())
        <a href="{{ route('campagnes.edit', $campagne) }}" class="btn-lam-ghost">
            <i class="bi bi-pencil"></i>
            <span class="d-none d-sm-inline">Modifier</span>
        </a>
    @endif
@endsection

@section('content')

{{--  Header campagne  --}}
<div class="lam-card mb-4">
    <div class="lam-card-body">
        <div style="display:flex; flex-wrap:wrap; align-items:flex-start; justify-content:space-between; gap:16px;">

            {{-- Infos principales --}}
            <div>
                <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:8px;">
                    <h1 style="font-size:22px; font-weight:800; margin:0;">{{ $campagne->nom }}</h1>
                    <span class="badge-statut badge-{{ strtolower($campagne->statut) }}">
                        {{ $campagne->labelStatut() }}
                    </span>
                </div>

                <div style="display:flex; flex-wrap:wrap; gap:20px; font-size:13px; color:var(--text-secondary);">
                    <span><i class="bi bi-geo-alt" style="color:var(--text-muted);"></i> {{ $campagne->pays }}</span>
                    <span>
                        <i class="bi bi-broadcast" style="color:var(--text-muted);"></i>
                        Sender : <code style="background:var(--bg-base); padding:1px 7px; border-radius:4px; color:var(--teal); font-size:12px;">{{ $campagne->sender }}</code>
                    </span>
                    @if($campagne->date_planification)
                        <span><i class="bi bi-calendar3" style="color:var(--text-muted);"></i> {{ $campagne->date_planification->format('d/m/Y à H:i') }}</span>
                    @endif
                    <span><i class="bi bi-clock" style="color:var(--text-muted);"></i> Créée le {{ $campagne->created_at->format('d/m/Y') }}</span>
                </div>

                @if($campagne->message)
                <div style="margin-top:14px; padding:12px 16px; background:var(--bg-base); border-radius:var(--radius-sm); border-left:3px solid var(--accent); max-width:600px;">
                    <div style="font-size:11px; font-weight:600; color:var(--accent); text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px;">
                        <i class="bi bi-chat-quote"></i> Message vocal
                    </div>
                    <p style="font-size:13px; color:var(--text-secondary); margin:0; line-height:1.6;">{{ $campagne->message }}</p>
                </div>
                @endif
            </div>

            {{-- Actions campagne --}}
            <div style="display:flex; flex-wrap:wrap; gap:8px; align-items:flex-start;">
                @if($campagne->estModifiable())
                    <a href="{{ route('campagnes.edit', $campagne) }}" class="btn-lam-ghost">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                    <button class="btn-lam-ghost"
                            onclick="ouvrirModalStatut('{{ route('campagnes.statut', $campagne) }}', '{{ $campagne->statut }}', '{{ addslashes($campagne->nom) }}')">
                        <i class="bi bi-arrow-repeat"></i> Statut
                    </button>
                @endif
                @if($campagne->estSupprimable())
                    <form method="POST" action="{{ route('campagnes.destroy', $campagne) }}"
                          onsubmit="return confirm('Supprimer cette campagne ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-lam-danger">
                            <i class="bi bi-trash3"></i> Supprimer
                        </button>
                    </form>
                @endif
                <a href="{{ route('campagnes.index') }}" class="btn-lam-ghost">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{--  Statistiques  --}}
<div class="row g-3 mb-4">
    @php
        $statsConfig = [
            ['label' => 'Total destinataires', 'key' => 'total',        'icon' => 'bi-people',          'color' => '#8a93b5', 'bg' => 'rgba(138,147,181,.1)'],
            ['label' => 'En attente',           'key' => 'en_attente',  'icon' => 'bi-hourglass-split', 'color' => '#fbbf24', 'bg' => 'rgba(251,191,36,.1)'],
            ['label' => 'Réussis',              'key' => 'reussis',     'icon' => 'bi-check-circle',    'color' => '#4ade80', 'bg' => 'rgba(34,197,94,.1)'],
            ['label' => 'Échoués',              'key' => 'echoues',     'icon' => 'bi-x-circle',        'color' => '#f87171', 'bg' => 'rgba(239,68,68,.1)'],
            ['label' => 'Sans réponse',         'key' => 'sans_reponse','icon' => 'bi-telephone-x',     'color' => '#94a3b8', 'bg' => 'rgba(100,116,139,.1)'],
            ['label' => 'Durée totale',         'key' => 'duree',       'icon' => 'bi-stopwatch',       'color' => '#0fcfb0', 'bg' => 'rgba(15,207,176,.1)'],
        ];
    @endphp

    @foreach($statsConfig as $sc)
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card" style="flex-direction:column; align-items:flex-start; padding:16px;">
            <div class="stat-icon" style="background:{{ $sc['bg'] }}; color:{{ $sc['color'] }}; width:38px; height:38px; font-size:16px; margin-bottom:10px;">
                <i class="bi {{ $sc['icon'] }}"></i>
            </div>
            <div class="stat-label">{{ $sc['label'] }}</div>
            <div class="stat-value" style="color:{{ $sc['color'] }}; font-size:22px;">
                @if($sc['key'] === 'duree')
                    @php
                        $sec = $stats['duree_totale'];
                        $m = intdiv($sec, 60); $s = $sec % 60;
                    @endphp
                    {{ $m > 0 ? $m.'m '.$s.'s' : $s.'s' }}
                @else
                    {{ $stats[$sc['key']] }}
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

{{--  Section destinataires  --}}
<div class="lam-card">
    <div class="lam-card-header" style="flex-wrap:wrap; gap:12px;">
        <div>
            <h2 style="font-size:16px; font-weight:700; margin:0;">Destinataires</h2>
            <p style="font-size:12px; color:var(--text-muted); margin:2px 0 0;">
                {{ $destinataires->total() }} destinataire{{ $destinataires->total() > 1 ? 's' : '' }}
            </p>
        </div>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            @if($campagne->estModifiable())
                <button class="btn-lam" onclick="togglePanel('panelAjout')">
                    <i class="bi bi-person-plus"></i>
                    <span class="d-none d-sm-inline">Ajouter</span>
                </button>
                <button class="btn-lam-ghost" onclick="togglePanel('panelCsv')">
                    <i class="bi bi-filetype-csv"></i>
                    <span class="d-none d-sm-inline">Import CSV</span>
                </button>
            @endif
        </div>
    </div>

    {{-- Panel ajout destinataires --}}
    @if($campagne->estModifiable())
    <div id="panelAjout" style="display:none; padding:20px; border-bottom:1px solid var(--border); background:var(--bg-base);">
        <form method="POST" action="{{ route('campagnes.destinataires.store', $campagne) }}">
            @csrf
            <div style="margin-bottom:12px;">
                <label class="lam-label">Numéros de téléphone</label>
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:8px;">
                    Entrez un ou plusieurs numéros (un par ligne)
                </div>
                <div id="numerosWrap">
                    <div class="numero-row" style="display:flex; gap:8px; margin-bottom:8px; align-items:center;">
                        <input type="text" name="numeros[]"
                               class="lam-input"
                               placeholder="+221 77 000 00 00"
                               style="max-width:280px;">
                        <button type="button" class="btn-lam-ghost" style="padding:8px 12px;" onclick="ajouterNumero()">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
            @error('numeros')<div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
            @error('numeros.*')<div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
            <div style="display:flex; gap:8px; margin-top:8px;">
                <button type="submit" class="btn-lam">
                    <i class="bi bi-check-lg"></i> Ajouter
                </button>
                <button type="button" class="btn-lam-ghost" onclick="togglePanel('panelAjout')">Annuler</button>
            </div>
        </form>
    </div>

    {{-- Panel import CSV --}}
    <div id="panelCsv" style="display:none; padding:20px; border-bottom:1px solid var(--border); background:var(--bg-base);">
        <form method="POST" action="{{ route('campagnes.destinataires.import', $campagne) }}" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom:12px;">
                <label class="lam-label">Fichier CSV</label>
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:8px;">
                    Format attendu : une colonne <code style="color:var(--teal);">numero_telephone</code>, un numéro par ligne.
                </div>
                <input type="file" name="fichier_csv" accept=".csv,.txt"
                       class="lam-input @error('fichier_csv') border-danger @enderror"
                       style="padding:8px 14px; cursor:pointer;">
                @error('fichier_csv')<div class="form-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>@enderror
            </div>
            <div style="display:flex; gap:8px;">
                <button type="submit" class="btn-lam">
                    <i class="bi bi-upload"></i> Importer
                </button>
                <button type="button" class="btn-lam-ghost" onclick="togglePanel('panelCsv')">Annuler</button>
            </div>
        </form>
    </div>
    @endif

    {{-- Filtre par statut d'appel --}}
    <div style="padding:14px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
        <span style="font-size:12px; color:var(--text-muted);"><i class="bi bi-funnel"></i> Filtrer :</span>
        <a href="{{ route('campagnes.show', $campagne) }}"
           class="badge-call {{ !$filtre_statut ? 'badge-answered' : 'badge-pending' }}"
           style="text-decoration:none; cursor:pointer;">
            Tous
        </a>
        @foreach($statuts_appel as $sa)
        <a href="{{ route('campagnes.show', [$campagne, 'statut_appel' => $sa]) }}"
           class="badge-call badge-{{ strtolower($sa) }} {{ $filtre_statut === $sa ? 'ring' : '' }}"
           style="text-decoration:none; cursor:pointer; {{ $filtre_statut === $sa ? 'outline:2px solid currentColor; outline-offset:2px;' : '' }}">
            {{ match($sa) {
                'PENDING'   => 'En attente',
                'SENT'      => 'Envoyé',
                'ANSWERED'  => 'Répondu',
                'FAILED'    => 'Échoué',
                'NO_ANSWER' => 'Sans réponse',
                default     => $sa
            } }}
        </a>
        @endforeach
    </div>

    {{-- Tableau destinataires --}}
    @if($destinataires->isEmpty())
        <div class="empty-state">
            <i class="bi bi-person-x"></i>
            <h3>Aucun destinataire</h3>
            <p>
                @if($filtre_statut)
                    Aucun destinataire avec ce statut.
                    <a href="{{ route('campagnes.show', $campagne) }}" style="color:var(--accent)">Voir tous</a>
                @else
                    Ajoutez des destinataires à cette campagne.
                @endif
            </p>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table class="lam-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Numéro de téléphone</th>
                        <th>Statut d'appel</th>
                        <th>Durée</th>
                        <th>Motif d'échec</th>
                        <th>Ajouté le</th>
                        @if($campagne->estModifiable())
                        <th style="text-align:right;">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($destinataires as $dest)
                    <tr>
                        <td style="color:var(--text-muted); font-size:12px;">{{ $loop->iteration }}</td>
                        <td class="td-primary">
                            <i class="bi bi-telephone" style="color:var(--text-muted); margin-right:6px;"></i>
                            {{ $dest->numero_telephone }}
                        </td>
                        <td>
                            <span class="badge-call badge-{{ strtolower($dest->statut_appel) }}">
                                {{ $dest->labelStatutAppel() }}
                            </span>
                        </td>
                        <td style="font-size:13px;">
                            @if($dest->duree_appel)
                                <span style="color:var(--teal);">
                                    <i class="bi bi-stopwatch"></i> {{ $dest->dureeFormatee() }}
                                </span>
                            @else
                                <span style="color:var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td style="font-size:13px; max-width:200px;">
                            @if($dest->motif_echec)
                                <span style="color:#f87171;" title="{{ $dest->motif_echec }}">
                                    {{ Str::limit($dest->motif_echec, 40) }}
                                </span>
                            @else
                                <span style="color:var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td style="font-size:12px; color:var(--text-muted);">
                            {{ $dest->created_at->format('d/m/Y') }}
                        </td>
                        @if($campagne->estModifiable())
                        <td>
                            <div style="display:flex; gap:6px; justify-content:flex-end;">
                                <a href="{{ route('campagnes.destinataires.edit', [$campagne, $dest]) }}"
                                   class="btn-lam-ghost" style="padding:5px 10px;" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('campagnes.destinataires.destroy', [$campagne, $dest]) }}"
                                      onsubmit="return confirm('Supprimer ce destinataire ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-lam-danger" style="padding:5px 10px;" title="Supprimer">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination destinataires --}}
        @if($destinataires->hasPages())
        <div style="padding:16px 20px; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
            <span style="font-size:12px; color:var(--text-muted);">
                Affichage {{ $destinataires->firstItem() }}–{{ $destinataires->lastItem() }} sur {{ $destinataires->total() }}
            </span>
            {{ $destinataires->links('pagination::bootstrap-5') }}
        </div>
        @endif
    @endif
</div>

{{--  Modal changement statut  --}}
<div class="modal fade" id="modalStatut" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content" style="background:var(--bg-card); border:1px solid var(--border-light); border-radius:var(--radius);">
            <div class="modal-header" style="border-bottom:1px solid var(--border); padding:16px 20px;">
                <h5 class="modal-title" style="font-family:'Syne',sans-serif; font-size:16px;">
                    <i class="bi bi-arrow-repeat" style="color:var(--accent);"></i> Changer le statut
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
    //  Panels accordion
    function togglePanel(id) {
        const panel = document.getElementById(id);
        const isOpen = panel.style.display !== 'none';

        // Ferme tous les panels
        ['panelAjout','panelCsv'].forEach(pid => {
            const p = document.getElementById(pid);
            if (p) p.style.display = 'none';
        });

        // Ouvre le bon si on ne le fermait pas
        if (!isOpen) panel.style.display = 'block';
    }

    //  Ajout dynamique de champs numéros
    function ajouterNumero() {
        const wrap = document.getElementById('numerosWrap');
        const div  = document.createElement('div');
        div.className = 'numero-row';
        div.style.cssText = 'display:flex; gap:8px; margin-bottom:8px; align-items:center;';
        div.innerHTML = `
            <input type="text" name="numeros[]"
                   class="lam-input"
                   placeholder="+221 77 000 00 00"
                   style="max-width:280px;">
            <button type="button" class="btn-lam-danger" style="padding:8px 12px;" onclick="this.closest('.numero-row').remove()">
                <i class="bi bi-dash-lg"></i>
            </button>
        `;
        wrap.appendChild(div);
        div.querySelector('input').focus();
    }

    //  Modal statut
    let modalStatutUrl = '';
    const bsModal = new bootstrap.Modal(document.getElementById('modalStatut'));

    function ouvrirModalStatut(url, statutActuel, nom) {
        modalStatutUrl = url;
        document.getElementById('modalCampagneNom').textContent = nom;

        const select = document.getElementById('selectNouveauStatut');
        Array.from(select.options).forEach(opt => {
            opt.disabled = false;
            opt.text = opt.text.replace(' (actuel)', '');
        });
        const current = select.querySelector(`option[value="${statutActuel}"]`);
        if (current) { current.disabled = true; current.text += ' (actuel)'; }
        select.value = statutActuel;
        bsModal.show();
    }

    async function confirmerStatut() {
        const statut = document.getElementById('selectNouveauStatut').value;
        const btn    = document.getElementById('btnConfirmerStatut');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> En cours…';

        try {
            const res  = await fetch(modalStatutUrl, {
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
                // Met à jour le badge dans le header sans rechargement
                document.querySelectorAll('.badge-statut').forEach(b => {
                    b.className = 'badge-statut badge-' + data.statut.toLowerCase();
                    b.textContent = data.label;
                });
                afficherFlash('Statut mis à jour : ' + data.label, 'success');
            } else {
                afficherFlash(data.erreur || 'Erreur lors du changement.', 'error');
            }
        } catch(e) {
            afficherFlash('Erreur réseau.', 'error');
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
        setTimeout(() => { div.style.opacity = '0'; div.style.transition = 'opacity .4s'; setTimeout(() => div.remove(), 400); }, 4000);
    }

    //  Auto-ouvre panel si erreur de validation
    @if($errors->has('numeros') || $errors->has('numeros.*'))
        document.getElementById('panelAjout').style.display = 'block';
    @endif
    @if($errors->has('fichier_csv'))
        document.getElementById('panelCsv').style.display = 'block';
    @endif
</script>
@endpush
