<section>
    <div class="page-heading">
        <h1>Liste des salles</h1>
        <a class="button" href="/salles/create">Ajouter une salle</a>
    </div>
    <?php if ($salles === []): ?>
        <p>Aucune salle disponible.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Nom</th><th>Batiment</th><th>Capacite</th><th>Type</th><th>Etat</th></tr></thead>
            <tbody>
            <?php foreach ($salles as $salle): ?>
                <tr>
                    <td><a href="/salles/<?= (int) $salle->id ?>"><?= htmlspecialchars((string) $salle->nom, ENT_QUOTES, 'UTF-8') ?></a></td>
                    <td><?= htmlspecialchars((string) $salle->batiment, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int) $salle->capacite ?></td>
                    <td><?= htmlspecialchars((string) $salle->type, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $salle->active ? 'Active' : 'Inactive' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
