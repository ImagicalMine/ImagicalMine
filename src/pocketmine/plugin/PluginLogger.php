<?php
/**
 * src/pocketmine/plugin/PluginLogger.php
 *
 * @package default
 */


/*
 *
 *  _                       _           _ __  __ _
 * (_)                     (_)         | |  \/  (_)
 *  _ _ __ ___   __ _  __ _ _  ___ __ _| | \  / |_ _ __   ___
 * | | '_ ` _ \ / _` |/ _` | |/ __/ _` | | |\/| | | '_ \ / _ \
 * | | | | | | | (_| | (_| | | (_| (_| | | |  | | | | | |  __/
 * |_|_| |_| |_|\__,_|\__, |_|\___\__,_|_|_|  |_|_|_| |_|\___|
 *                     __/ |
 *                    |___/
 *
 * This program is a third party build by ImagicalMine.
 *
 * PocketMine is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ImagicalMine Team
 * @link http://forums.imagicalcorp.ml/
 *
 *
*/

namespace pocketmine\plugin;

use LogLevel;
use pocketmine\Server;

class PluginLogger implements \AttachableLogger
{

    private $pluginName;

    /** @var \LoggerAttachment[] */
    private $attachments = [];

    /**
     *
     * @param LoggerAttachment $attachment
     */
    public function addAttachment(\LoggerAttachment $attachment)
    {
        $this->attachments[spl_object_hash($attachment)] = $attachment;
    }


    /**
     *
     * @param LoggerAttachment $attachment
     */
    public function removeAttachment(\LoggerAttachment $attachment)
    {
        unset($this->attachments[spl_object_hash($attachment)]);
    }


    /**
     *
     */
    public function removeAttachments()
    {
        $this->attachments = [];
    }


    /**
     *
     * @return unknown
     */
    public function getAttachments()
    {
        return $this->attachments;
    }


    /**
     *
     * @param Plugin  $context
     */
    public function __construct(Plugin $context)
    {
        $prefix = $context->getDescription()->getPrefix();
        $this->pluginName = $prefix != null ? "[$prefix] " : "[" . $context->getDescription()->getName() . "] ";
    }


    /**
     *
     * @param unknown $message
     */
    public function emergency($message)
    {
        $this->log(LogLevel::EMERGENCY, $message);
    }


    /**
     *
     * @param unknown $message
     */
    public function alert($message)
    {
        $this->log(LogLevel::ALERT, $message);
    }


    /**
     *
     * @param unknown $message
     */
    public function critical($message)
    {
        $this->log(LogLevel::CRITICAL, $message);
    }


    /**
     *
     * @param unknown $message
     */
    public function error($message)
    {
        $this->log(LogLevel::ERROR, $message);
    }


    /**
     *
     * @param unknown $message
     */
    public function warning($message)
    {
        $this->log(LogLevel::WARNING, $message);
    }


    /**
     *
     * @param unknown $message
     */
    public function notice($message)
    {
        $this->log(LogLevel::NOTICE, $message);
    }


    /**
     *
     * @param unknown $message
     */
    public function info($message)
    {
        $this->log(LogLevel::INFO, $message);
    }


    /**
     *
     * @param unknown $message
     */
    public function debug($message)
    {
        $this->log(LogLevel::DEBUG, $message);
    }


    /**
     *
     * @param Throwable $e
     * @param unknown   $trace (optional)
     */
    public function logException(\Throwable $e, $trace = null)
    {
        Server::getInstance()->getLogger()->logException($e, $trace);
    }



    /**
     *
     * @param unknown $level
     * @param unknown $message
     */
    public function log($level, $message)
    {
        Server::getInstance()->getLogger()->log($level, $this->pluginName . $message);
        foreach ($this->attachments as $attachment) {
            $attachment->log($level, $message);
        }
    }
}
