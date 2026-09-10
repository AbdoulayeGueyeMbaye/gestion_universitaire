<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Capsule\Manager as Capsule;

final class Application
{
	public function __construct(private Capsule $database)
	{
	}

	public function run(array $arguments): int
	{
		$command = $arguments[1] ?? '';

		switch ($command) {
			case 'migrate:up':
				return $this->migrate('up');
			case 'migrate:down':
				return $this->migrate('down');
			default:
				fwrite(STDERR, "Commande inconnue. Utilisation : migrate:up|migrate:down\n");

				return 1;
		}
	}

	private function migrate(string $direction): int
	{
		try {
			$this->database->getConnection()->getPdo();

			require dirname(__DIR__) . '/database/migrations/001_create_salles_table.php';
			require dirname(__DIR__) . '/database/migrations/002_create_reservations_table.php';

			$migrations = [
				new \CreateSallesTable(),
				new \CreateReservationsTable(),
			];

			if ($direction === 'down') {
				$migrations = array_reverse($migrations);
			}

			foreach ($migrations as $migration) {
				$migration->{$direction}($this->database);
			}

			fwrite(STDOUT, "Migration terminee.\n");

			return 0;
		} catch (\Throwable $exception) {
			fwrite(STDERR, "Echec de la connexion ou de la migration : {$exception->getMessage()}\n");

			return 1;
		}
	}
}
