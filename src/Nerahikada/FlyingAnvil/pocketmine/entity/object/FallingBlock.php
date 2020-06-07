<?php

declare(strict_types=1);

namespace Nerahikada\FlyingAnvil\pocketmine\entity\object;

use Nerahikada\FlyingAnvil\utils\Math;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Explosion;
use pocketmine\level\particle\MobSpawnParticle;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\IntTag;
use function get_class;

/**
 * できれば \pocketmine\entity\object\FallingBlock を継承したかったが、
 * parent::entityBaseTick() を呼び出さなければいけないので断念
 */
class FallingBlock extends Entity{
	public const NETWORK_ID = self::FALLING_BLOCK;

	public $width = 0.98;
	public $height = 0.98;

	protected $baseOffset = 0.49;

	protected $gravity = -0.04;
	protected $drag = 0.02;

	/** @var Block */
	protected $block;

	public $canCollide = false;

	protected function initEntity() : void{
		parent::initEntity();

		$blockId = 0;

		//TODO: 1.8+ save format
		if($this->namedtag->hasTag("TileID", IntTag::class)){
			$blockId = $this->namedtag->getInt("TileID");
		}elseif($this->namedtag->hasTag("Tile", ByteTag::class)){
			$blockId = $this->namedtag->getByte("Tile");
			$this->namedtag->removeTag("Tile");
		}

		if($blockId === 0){
			throw new \UnexpectedValueException("Invalid " . get_class($this) . " entity: block ID is 0 or missing");
		}

		$damage = $this->namedtag->getByte("Data", 0);

		$this->block = BlockFactory::get($blockId, $damage);

		$this->propertyManager->setInt(self::DATA_VARIANT, $this->block->getRuntimeId());
	}

	public function canCollideWith(Entity $entity) : bool{
		return false;
	}

	public function canBeMovedByCurrents() : bool{
		return false;
	}

	public function attack(EntityDamageEvent $source) : void{
		if($source->getCause() === EntityDamageEvent::CAUSE_VOID){
			parent::attack($source);
		}
	}

	public function entityBaseTick(int $tickDiff = 1) : bool{
		if($this->closed){
			return false;
		}

		$hasUpdate = parent::entityBaseTick($tickDiff);

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

	public function getBlock() : int{
		return $this->block->getId();
	}

	public function getDamage() : int{
		return $this->block->getDamage();
	}

	public function saveNBT() : void{
		parent::saveNBT();
		$this->namedtag->setInt("TileID", $this->block->getId(), true);
		$this->namedtag->setByte("Data", $this->block->getDamage());
	}
}