<?php

namespace Marcoboom\MediaManager\Drivers\Responses;

use Marcoboom\MediaManager\Imagestyle;
use Marcoboom\MediaManager\Contracts\ImagestyleAction;
use Cache;

class SaveToCache implements ImagestyleAction
{
	protected $config;
	protected $style;

	public function __construct(Imagestyle $style, array $config=[])
	{
		$this->style = $style;
		$this->config = $config;
	}

	public function handle()
	{
		Cache::put($this->style->getCacheKey(), (string)$this->style->img->encode(), 10000);
	}

}
