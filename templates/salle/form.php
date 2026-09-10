<section>
    <h1><?= $id === null ? 'Ajouter une salle' : 'Modifier une salle' ?></h1>
    <form method="post" action="<?= $id === null ? '/salles' : '/salles/' . (int) $id . '/edit' ?>">
        <label>Nom
            <input name="nom" value="<?= htmlspecialchars((string) ($data['nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            <?php foreach ($errors['nom'] ?? [] as $error): ?><small class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></small><?php endforeach; ?>
        </label>
        <label>Batiment
            <input name="batiment" value="<?= htmlspecialchars((string) ($data['batiment'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            <?php foreach ($errors['batiment'] ?? [] as $error): ?><small class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></small><?php endforeach; ?>
        </label>
        <label>Capacite
            <input type="number" name="capacite" value="<?= htmlspecialchars((string) ($data['capacite'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            <?php foreach ($errors['capacite'] ?? [] as $error): ?><small class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></small><?php endforeach; ?>
        </label>
        <label>Type
            <select name="type">
                <?php foreach (['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion'] as $type): ?>
                    <option value="<?= $type ?>" <?= ($data['type'] ?? '') === $type ? 'selected' : '' ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
            <?php foreach ($errors['type'] ?? [] as $error): ?><small class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></small><?php endforeach; ?>
        </label>
        <label class="checkbox"><input type="checkbox" name="active" value="1" <?= !isset($data['active']) || filter_var($data['active'], FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' ?>> Salle active</label>
        <?php foreach ($errors['active'] ?? [] as $error): ?><small class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></small><?php endforeach; ?>
        <button type="submit">Enregistrer</button>
    </form>
</section>
