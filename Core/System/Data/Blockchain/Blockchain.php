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
* Please refer to https://firstruner.fr/ or contact Firstruner for more information.
*
* @author    Firstruner and Contributors <contact@firstruner.fr>
* @copyright Since 2024 Firstruner and Contributors
* @license   Proprietary
* @version 2.0.0
*/

namespace System\Data\Blockchain;

use Firstruner\Langages\Alphabetics;
use System\_String as System_String;
use System\Collections\CCollection;
use System\Console;
use System\Data\Blockchain\LoadingCert;
use System\Default\_array;
use System\Default\_boolean;
use System\Default\_string;
use System\Exceptions\ArgumentOutOfRangeException;
use System\Exceptions\NeedAdministratorAccesException;
use System\Exceptions\NotImplementedException;
use System\Exceptions\PrivateKeyUnavailableException;
use System\Guid;
use System\IO\AccessMode;
use System\IO\FileStream;
use System\IO\StreamReader;
use System\IO\StreamWriter;
use System\Security\Cryptography\Encryption;

class Blockchain
{
    /**
     *     Pattern attendu Mine_Ended(int blockID, string hash)
     */
    public ?\Closure $Mined = null;

    private string $blockSep = "[_BLOCK_SEP_]";
    private /*?X509Certificate2*/ $cert = null; // TODO : cert !!!
    private ?string $signature = null;
    private ?Guid $bC_Guid = null;

    public ?string $Control = null;
    public ?CCollection $Chain = null;
    public bool $MiningWithGuid = false;
    public bool $AutoMining = false;

    private ?string $provider = null;
    private int $storageProvider = null;

    /// <summary>
    /// Constructeur
    /// </summary>
    /// <param name="initializingData">Donnée d'initialisation</param>
    /// <param name="sign">Clé personnelle d'identification</param>
    /// <param name="controlFormat">Format dont le Hash doit correspondre
    /// B:# = Commence par - E:# = Fini par - C:# = Contient - Example : B:000 = Commence par 3 zéros</param>
    public function __construct(
        string $initializingData = "{}",
        ?string $sign = null,
        ?string $controlFormat = null,
        bool $eventManagement = false,
        int $loadingMode = LoadingCert::Ignore
        )
    {
        $this->bC_Guid = Guid::NewGuid();

        if ($loadingMode >= LoadingCert::Create)
        {
            CreateCert();
            $loadingMode = LoadingCert::Load;
        }

        if ($loadingMode == LoadingCert::Load)
            loadCert();

        $this->signature = $sign;
        $this->Control = $controlFormat;

        InitializeChain();
        AddGenesisBlock(($initializingData == "{}" ? "{" + BC_Guid.ToString() + "}" : $initializingData));

        if ($eventManagement)
        {
            // TODO : cert !!!
            // Firstruner.Tools.Cryptography.Tools.Certificate_Expired += ToolsOnCertificateExpired;
            // Firstruner.Tools.Cryptography.Tools.Certificate_NotFind += ToolsOnCertificateNotFind;
            // Firstruner.Tools.Cryptography.Tools.Certificate_OverCount += ToolsOnCertificateOverCount;
        }
    }

      
    private function toOutput() : string
    {
        return
                ($this->MiningWithGuid ? _boolean::TrueTextValue : _boolean::FalseTextValue) + ";"
                + ($this->AutoMining ? _boolean::TrueTextValue : _boolean::FalseTextValue) + ";" 
                + ($this->Control ?? _string::EmptyString) + ";"
                + ($this->signature ?? _string::EmptyString) + ";" 
                + $this->bC_Guid;
    }

    #region Events des certificats

    private function ToolsOnCertificateOverCount(int $count) : void
    {
        throw new \Exception("Il y a plus d'un certificat (" + $count + ")");
    }

    private function ToolsOnCertificateNotFind() : void
    {
        throw new \Exception("Certificat introuvable");
    }

    private function ToolsOnCertificateExpired(\DateTime $dateTime) : void
    {
        throw new \Exception("Le certificat a expiré");
    }

    #endregion

    private function InitializeChain() : void
    {
        $this->Chain = new CCollection(Block::class);
    }

    private function CreateGenesisBlock(string $data) : Block
    {
        $GenesisBlock = new Block(null, $data, Sign: Signature, ControlFormat: Control);
        $GenesisBlock->SetAutoMining($this->autoMining);

        $GenesisBlock->Mine();

        return $GenesisBlock;
    }

    private function AddGenesisBlock(string $data) : void
    {
        $this->Chain->Add($this->CreateGenesisBlock($data));
    }

    public function Last() : Block
    {
        return $this->Chain->Last();
    }

    public function AddBlock(string $data) : void
    {
        $latestBlock = $this->Last();

        if (!$latestBlock->IsLock() && !$this->AutoMining)
                throw new \Exception("Le block précédent n'est pas miné");

        if (!$latestBlock->IsLock())
                $this->Mine();

        $tempBlock = new Block(
                $latestBlock->Hash(),
                $data,
                $latestBlock->Index + 1,
                $this->signature,
                $this->Control);

        $tempBlock->SetMineIncludeGuid($this->MiningWithGuid);
        $tempBlock->SetAutoMining($this->AutoMining);
    }

    public function IsValid() : bool
    {
        for ($i = 1; $i < $this->Chain->count(); $i++)
        {
            $currentBlock = $this->ChainChain[$i];
            $previousBlock = $this->ChainChain[$i - 1];

            if (($currentBlock->Hash() != $currentBlock.ComputeHash()) ||
                ($currentBlock->GetPreviousHash() != $previousBlock->Hash()))
                return false;
        }

        return true;
    }

    public function Mine() : void
    {
        $last = $this->Last();
        $b = false;

        if ($last->IsLock())
        {
            if (isset($this->Mined))
                $this->Mined($last->Index, $last->Hash());

            return;
        }

        $last->Mine();

        if ($this->Mined != null)
        $this->Mined($last->Index, $last->Hash());
    }
    
    public function Load(int $storageProvider, string $provider, bool $encryption = false) : bool
    {
        if ($storageProvider != StorageProvider::File)
            throw new NotImplementedException();
       
        if ($encryption && ($cert == null))
            $encryption = false;

        $head1 = _string::EmptyString;
        $head2 = _string::EmptyString;

        $method = StorageMethod::ByBlock;
        $size = 0;

        // Lecture du Header
        $this->provider = $provider;
        $srheader = new StreamReader($provider + "\\fbc.header");

        $head1 = $srheader->ReadLine(); //((int)method).ToString("0") + ";" + size.ToString("0"));
        $head2 = $srheader->ReadLine(); //(Encryption ? EDModule.Encrypt(cert, toOutput()).Value : toOutput()));

        $srheader->Close();

        // Traitement du header
        $header = explode(';', $head1);
        $method = intval($header[0]);
        $size = intval($header[1]);

        try
        {
            // TODO : cert !!!
            Encryption::decrypt("$cert", $head2);
            //EDModule.Decrypt($cert, $head2);
        }
        catch (NeedAdministratorAccesException $eAdmin)
        {
            Console::WriteError("Accès administrateur nécessaire");
            return false;
        }
        catch (PrivateKeyUnavailableException $ePK)
        {
            Console::WriteError("Clé non disponible");
            return false;
        }

        // TODO : cert !!!
        //$header = ($encryption ? EDModule.Decrypt($cert, $head2) : $head2).Split(';');
        $header = ($encryption ? Encryption::decrypt("$cert", $head2) : $head2).Split(';');

        // Affectation des valeurs lues
        $this->MiningWithGuid = bool.Parse($header[0]);
        $this->AutoMining = bool.Parse($header[1]);
        $this->Control = ($header[2] == _string::EmptyString ? null : $header[2]);
        $this->signature = ($header[3] == _string::EmptyString ? null : $header[3]);
        $this->bC_Guid = new Guid($header[4]);

        switch ($method)
        {
            case StorageMethod::ByBlock:
            case StorageMethod::By5Block:
            case StorageMethod::By10Block:
            case StorageMethod::ByNbBlock:
                $this->Chain = new CCollection(type: Block::class);

                foreach (glob($this->Provider . "/*.fbc") as $file)
                {
                    $datasC = new CCollection(type::class);
                    $datasU = new CCollection(type::class);
                    $srLine = _string::EmptyString;

                    $sr = new StreamReader($file);
                    do
                    {
                        $srLine = sr->ReadLine();

                        if ($srLine->IsNull())
                            break;

                        $datasC->Add(
                            $srLine.Split(Alphabetics::HidedChar.ToCharArray()[0])[0]);
                    } while (!$srLine->IsNull());

                    $sr->Close();

                    foreach ($datasC as $data)
                        // TODO : cert !!!
                        //$datasU->Add(EDModule.Decrypt($cert, $data));
                        $datasU->Add(Encryption::decrypt("$cert", $data));

                    $final = _string::EmptyString;
                    $temp = null;

                    for ($b = 0; $b < $datasU->count(); $b++)
                    {
                        $end = strpos($datasU[$b] + (
                            ($b < ($datasU->count() - 1)) 
                                ? $datasU[$b + 1] 
                                : _string::EmptyString), $BlockSep);

                        if (isset($temp) || (($end < 0) && (!isset($temp))))
                        {
                            $final += $temp ?? $datasU[$b];
                            $temp = null;
                        }
                        else
                        {
                            $final +=
                                substr($datasU[$b] + (
                                    ($b < ($datasU->count() - 1))
                                        ? $datasU[$b + 1]
                                        : _string::EmptyString),
                                            0,
                                            $end);
                            
                            $this->Chain.Add(new Block($final));
                            $final = _string::EmptyString;
                            $temp =
                                substr($datasU[$b] + (
                                    ($b < ($datasU.Count - 1)) 
                                        ? $datasU[$b + 1] 
                                        : _string::EmptyString),
                                            $end + strlen($BlockSep));
                        }
                    }
                }
                return true;
            case StorageMethod::ByFileSize:
                return false;
            default:
                throw new ArgumentOutOfRangeException("method");
        }
    }

    public function Save(int $storageProvider, string $provider,
        int $method = StorageMethod::ByBlock, int $size = 0,
        bool $encryption = false, int $backupMethod = BackupMethod::Standard) : bool
    {
        // TODO : cert !!!
        // Firstruner.Tools.Cryptography.EncryptDecryptModule EDModule =
        //     new EncryptDecryptModule(null);

        if ($storageProvider != StorageProvider::File)
            throw new NotImplementedException();
        
        try
        {
            foreach (glob($provider) as $file)
                unlink($file);
        }
        catch (\Exception $e)
        {
            throw $e;
        }

        // TODO : cert !!!
        // if ($encryption && (cert == null))
        //     $encryption = false;

        switch ($method)
        {
            case StorageMethod::ByBlock:
                $size = StorageMethod::ByBlock_Value;
            case StorageMethod::By5Block:
                $size = StorageMethod::By5Block_Value;
            case StorageMethod::By10Block:
                $size = StorageMethod::By10Block_Value;            
        }

        $swHeader = new StreamWriter($provider + "\\fbc.header");
        $swheader->WriteLine(strval($method) . ";" . strval($size));
        // TODO : cert !!!
        //$swheader->WriteLine(($encryption ? EDModule.Encrypt(cert, toOutput()).Value : toOutput()));
        $swheader->WriteLine(($encryption ? Encryption::encrypt("cert", $this->toOutput())->Value : $this->toOutput()));
        $swheader->Flush();
        $swheader->Close();

        switch ($method)
        {
            case StorageMethod::ByBlock:
            case StorageMethod::By5Block:
            case StorageMethod::By10Block:
            case StorageMethod::ByNbBlock:
                #region Ecriture des fichiers client

                if (($backupMethod == BackupMethod::Standard) || ($backupMethod == BackupMethod::Full))
                {
                    for ($i = 0; $i < $this->Chain->count(); $i += $size)
                    {
                        $xml_blocks = array_fill(0, $size, _string::EmptyString);

                        for ($j = 0; $j < $size; $j++)
                        {
                            $xml = serialize($this->Chain[$i]);
                            $xml_blocks[$j] = $xml . $BlockSep;
                        }

                        $fileout = new StreamWriter($provider + "\\" + Guid::NewGuid() + ".fbc");
                        for ($z = 0; $z < count($xml_blocks); $z++)
                        {
                            if (($xml_blocks[$z] != null) && ($xml_blocks[$z] != _string::EmptyString))
                            {
                                if ($encryption)
                                {
                                    $cuttedStrings = $xml_blocks[z].Cut(65);
                                    for ($cs = 0; $cs < count($cuttedStrings); $cs++)
                                    {
                                        $fileout->WriteLine(
                                            EDModule.Encrypt(
                                                cert,
                                                cuttedStrings[cs]).Value . Alphabetics::HidedChar);
                                        $fileout->Flush();
                                    }
                                }
                                else
                                {
                                    $fileout->WriteLine(xml_blocks[z]);
                                }

                                $fileout->Flush();
                            }
                        }

                        $fileout->Close();
                    }
                }
                #endregion

                #region Ecriture des fichiers de sécurité

                if ((int) backupMethod >= 1)
                {
                    $secIDFile = 1;
                    $fs = new FileStream(
                        $provider + "\\fbc.1.security",
                        AccessMode::WriteOnly_CreateIfNotExists,
                        true);

                    $full = new CCollection(type:_array::ClassName); // String[]
                    $finalByteses = new CCollection(type:_array::ClassName); // Byte[]

                    foreach ($this->Chain as $block)
                        $full->Add(
                            System_String::Chunk(serialize($block) . Alphabetics::HidedChar, 200));

                    for (int fullID = 0; fullID < full.Count; fullID++)
                    {
                        string[] items = full[fullID];
                        for (int strs = 0; strs < items.Length; strs++)
                            finalByteses.Add(
                                EDModule.Encrypt(items[strs], Cryptography.RSA_EncryptionType.PreCalculate,
                                    ECSize: Cryptography.EncryptionSize.enc2048).ByteValue);
                    }

                    for (int i = 0; i < finalByteses.Count; i++)
                    {
                        string fName = DirectoryName + "\\fbc." + secIDFile.ToString() + ".security";

                        if (System.IO.File.Exists(fName))
                        {
                            FileInfo fi = new FileInfo(fName);

                            if ((fi.PrettyOctetsPhysicalSize().Item1 >= 10) &&
                                (fi.PrettyBytesPhysicalSize().Item2 ==
                                Firstruner.Enumerations.OctetsRange.SizeSuffixes[
                                    (int) Firstruner.Enumerations.SizeSuffixesEnumeration.Mega]))
                            {
                                $fs->Flush();
                                $fs->Close();
                                $i++;

                                fName = DirectoryName + "\\fbc." + secIDFile.ToString() + ".security";
                                $fs = new FileStream(fName, FileMode.Create, FileAccess.Write);
                            }
                        }

                        fs.Write(finalByteses[i], 0, finalByteses[i].Length);
                        fs.Flush();
                    }

                    fs.Close();
                }

                #endregion

                return true;
            case StorageMethod::ByFileSize:
                return false;
            default:
                throw new ArgumentOutOfRangeException("method");
        }
    }

    private function CreateCert() : void
    {
        SelfCert.Create(CertificateName:"Firstruner_BlockChain", ValidationRange:1);
    }

    private function loadCert() : void
    {
        cert = Firstruner.Tools.Cryptography.Tools.getStoredCertificate(
            "Firstruner_BlockChain",
            StoreName.TrustedPublisher,
            StoreLocation.LocalMachine);
    }
}