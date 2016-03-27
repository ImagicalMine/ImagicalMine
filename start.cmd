@echo off
TITLE ImagicalMine - a third-party build of PocketMine-MP and server software for Minecraft: Pocket Edition
cd /d %~dp0

if exist bin\php\php.exe (
	set PHPRC=""
	set PHP_BINARY=bin\php\php.exe
) else (
	set PHP_BINARY=php
)

if exist ImagicalMine.phar (
	set IMAGICALMINE_FILE=ImagicalMine.phar
) else (
	if exist PocketMine-MP.phar (
		set IMAGICALMINE_FILE=PocketMine-MP.phar
) else (
	if exist src\pocketmine\PocketMine.php (
		set IMAGICALMINE_FILE=src\pocketmine\PocketMine.php
	) else (
		echo "error> There was an error in starting ImagicalMine. Check that this is either a file named ImagicalMine.phar or PocketMine-MP.phar or a src folder, or try reinstalling ImagicalMine with instructions at imagicalmine.net."
		pause
		exit 7
	)
)
if exist bin\mintty.exe (
	start "" bin\mintty.exe -o Columns=88 -o Rows=32 -o AllowBlinking=0 -o FontQuality=3 -o Font="DejaVu Sans Mono" -o FontHeight=10 -o CursorType=0 -o CursorBlinks=1 -h error -t "ImagicalMine" -i bin/pocketmine.ico -w max %PHP_BINARY% %IMAGICALMINE_FILE% --enable-ansi %*
) else (
	%PHP_BINARY% -c bin\php %IMAGICALMINE_FILE% %*
)
