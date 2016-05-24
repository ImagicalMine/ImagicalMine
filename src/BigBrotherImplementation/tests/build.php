<?php
if(PHP_VERSION_ID < 70000){
    echo "You must use PHP 7 (or higher) to run this script.";
    exit(7);
}
function info(string $message, int $prefix = 0){
    $tag = ["Info", "Warning", "Error"];
    if(!isset($tag[$prefix])){
        $prefix = 1;
    }
    echo("\n[" . $tag[$prefix] . "] " . $message);
}
function do_command(string $command): bool{
    exec($command, $output, $status);
    return $status < 1;
}
function get_base(string $string): string{
    $parts = explode("/", $string);
    return $parts[1];
}
function validEnv(string $var){
    if(!getenv($var) || getenv($var) === null || strlen(getenv($var)) < 1){
        return null;
    }
    return getenv($var);
}
if(getenv("TRAVIS_PULL_REQUEST") !== "false"){
    info("Pull Request detected! Quitting...");
    exit(0);
}
$repo = validEnv("DEPLOY_REPO") ?? getenv("TRAVIS_REPO_SLUG");
$branch = validEnv("DEPLOY_REPO") ?? "travis-build";
$token = validEnv("DEPLOY_TOKEN") ?? false;
# Mess with Build tags
$name_tags = [
    "@number" => "TRAVIS_BUILD_NUMBER",
    "@commit" => "TRAVIS_COMMIT"
];
$build_name = get_base(validEnv("BUILD_NAME") ?? $repo);
foreach($name_tags as $k => $v){
    if(!empty(getenv($v))){
        str_replace($k, $v, $build_name);
    }
}
if(substr($build_name, -5, 5) !== ".phar"){
    $build_name .= ".phar";
}
# Get back to workflow...
if(!$token){
    info("No \"Token\" provided, \"Build\" will not be deployed", 1);
}else{
    info("Build will deploy to repo: " . $repo . ", branch: " . $branch . ". Unless token is invalid...");
}
info("Preparing Build environment...");
@mkdir("build");
# Move files to build
$files = ["resources", "src", "LICENSE", "plugin.yml", "README.md"];
foreach($files as $f){
    if(is_dir($f) or file_exists($f)){
        do_command("mv $f build/$f");
    }
}
# Download DevTools to build the PHAR
if(!do_command("curl -sL https://github.com/PocketMine/DevTools/releases/download/v1.11.0/DevTools_v1.11.0.phar -o DevTools.phar")){
    info("Couldn't download DevTools. We sorry...", 2);
    exit(1);
}
# Build...
if(!do_command("php -dphar.readonly=0 DevTools.phar --make build --out " . $build_name) && !file_exists($build_name)){
    info("Something went wrong while Building. Sorry! :(", 2);
    exit(1);
}
info("PHAR successfully built!");
if($token !== false){
    info("Deploying...");
    foreach($files as $f){
        if(is_dir($f) or file_exists($f)){
            do_command("mv build/$f $f");
        }
    }
    $git = [
        #"mv " . $build_name . " ../" . $build_name,
        "git remote set-url origin https://" . $token . "@github.com/" . $repo . ".git",
        "git fetch --all",
        "git pull --all",
        "git config user.name \"TravisBuilder (By @iksaku)\"",
        "git config user.email \"iksaku@me.com\"",
        "git config push.default simple",
        "git checkout -b " . $branch,
        "rm -rf * ($build_name)",
        "git rm -rf *",
        #"mv ../" . $build_name . " " . $build_name,
        "git add -A",
        "git commit -m \"(" . getenv("TRAVIS_BUILD_NUMBER") . ") New Build! Revision: " . getenv("TRAVIS_COMMIT") . "\"",
        "git push",
    ];
    foreach($git as $cmd){
        if(!do_command($cmd)){
            info("Something went wrong while deploying. Is your Token/Information still valid?", 2);
            exit(1);
        }
    }
    info("Successfully Deployed build. Enjoy!");
}
