<?php
namespace Olifant\Kernel;

use Closure;
use Olifant\App;
use Cron\CronExpression;

class JobManager
{
	private $queue = [];
	private $ignored = [];

	public function add($name, $period, Closure $call)
	{
		if (!$name) {
			throw new KernelException();
		}

		if (isset($this->queue[$name])) {
			throw new KernelException();
		}


		if(!CronExpression::isValidExpression($period)) {
			throw new KernelException();
		}

		$this->queue[$name] = [
			'period' => $period,
			'call'   => $call
		];
	}

	public function exec($name)
	{
		if (isset($this->queue[$name])) {
            list($period, $call) = array_values($this->queue[$name]);
			App::make($call);
		}
	}

	public function on($name)
	{
		$this->ignored = array_unique(
			array_merge($this->ignored, (array) $name)
		);
	}

	public function off($name)
	{
		$this->ignored = array_diff($this->ignored, (array) $name);
	}

	public function getExpired($currentTime = 'now')
	{
		$queue = $this->queue;
		if ($this->ignored) {
			$ignored = $this->ignored;

			$queue = array_filter($queue, function ($item) use ($ignored) {
				return !in_array($item, $ignored, true);
			}, ARRAY_FILTER_USE_KEY);
		}

		$expired = array_filter($queue, function($item) use ($currentTime) {
			return CronExpression::factory($item['period'])->isDue($currentTime);
		});

		return array_keys($expired);
	}
}