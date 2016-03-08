<?php

/*
 *
 *  _                       _           _ __  __ _
 * (_)                     (_)         | |  \/  (_)
 *  _ _ __ ___   __ _  __ _ _  ___ __ _| | \  / |_ _ __   ___
 * | | '_ ` _ \ / _` |/ _` | |/ __/ _` | | |\/| | | '_ \ / _ \
 * | | | | | | | (_| | (_| | | (_| (_| | | |  | | | | | |  __/
 * |_|_| |_| |_|\__,_|\__, |_|\___\__,_|_|_|  |_|_|_| |_|\___|
 *                     __/ |
 *                    |___/
 *
 * This program is a third party build by ImagicalMine.
 *
 * PocketMine is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ImagicalMine Team
 * @link http://forums.imagicalmine.net/
 *
 *
*/

namespace pocketmine\event\server;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;

/**
 * Called when the console runs a command, early in the process
 *
 * You don't want to use this except for a few cases like logging commands,
 * blocking commands on certain places, or applying modifiers.
 *
 * The message contains a slash at the start
 */
class ServerCommandEvent extends ServerEvent implements Cancellable{
	public static $handlerList = null;

	/** @var string */
	protected $command;

	/** @var CommandSender */
	protected $sender;

	/**
	 * @param CommandSender $sender
	 * @param string        $command
	 */
	public function __construct(CommandSender $sender, $command){
		$this->sender = $sender;
		$this->command = $command;
	}

	/**
	 * @return CommandSender
	 */
	public function getSender(){
		return $this->sender;
	}

	/**
	 * @return string
	 */
	public function getCommand(){
		return $this->command;
	}

	/**
	 * @param string $command
	 */
	public function setCommand($command){
		$this->command = $command;
	}

}