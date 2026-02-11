<?php

/**
 * Copyright 2024-2026 Firstruner and Contributors
 * Firstruner is an Registered Trademark & Property of Christophe BOULAS
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Freemium License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@firstruner.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit, reproduce ou modify this file.
 * Please refer to https://firstruner.fr/ or contact Firstruner for more information.
 *
 * @author    Firstruner and Contributors <contact@firstruner.fr>
 * @copyright 2024-2026 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace System\Data\Servers;

use Exception;
use System\Forms\Messages;
use System\Data\RequestType;
use System\Default\_boolean;
use System\Forms\MessageType;
use System\Diagnostics\ILogger;
use System\Default\_string;

abstract class AServer_Prosgres extends AServer
{
    private ?\mysqli $connexion = null;
    private string $cnxString = _string::EmptyString;
    protected ILogger $Logger;

    // private function __construct() {
    //     $this->Logger = new Internal_Logger(); // Initialize the Logger
    // }

    /*private function __construct(int $_connexionType = EConnexionType::Prod)
    {
        $cnxString = DBConnectRelation::Get($_connexionType);
        
        $this->OpenConnexion($cnxString);
    }*/

    protected function OpenConnexion(string $cnxString)
    {
        // $this->Logger = new Webbrowser_Logger();
        $this->cnxString = $cnxString;
        $cnxDatas = explode(";", $cnxString);

        //On établit la connexion 
        $this->connexion = new \mysqli(
            $cnxDatas[0],
            $cnxDatas[2],
            $cnxDatas[3],
            $cnxDatas[4],
            $cnxDatas[1]
        );

        //On vérifie la connexion
        if ($this->connexion->connect_errno) //connect_error {
        {
            Messages::LogMessage(
                $this->Logger,
                "Echec connexion - " . mysqli_connect_error()
            );

            return;
        }
        // var_dump($this->Logger);
        Messages::LogMessage(
            $this->Logger,
            "Connexion réussie"
        );
    }

    private function p_CloseConnexion()
    {
        try {
            if (!$this->connexion->close())
                Messages::LogMessage(
                    $this->Logger,
                    "Connexion fermée"
                );
        } catch (\Exception $ex) {
        }
    }

    private function ExeQuery(
        string $req,
        bool $keepAlive = false,
        int $RequestType = RequestType::Text,
        int $ResultType = ResultType::Fetch_Full
    ) {
        try {
            if (!isset($this->connexion->server_info)) {
                $this->OpenConnexion($this->cnxString);
            }

            $resultBrut = $this->connexion->query($req);

            if (gettype($resultBrut) == _boolean::ClassName)
                if ($resultBrut == false)
                    Messages::LogMessage(
                        $this->Logger,
                        "Error in executing query",
                        MessageType::Error,
                        true
                    );

            switch ($ResultType) {
                case ResultType::Fetch_Partial_Assoc:
                    return $resultBrut->fetch_assoc();
                case ResultType::Fetch_Full:
                    return $resultBrut->fetch_all(MYSQLI_ASSOC);
                case ResultType::CCollection_Array:
                    return $this->ConvertIntoDataAdapterFromFullArray($resultBrut->fetch_all(MYSQLI_ASSOC));
                case ResultType::CCollection_Dynamical:
                    return $this->ConvertIntoDynamicObjectFromFullArray($resultBrut->fetch_all(MYSQLI_ASSOC));
                case ResultType::Fetch_ToBool:
                    return $resultBrut;
                default:
                case ResultType::Fetch_Partial_Array:
                    return $resultBrut->fetch_array();
            }
        } catch (Exception $e) {
            Messages::LogMessage(
                $this->Logger,
                $e->GetMessage()
            );
        } finally {
            if (!$keepAlive)
                $this->CloseConnexion();
        }
    }

    public function CloseConnexion()
    {
        $this->p_CloseConnexion();
    }

    public function GetDatas(
        string $req,
        bool $keepAlive = false,
        int $RequestType = RequestType::Text,
        int $ResultType = ResultType::Fetch_Full
    ) {
        try {
            return $this->ExeQuery($req, $keepAlive, $RequestType, $ResultType);
        } catch (Exception $e) {
            Messages::LogMessage(
                $this->Logger,
                $e->GetMessage()
            );
        }
    }

    public function SetDatas(string $req, bool $keepAlive = false, int $RequestType = RequestType::Text)
    {
        try {
            $backValue = $this->ExeQuery($req, false, $keepAlive, ResultType::Fetch_ToBool);
            return $backValue;
        } catch (Exception $e) {
            Messages::LogMessage(
                $this->Logger,
                $e->GetMessage()
            );
            return false;
        }
    }

    // WARN : Get et Set DATAS, se connecte puis se déconnecte du serveur si KeepAlive = False
}
