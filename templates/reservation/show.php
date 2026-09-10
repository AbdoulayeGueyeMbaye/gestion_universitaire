<section>
    <h1>Reservation #<?= (int) $reservation->id ?></h1>
    <dl>
        <dt>Salle</dt><dd>#<?= (int) $reservation->salle_id ?></dd>
        <dt>Responsable</dt><dd><?= htmlspecialchars((string) $reservation->responsable, ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>Email</dt><dd><?= htmlspecialchars((string) $reservation->email, ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>Motif</dt><dd><?= htmlspecialchars((string) $reservation->motif, ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>Debut</dt><dd><?= htmlspecialchars((string) $reservation->date_debut, ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>Fin</dt><dd><?= htmlspecialchars((string) $reservation->date_fin, ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>Statut</dt><dd><?= htmlspecialchars((string) $reservation->statut, ENT_QUOTES, 'UTF-8') ?></dd>
    </dl>
    <?php if ($reservation->statut === 'confirmee'): ?>
        <form method="post" action="/reservations/<?= (int) $reservation->id ?>/cancel"><button type="submit">Annuler</button></form>
    <?php endif; ?>
</section>
