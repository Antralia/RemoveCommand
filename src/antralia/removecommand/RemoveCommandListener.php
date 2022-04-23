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

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;

class RemoveCommandListener implements Listener
{

    /**
     * @var RemoveCommandPlugin
     */
    private RemoveCommandPlugin $plugin;

    /**
     * @param RemoveCommandPlugin $plugin
     */
    public function __construct(RemoveCommandPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerCommandPreprocessEvent $event
     * @return void
     */
    public function handlePlayerCommandPreprocess(PlayerCommandPreprocessEvent $event): void
    {
        $player = $event->getPlayer();

        $message = $event->getMessage();
        $explodedMessage = explode(" ", $message);

        $command = strtolower(substr(array_shift($explodedMessage), 1));
        $commands = $this->plugin->getConfig()->getNested("commands");

        if (in_array($command, $commands) && $message[0] === "/") {
            $event->cancel();
            $player->sendMessage(str_replace(
                "{COMMAND}",
                $command,
                $this->plugin->getConfig()->getNested("blocked-message")
            ));
        }
    }
}