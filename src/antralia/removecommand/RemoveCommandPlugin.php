<?php

declare(strict_types=1);

/**
 *     _          _             _ _
 *    / \   _ __ | |_ _ __ __ _| (_) __ _
 *   / _ \ | '_ \| __| '__/ _` | | |/ _` |
 *  / ___ \| | | | |_| | | (_| | | | (_| |
 * /_/   \_\_| |_|\__|_|  \__,_|_|_|\__,_|
 *
 * @author Antralia (Lunarelly)
 * @link https://github.com/Antralia
 *
 */

namespace antralia\removecommand;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginException;

class RemoveCommandPlugin extends PluginBase
{

    /**
     * @return void
     */
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->processChecks();
    }

    /**
     * @return void
     */
    private function processChecks(): void
    {
        $commands = $this->getConfig()->getNested("commands");
        $mode = strtolower($this->getConfig()->getNested("mode"));

        if (!(is_array($commands))) {
            throw new PluginException(sprintf("Commands key must be an array, %s given", gettype($commands)));
        }

        if ($mode === "unregister") {
            $this->unregisterCommands();
        } elseif ($mode === "block") {
            $this->getServer()->getPluginManager()->registerEvents(new RemoveCommandListener($this), $this);
        } else {
            throw new PluginException(sprintf("Unsupported mode: %s", $mode));
        }
    }

    /**
     * @return void
     */
    private function unregisterCommands(): void
    {
        $commands = $this->getConfig()->getNested("commands");
        $commandMap = $this->getServer()->getCommandMap();

        foreach ($commands as $command) {
            $commandMap->unregister($commandMap->getCommand($command));
        }
    }
}