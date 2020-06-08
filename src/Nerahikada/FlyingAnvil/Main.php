<?php

declare(strict_types=1);

namespace Nerahikada\FlyingAnvil;

use Nerahikada\FlyingAnvil\pocketmine\entity\object\FlyingBlock;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		Entity::registerEntity(FlyingBlock::class, false, ['FlyingBlock']);
	}
}