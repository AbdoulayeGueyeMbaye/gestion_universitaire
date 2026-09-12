<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

final class CreateReservationsTable
{
    public function up(Capsule $capsule): void
    {
        $schema = $capsule->schema();

        if ($schema->hasTable('reservations')) {
            return;
        }

        $schema->create('reservations', static function ($table): void {
            $table->id();
            $table->foreignId('salle_id')->constrained('salles')->cascadeOnDelete();
            $table->string('responsable', 120);
            $table->string('email', 255);
            $table->string('motif', 255);
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->string('statut', 20)->default('confirmee');
            $table->timestamps();
        });
    }

    public function down(Capsule $capsule): void
    {
        $capsule->schema()->dropIfExists('reservations');
    }
}
