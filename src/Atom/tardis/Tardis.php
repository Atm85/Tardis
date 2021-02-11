<?php


namespace Atom\tardis;


use Atom\tardis\commands\TardisCommand;
use Atom\tardis\managers\AddWarpCommand;
use Atom\tardis\managers\DelWarpCommand;
use Atom\tardis\managers\WarpCommand;
use Atom\tardis\managers\WarpManager;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use xenialdan\customui\elements\Button;
use xenialdan\customui\windows\SimpleForm;

use ReflectionProperty;

class Tardis extends PluginBase {

    public static $staticUI;

    /** @var Config */
    public $config;

    /** @var string */
    public $path;

    /** @var ReflectionProperty */
    public $reflectionProperty;

    /** @var ReflectionProperty */
    public $reflectionFolderName;

    /** @var WarpManager */
    private $warpManager;

    public function onEnable(): void {
        $this->getLogger()->info(TextFormat::colorize("&aTardis world manager by Atom#7489"));
        $this->path = $this->getServer()->getDataPath()."worlds/";

        $this->reflectionProperty = new ReflectionProperty(Level::class, "displayName");
        $this->reflectionFolderName = new ReflectionProperty(Level::class, "folderName");
        $this->reflectionProperty->setAccessible(true);
        $this->reflectionFolderName->setAccessible(true);

        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
        if ($this->config->get("config-version") != 1) {
            $this->getLogger()->error("You are using an outdated config version! please delete old config.yml...");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $commandMap = $this->getServer()->getCommandMap();
        $commandMap->register("tardis", new TardisCommand($this, "tardis", "Tardis plugin command", ["td", "tr", "twm"]));
        $commandMap->register("tardis", new AddWarpCommand($this, "addwarp", "Add a warp", ["aw"]));
        $commandMap->register("tardis", new DelWarpCommand($this, "delwarp", "deletes a warp", ["dw"]));
        $commandMap->register("tardis", new WarpCommand($this, "warp", "warp to a position"));

        if ($this->config->get("loadall-on-startup")) {
            $this->getLogger()->info(TextFormat::GREEN."Fixed ".$this->fixAll()." world name(s)!");
        }

        self::$staticUI = new SimpleForm("Worlds");
        $levels = $this->getServer()->getLevels();
        foreach ($levels as $level) {
            self::$staticUI->addButton(new Button($level->getName()));
        }

        self::$staticUI->setCallable(function (Player $player, string $data) {

            $level = $this->getServer()->getLevelByName($data);
            if ($level == null) {
                $player->sendMessage(TextFormat::RED . "An error occurred while attempting to teleport you to '$data'");
                return;
            }

            $player->teleport($level->getSafeSpawn());
        });

        $this->warpManager = new WarpManager($this);
    }

    public function loadAll(): void {
        foreach ($this->getAll() as $world) {
            $this->getServer()->loadLevel($world);
        }
    }

    public function getAll(): array {
        $folders = scandir($this->path);
        $worlds = [];
        for ($i = 2; $i<count($folders); $i++) {
            $worlds[] = $folders[$i];
        }

        return $worlds;
    }

    public function fixAll(): int {
        $fixedWorlds = 0;
        $this->loadAll();
        foreach ($this->getServer()->getLevels() as $level) {
            if ($this->canFix($level)) {
                $fixedWorlds++;
                $this->fixWorld($level);
            }
        }

        return $fixedWorlds;
    }

    public function fixWorld(Level $level): bool {
        if ($this->canFix($level)) {
            $this->reflectionProperty->setValue($level, $level->getFolderName());
            $this->getLogger()->info("Preparing world ".'"'.$level->getName().'"');
            return true;
        }

        return false;
    }

    public function renameWorld(Level $level, string $newname): bool {
        if ($this->canRename($level, $newname)) {
            $this->getServer()->unloadLevel($level, true);
            rename($this->path.$level->getFolderName()."/", $this->path."$newname/");
            $this->reflectionFolderName->setValue($level, $newname);
            $this->getServer()->loadLevel($level->getFolderName());
            $this->fixWorld($level);
            return true;
        }

        return false;
    }

    public function canFix(Level $level):bool {
        return !($level->getName() === $level->getFolderName());
    }

    public function canRename(Level $level, string $name): bool {
        if (!file_exists($this->path."$name/")) {
            if ($level->getName() !== $name) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    // API
    public function getWarpManager() : WarpManager {
        return $this->warpManager;
    }

}

