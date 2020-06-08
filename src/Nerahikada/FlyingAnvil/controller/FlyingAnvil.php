<?php

declare(strict_types=1);

namespace Nerahikada\FlyingAnvil\controller;

use Nerahikada\FlyingAnvil\pocketmine\network\mcpe\protocol\types\NetworkInventoryAction;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\entity\Entity;
use pocketmine\inventory\AnvilInventory;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;

class FlyingAnvil{

	public static function handleAnvilInventoryTransaction(Player $player, DataPacket $packet) : void{
		/** @var InventoryTransactionPacket $packet */
		if($packet::NETWORK_ID === ProtocolInfo::INVENTORY_TRANSACTION_PACKET){
			if(!$player->spawned || !$player->isAlive() || $player->isSpectator()){
				return;
			}

			$isAnvilResult = false;
			foreach($packet->actions as $key => $action){
				$action->write($packet);
				$packet->actions[$key] = $action = (new NetworkInventoryAction())->read($packet);

				if($action->sourceType === NetworkInventoryAction::SOURCE_TODO && $action->windowId === NetworkInventoryAction::SOURCE_TYPE_ANVIL_RESULT){
					$isAnvilResult = true;
				}
			}

			if($packet->isCraftingPart && $isAnvilResult){
				self::flyAnvil($player->findWindow(AnvilInventory::class)->getHolder());
			}
		}
	}

	private static function flyAnvil(Position $position) : void{
		$position->getLevel()->setBlock($position, BlockFactory::get(Block::AIR), true);

		$nbt = Entity::createBaseNBT($position->add(0.5, 0, 0.5));
		$nbt->setInt("TileID", Block::ANVIL);
		$nbt->setByte("Data", 0);

		$fall = Entity::createEntity("FlyingBlock", $position->getLevel(), $nbt);

		if($fall !== null){
			$fall->spawnToAll();
		}
	}
}