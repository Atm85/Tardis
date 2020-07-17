<?php


namespace Atom\tardis;


use Atom\tardis\commands\WorldCoreCommand;
use pocketmine\level\Level;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use ReflectionProperty;

class Tardis extends PluginBase {

    /** @var Config */
    public $config;

    /** @var string */
    public $path;

    /** @var ReflectionProperty */
    public $reflectionProperty;

    /** @var ReflectionProperty */
    public $reflectionFolderName;

    public function onEnable(): void {
        $this->getLogger()->info(TextFormat::colorize("&Tardis world manager by Atom#7489"));
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
        $commandMap->register("tardis", new WorldCoreCommand($this, "tardis", "Tardis plugin command", ["twm"]));

        if ($this->config->get("loadall-on-startup")) {
            $this->getLogger()->info(TextFormat::GREEN."Fixed ".$this->fixAll()." world name(s)!");
        }

    }

    public function loadAll(): void {
        $folders = scandir($this->path);
        for ($i = 2; $i<count($folders); $i++) {
            $this->getServer()->loadLevel($folders[$i]);
        }
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

}

