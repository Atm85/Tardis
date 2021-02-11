<?php

namespace Atom\tardis\managers;

use Atom\tardis\Tardis;
use pocketmine\level\Position;
use pocketmine\utils\Config;

class WarpManager {

    /** @var Tardis */
    private $plugin;

    /** @var Warp[]  */
    private $warps = [];

    /** @var Config */
    private $datafile;

    public function __construct(Tardis $plugin) {

        $this->plugin = $plugin;

        // initialize warps from file
        $this->datafile = new Config($plugin->getDataFolder()."warps.json", Config::JSON);
        foreach ($this->datafile->getAll() as $name => $values) {

            $level = $plugin->getServer()->getLevelByName($values[3]);
            if ($level !== null) {
                $position = new Position($values[0], $values[1], $values[2], $level);
                $this->add(new Warp($name, $position));
            }
        }
    }

    public function saveWarp(Warp $warp) : bool {

        if (!isset($this->warps[$warp->getName()])) {
            $position = [$warp->getPosition()->getFloorX(), $warp->getPosition()->getFloorY(), $warp->getPosition()->getFloorZ(), $warp->getPosition()->getLevel()->getName()];
            $this->datafile->setNested($warp->getName(), $position);
            $this->datafile->save();
            $this->warps[$warp->getName()] = $warp;
            return true;
        }

        return false;
    }

    /**
     * return false if failed to create the warp
     * @param Warp $warp
     * @return bool
     */
    public function add(Warp $warp) : bool {
        if (!isset($this->warps[$warp->getName()])) {
            $this->warps[$warp->getName()] = $warp;
            return true;
        }

        return false;
    }

    /**
     * return false if failed to delete the warp
     *
     * @param Warp $warp
     * @return bool
     */
    public function delete(Warp $warp) : bool {
        if (isset($this->warps[$warp->getName()])) {

            $data = $this->datafile->getAll();
            unset($data[$warp->getName()]);

            $this->datafile->setAll($data);
            $this->datafile->save();

            unset($this->warps[$warp->getName()]);
            return true;
        }

        return false;
    }

    /**
     * @return Warp[]
     */
    public function getAll() : array {
        return $this->warps;
    }

    public function getWarp(string $name) : ?Warp {
        foreach ($this->getAll() as $warp) {
            if (strtolower($warp->getName()) == strtolower($name)) return $warp;
        }

        return null;
    }
}
