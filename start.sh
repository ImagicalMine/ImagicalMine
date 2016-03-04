#!/bin/bash
DIR="$(cd -P "$( dirname "${BASH_SOURCE[0]}" )" && pwd)"
cd "$DIR"

DO_LOOP="no"

while getopts "p:f:l" OPTION 2> /dev/null; do
	case ${OPTION} in
		p)
			PHP_BINARY="$OPTARG"
			;;
		f)
			IMAGICALMINE_FILE="$OPTARG"
			;;
		l)
			DO_LOOP="yes"
			;;
		\?)
			break
			;;
	esac
done

if [ "$PHP_BINARY" == "" ]; then
	if [ -f ./bin/php7/bin/php ]; then
		export PHPRC=""
		PHP_BINARY="./bin/php7/bin/php"
	elif [ type php 2>/dev/null ]; then
		PHP_BINARY=$(type -p php)
	else
		echo "error> There was an error in starting the PHP binary. Check that you have a bin/php7/bin folder, or try reinstalling the PHP binary with instructions at imagicalmine.net."
		exit 7
	fi
fi

if [ "$IMAGICALMINE_FILE" == "" ]; then
	if [ -f ./ImagicalMine.phar ]; then
		IMAGICALMINE_FILE="./ImagicalMine.phar"
	elif [ -f ./PocketMine-MP.phar ]; then
		IMAGICALMINE_FILE="./PocketMine-MP.phar
	elif [ -f ./src/pocketmine/PocketMine.php ]; then
		IMAGICALMINE_FILE="./src/pocketmine/PocketMine.php"
	else
		echo "error> There was an error in starting ImagicalMine. Check that this is either a file named ImagicalMine.phar or PocketMine-MP.phar or a src folder, or try reinstalling ImagicalMine with instructions at imagicalmine.net."
		exit 7
	fi
fi

LOOPS=0

set +e
while [ "$LOOPS" -eq 0 ] || [ "$DO_LOOP" == "yes" ]; do
	if [ "$DO_LOOP" == "yes" ]; then
		"$PHP_BINARY" "$IMAGICALMINE_FILE" $@
	else
		exec "$PHP_BINARY" "$IMAGICALMINE_FILE" $@
	fi
	((LOOPS++))
done

if [ ${LOOPS} -gt 1 ]; then
	echo "Restarted $LOOPS times"
fi
