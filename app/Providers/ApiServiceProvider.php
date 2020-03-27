<?php declare(strict_types=1);

namespace App\Providers;

use App\Services\ProductionService;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider {
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->bind('production_service', function ($app) {
			$url = config('services.api.services.productions.url');

			return new ProductionService([
				'base_uri' => $url,
				'headers' => ['User-Agent' => 'Reconnect/ProductionService' ]
			]);
		});
	}
}
