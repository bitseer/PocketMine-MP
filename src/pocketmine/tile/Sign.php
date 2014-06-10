<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace pocketmine\tile;

use pocketmine\level\format\Chunk;
use pocketmine\level\Level;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Int;
use pocketmine\nbt\tag\String;
use pocketmine\network\protocol\EntityDataPacket;
use pocketmine\Player;

class Sign extends Spawnable{

	public function __construct(Chunk $chunk, Compound $nbt){
		$nbt["id"] = Tile::SIGN;
		parent::__construct($chunk, $nbt);
	}

	public function setText($line1 = "", $line2 = "", $line3 = "", $line4 = ""){
		$this->namedtag->Text1 = $line1;
		$this->namedtag->Text2 = $line2;
		$this->namedtag->Text3 = $line3;
		$this->namedtag->Text4 = $line4;
		$this->spawnToAll();

		return true;
	}

	public function getText(){
		return array(
			$this->namedtag->Text1,
			$this->namedtag->Text2,
			$this->namedtag->Text3,
			$this->namedtag->Text4
		);
	}

	public function spawnTo(Player $player){
		if($this->closed){
			return false;
		}

		$nbt = new NBT(NBT::LITTLE_ENDIAN);
		$nbt->setData(new Compound("", array(
			new String("Text1", $this->namedtag->Text1),
			new String("Text2", $this->namedtag->Text2),
			new String("Text3", $this->namedtag->Text3),
			new String("Text4", $this->namedtag->Text4),
			new String("id", Tile::SIGN),
			new Int("x", (int) $this->x),
			new Int("y", (int) $this->y),
			new Int("z", (int) $this->z)
		)));
		$pk = new EntityDataPacket;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->namedtag = $nbt->write();
		$player->dataPacket($pk);

		return true;
	}

}