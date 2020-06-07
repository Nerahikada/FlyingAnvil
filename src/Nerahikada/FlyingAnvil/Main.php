<?php

declare(strict_types=1);

namespace Nerahikada\FlyingAnvil;

use Nerahikada\FlyingAnvil\pocketmine\entity\object\FallingBlock;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		Entity::registerEntity(FallingBlock::class, false, ['FallingBlock']);
	}
}