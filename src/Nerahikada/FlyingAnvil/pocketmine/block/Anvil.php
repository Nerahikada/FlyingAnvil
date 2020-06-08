<?php

declare(strict_types=1);

namespace Nerahikada\FlyingAnvil\pocketmine\block;

use pocketmine\inventory\AnvilInventory;
use pocketmine\item\Item;
use pocketmine\Player;

class Anvil extends \pocketmine\block\Anvil{

	public function onActivate(Item $item, Player $player = null) : bool{
		if($player instanceof Player && ($window = $player->findWindow(AnvilInventory::class)) !== null){
			$player->removeWindow($window, true);
		}

		return parent::onActivate($item, $player);
	}
}