<section>
    <h1>Ajouter une reservation</h1>
    <?php if (!empty($errors['reservation'])): ?>
        <div class="error-box"><?php foreach ($errors['reservation'] as $error): ?><p><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></p><?php endforeach; ?></div>
    <?php endif; ?>
    <form method="post" action="/reservations">
        <label>Salle
            <select name="salle_id">
                <?php foreach ($salles as $salle): ?>
                    <option value="<?= (int) $salle->id ?>" <?= (string) ($data['salle_id'] ?? '') === (string) $salle->id ? 'selected' : '' ?>><?= htmlspecialchars((string) $salle->nom, ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
            <?php foreach ($errors['salle_id'] ?? [] as $error): ?><small class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></small><?php endforeach; ?>
        </label>
        <label>Responsable
            <input name="responsable" value="<?= htmlspecialchars((string) ($data['responsable'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            <?php foreach ($errors['responsable'] ?? [] as $error): ?><small class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></small><?php endforeach; ?>
        </label>
        <label>Email
            <input type="email" name="email" value="<?= htmlspecialchars((string) ($data['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            <?php foreach ($errors['email'] ?? [] as $error): ?><small class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></small><?php endforeach; ?>
        </label>
        <label>Motif
            <textarea name="motif"><?= htmlspecialchars((string) ($data['motif'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
            <?php foreach ($errors['motif'] ?? [] as $error): ?><small class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></small><?php endforeach; ?>
        </label>
        <label>Debut
            <input type="datetime-local" name="date_debut" value="<?= htmlspecialchars(str_replace(' ', 'T', (string) ($data['date_debut'] ?? '')), ENT_QUOTES, 'UTF-8') ?>">
            <?php foreach ($errors['date_debut'] ?? [] as $error): ?><small class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></small><?php endforeach; ?>
        </label>
        <label>Fin
            <input type="datetime-local" name="date_fin" value="<?= htmlspecialchars(str_replace(' ', 'T', (string) ($data['date_fin'] ?? '')), ENT_QUOTES, 'UTF-8') ?>">
            <?php foreach ($errors['date_fin'] ?? [] as $error): ?><small class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></small><?php endforeach; ?>
        </label>
        <button type="submit">Enregistrer</button>
    </form>
</section>
