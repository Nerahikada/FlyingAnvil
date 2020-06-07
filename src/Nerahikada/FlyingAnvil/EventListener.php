<?php

declare(strict_types=1);

namespace Nerahikada\FlyingAnvil;

use Nerahikada\FlyingAnvil\controller\FlyingAnvil;
use Nerahikada\FlyingAnvil\pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

class EventListener implements Listener{

	/** @var Main */
	private $plugin;

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}

	public function onPlayerCreation(PlayerCreationEvent $event) : void{
		$event->setBaseClass(Player::class);
		$event->setPlayerClass(Player::class);
	}

	public function onDataPacketReceive(DataPacketReceiveEvent $event) : void{
		FlyingAnvil::handleAnvilInventoryTransaction($event->getPlayer(), $event->getPacket());
	}
}