@echo off
TITLE ImagicalMine server software for Minecraft: Pocket Edition
cd /d %~dp0

if exist bin\php\php.exe (
	set PHPRC=""
	set PHP_BINARY=bin\php\php.exe
) else (
	set PHP_BINARY=php
)

if exist ImagicalMine.phar (
	set POCKETMINE_FILE=ImagicalMine.phar
) else (
	if exist src\pocketmine\PocketMine.php (
		set POCKETMINE_FILE=src\pocketmine\PocketMine.php
	) else (
		echo "Couldn't find a valid ImagicalMine installation. If you have recently upgraded, ensure that you have renamed PocketMine-MP.phar to ImagicalMine.phar"
		pause
		exit 1
	)
)
