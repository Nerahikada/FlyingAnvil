<?php

declare(strict_types=1);

namespace Nerahikada\FlyingAnvil\pocketmine;

use pocketmine\inventory\Inventory;

class Player extends \pocketmine\Player{

	/**
	 * Dylan said "the only legitimate use for overriding player class right now is to override functions in the player class itself".
	 * This is wrong use, but there's no other way to do.
	 * Message link: https://discordapp.com/channels/373199722573201408/373214753147060235/678525593238896645
	 * Invite link: https://discord.gg/3tPjuGr
	 */
	public function findWindow(string $expectedClass) : ?Inventory{
		foreach($this->windowIndex as $window){
			if($window instanceof $expectedClass){
				return $window;
			}
		}

		return null;
	}
}