<section>
    <div class="page-heading">
        <h1>Reservations</h1>
        <a class="button" href="/reservations/create">Ajouter une reservation</a>
    </div>
    <?php if ($reservations === []): ?>
        <p>Aucune reservation.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Salle</th><th>Responsable</th><th>Debut</th><th>Fin</th><th>Statut</th></tr></thead>
            <tbody>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><a href="/reservations/<?= (int) $reservation->id ?>">Salle #<?= (int) $reservation->salle_id ?></a></td>
                    <td><?= htmlspecialchars((string) $reservation->responsable, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $reservation->date_debut, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $reservation->date_fin, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $reservation->statut, ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
