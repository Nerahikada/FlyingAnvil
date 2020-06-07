<?php

declare(strict_types=1);

namespace Nerahikada\FlyingAnvil\pocketmine\network\mcpe\protocol\types;

use pocketmine\inventory\AnvilInventory;
use pocketmine\inventory\transaction\action\InventoryAction;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\Player;

class NetworkInventoryAction extends \pocketmine\network\mcpe\protocol\types\NetworkInventoryAction{

	public function createInventoryAction(Player $player) : ?InventoryAction{
		try{
			return parent::createInventoryAction($player);
		}catch(\UnexpectedValueException $e){
			switch($this->sourceType){
				case self::SOURCE_CONTAINER:
					$window = $player->findWindow(AnvilInventory::class);
					if($window !== null){
						return new SlotChangeAction($window, $this->inventorySlot - 1, $this->oldItem, $this->newItem);
					}
					break;
			}
			throw $e;
		}
	}
}