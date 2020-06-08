<?php

declare(strict_types=1);

namespace Nerahikada\FlyingAnvil\pocketmine\entity\object;

use Nerahikada\FlyingAnvil\utils\Math;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Explosion;
use pocketmine\level\particle\MobSpawnParticle;
use pocketmine\level\sound\AnvilFallSound;

class FlyingBlock extends \pocketmine\entity\object\FallingBlock{

	protected $gravity = -0.04;

	public function entityBaseTick(int $tickDiff = 1) : bool{
		if($this->closed){
			return false;
		}

		$hasUpdate = Entity::entityBaseTick($tickDiff);

		if($this->y >= 256 + 16 and $this->isAlive()){
			$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_VOID, 10);
			$this->attack($ev);
			$hasUpdate = true;
		}

		if(!$this->isFlaggedForDespawn()){
			if($this->getLevelNonNull()->getBlock($this->add(0, 1.0, 0), true, false)->getId() !== Block::AIR){
				$explosion = new Explosion($this, 1.5, $this);
				$explosion->explodeA();
				$explosion->explodeB();
			}
			$this->getLevelNonNull()->addParticle(
				new MobSpawnParticle(
					$this->add(Math::randomFloat(-0.8, 0.8), -0.5, Math::randomFloat(-0.8, 0.8))
				)
			);
			$this->getLevelNonNull()->addSound(new AnvilFallSound($this));
		}

		return $hasUpdate;
	}
}