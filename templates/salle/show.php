<section>
    <h1><?= htmlspecialchars((string) $salle->nom, ENT_QUOTES, 'UTF-8') ?></h1>
    <dl>
        <dt>Batiment</dt><dd><?= htmlspecialchars((string) $salle->batiment, ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>Capacite</dt><dd><?= (int) $salle->capacite ?> places</dd>
        <dt>Type</dt><dd><?= htmlspecialchars((string) $salle->type, ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>Etat</dt><dd><?= $salle->active ? 'Active' : 'Inactive' ?></dd>
    </dl>
    <a class="button" href="/salles/<?= (int) $salle->id ?>/edit">Modifier</a>
</section>
