<?php

header("Content-Type: text/plain");
$input = json_decode(file_get_contents("php://input"), true);

if(getallheaders()["X-GitHub-Event"] === "push"){
        $startTime = microtime(true);
/*      $archives = $input["repository"]["archive_url"];
        $url = str_replace(["{archive_format}", "{/ref}"], ["zipball", ""], $archives);
        $rawData = getURL($url);
        $zipPath = tempnam("", "zip");
        file_put_contents($zipPath, $rawData);
        $zip = new ZipArchive;
        $zip->open($zipPath);*/
        $path = "/var/www/html/ImagicalMine/releases/ImagicalMine.phar";
        @unlink($path);
        $phar = new Phar($path);
        $phar->setMetadata([
                "name" => "ImagicalMine",
                "version" => "#" . substr($input["after"], 0, 7),
                "api" => "1.13.0",
                "minecraft" => "0.13.0",
                "protocol" => 38,
                "creationDate" => time()
        ]);
        $phar->setStub('<?php define("pocketmine\\\\PATH", "phar://". __FILE__ ."/"); require_once("phar://". __FILE__ ."/src/pocketmine/PocketMine.php");  __HALT_COMPILER();');
        $phar->setSignatureAlgorithm(\Phar::SHA1);
        $phar->startBuffering();
        /*$cnt = 1;
        for($i = 0; $i < $zip->numFiles; $i++){
                $name = $zip->getNameIndex($i);
                if(strpos($name, "src/") === 27 and substr($name, -1) !== "/"){
                        $phar->addFromString($name = substr($name, 27), $buffer = $zip->getFromIndex($i));
                        echo "[" . (++$cnt) . "] Adding " . round(strlen($buffer) / 1024, 2) . " KB to /$name", PHP_EOL;
                }
        }*/
        chdir("/ImagicalMine");
        echo `git pull --no-edit --recurse-submodules`;
        //$phar->buildFromDirectory("/ImagicalMine");
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator("/ImagicalMine/src")) as $file){
                if(is_file($file) and strpos($file, ".git") === false){
                        $real = realpath($file);
                        $include = substr($real, strlen("/ImagicalMine"));
                        $phar->addFile($real, $include);
                        // echo "$real -> $include\n";
                }
        }
        $phar->stopBuffering();
        //$zip->close();
        //unlink($zipPath);
        echo "Phar created at $path in ", (microtime(true) - $startTime) * 1000, "ms";
}else{
        echo "Unsupported GitHub event!";
}

function getURL($page, $timeout = 0 , array $extraHeaders = []){
        $ch = curl_init($page);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(["User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:12.0) Gecko/20100101 Firefox/12.0 LegionPE"], $extraHeaders));
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, (int) $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, (int) $timeout);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
}
