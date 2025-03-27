<?php

/**
* Copyright since 2024 Firstruner and Contributors
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
* Please refer to https:*firstruner.fr/ or contact Firstruner for more information.
*
* @author    Firstruner and Contributors <contact@firstruner.fr>
* @copyright Since 2024 Firstruner and Contributors
* @license   Proprietary
* @version 2.0.0
*/

namespace System\Data\Blockchain;

use Firstruner\Cryptography\EncryptDecryptModule;

final class Blockchain
{
    private $BlockSep = "[_BLOCK_SEP_]";
    private $cert = null;

    public $Chain = [];
    public $MiningWithGuid = false;
    public $autoMining = false;

    public $Control = null;
    private $Signature = null;
    private $BC_Guid;

    public $Mined;

    public function __construct(
        $initializingData = "{}", 
        $sign = null, 
        $controlFormat = null, 
        $eventManagement = false, 
        $loadingMode = 'Ignore')
    {
        $this->BC_Guid = uniqid('', true);

        if ($loadingMode == 'Create') {
            $this->CreateCert();
            $loadingMode = 'Load';
        }

        if ($loadingMode == 'Load') {
            $this->loadCert();
        }

        $this->Signature = $sign;
        $this->Control = $controlFormat;

        $this->InitializeChain();
        $this->AddGenesisBlock(($initializingData == "{}" ? "{" . $this->BC_Guid . "}" : $initializingData));

        if ($eventManagement) {
            // Attach event handlers (Example: using closures in PHP)
            // Certificate_Expired, Certificate_NotFind, etc.
        }
    }

    private function CreateCert()
    {
        // Implement certificate creation logic
    }

    private function loadCert()
    {
        // Implement certificate loading logic
    }

    private function toOutput()
    {
        return $this->MiningWithGuid . ";" . $this->autoMining . ";" . ($this->Control ?? '') . ";" .
               ($this->Signature ?? '') . ";" . $this->BC_Guid;
    }

    private function InitializeChain()
    {
        $this->Chain = [];
    }

    private function CreateGenesisBlock($data)
    {
        $GenesisBlock = new Block(null, $data, $this->Signature, $this->Control);
        $GenesisBlock->SetAutoMining($this->autoMining);
        $GenesisBlock->Mine();
        return $GenesisBlock;
    }

    private function AddGenesisBlock($data)
    {
        $this->Chain[] = $this->CreateGenesisBlock($data);
    }

    public function Last()
    {
        return end($this->Chain);
    }

    public function AddBlock($data)
    {
        $latestBlock = $this->Last();

        if (!$latestBlock->isLock && !$this->autoMining) {
            throw new \Exception("Le block précédent n'est pas miné");
        }

        if (!$latestBlock->isLock) {
            $this->Mine();
        }

        $this->Chain[] = new Block($latestBlock->Hash, $data, $latestBlock->Index + 1, $this->Signature, $this->Control);
    }

    public function IsValid()
    {
        for ($i = 1; $i < count($this->Chain); $i++) {
            $currentBlock = $this->Chain[$i];
            $previousBlock = $this->Chain[$i - 1];

            if (($currentBlock->Hash != $currentBlock->ComputeHash()) || ($currentBlock->PreviousHash != $previousBlock->Hash)) {
                return false;
            }
        }

        return true;
    }

    public function Mine()
    {
        $last = $this->Last();
        $b = false;

        if ($last->isLock) {
            if ($this->Mined != null) {
                call_user_func($this->Mined, $last->Index, $last->Hash);
            }

            return;
        }

        $last->Mine();

        if ($this->Mined != null) {
            call_user_func($this->Mined, $last->Index, $last->Hash);
        }
    }

    public function Load($DirectoryName, $Encryption = false)
    {
        $EDModule = new EncryptDecryptModule(null);
        if ($Encryption && $this->cert == null) {
            $Encryption = false;
        }

        // Load and parse the blockchain data...
        // Implement the loading logic from files here

        return true;
    }

    public function Save($DirectoryName, $method = 'ByBlock', $size = 0, $Encryption = false, $backupMethod = 'Standard')
    {
        $EDModule = new EncryptDecryptModule(null);

        // Save the blockchain data to files...
        // Implement the saving logic here

        return true;
    }
}