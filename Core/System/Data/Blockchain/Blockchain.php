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
 * Please refer to https:*firstruner.fr/ or contact Firstruner for more information.
 *
 * @author    Firstruner and Contributors <contact@firstruner.fr>
 * @copyright 2024-2026 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace System\Data\Blockchain;

use Firstruner\Cryptography\EncryptDecryptModule;
use Firstruner\Langages\Alphabetics;
use System\_Array as System_Array;
use System\_String as System_String;
use System\Collections\CCollection;
use System\Console;
use System\Default\_sbyte;
use System\Default\_string;
use System\Exceptions\ArgumentOutOfRangeException;
use System\Exceptions\NeedAdministratorAccesException;
use System\Exceptions\PrivateKeyUnavailableException;
use System\Guid;
use System\IO\AccessMode;
use System\IO\File;
use System\IO\FileInfo;
use System\IO\FileStream;
use System\IO\StreamReader;
use System\IO\StreamWriter;
use System\IO\SuffixesRange;
use System\IO\SuffixesSizes;
use System\Linq\ILinq;
use System\Security\Cryptography\EncryptionSize;
use System\Security\Cryptography\RsaEncryptionType;
use System\Security\Cryptography\X509Certificates\X509Certificate2;
use System\Threading\Task;

final class Blockchain
{
    private const CertName = "Firstruner_BlockChain";
    private const BlockSep = "[_BLOCK_SEP_]";

    private \Closure $Certificate_Expired;
    private \Closure $Certificate_NotFind;
    private \Closure $Certificate_OverCount;

    public \Closure $Mined;

    private ?X509Certificate2 $cert = null;

    public ?string $Control = null;
    private ?string $Signature = null;
    private Guid $BC_Guid;


    public CCollection $Chain;
    public bool $MiningWithGuid = false;
    public bool $AutoMining = false;

    public function __construct(
        string $initializingData = "{}",
        ?string $sign = null,
        ?string $controlFormat = null,
        bool $eventManagement = false,
        int $loadingMode = LoadingCert::Ignore
    ) {
        $this->BC_Guid = new Guid();
        $this->Chain = new CCollection([], 0, Block::ClassName);

        if ($loadingMode >= LoadingCert::Create) {
            $this->CreateCert();
            $loadingMode = LoadingCert::Load;
        }

        if ($loadingMode == LoadingCert::Load)
            $this->loadCert();

        $this->Signature = $sign;
        $this->Control = $controlFormat;

        $this->InitializeChain();
        $this->AddGenesisBlock(($initializingData == "{}" ? "{" . $this->BC_Guid . "}" : $initializingData));

        if ($eventManagement) {
            $this->Certificate_Expired = \Closure::fromCallable([$this, 'ToolsOnCertificateExpired']);
            $this->Certificate_NotFind = \Closure::fromCallable([$this, 'ToolsOnCertificateNotFind']);
            $this->Certificate_OverCount = \Closure::fromCallable([$this, 'ToolsOnCertificateOverCount']);
        }
    }

    #region Events des certificats

    private function ToolsOnCertificateOverCount(int $count): void
    {
        throw new \Exception("Il y a plus d'un certificat (" + $count + ")");
    }

    private function ToolsOnCertificateNotFind(): void
    {
        throw new \Exception("Certificat introuvable");
    }

    private function ToolsOnCertificateExpired(\DateTime $dateTime): void
    {
        throw new \Exception("Le certificat a expiré");
    }

    #endregion

    private function toOutput()
    {
        return implode(
            ";",
            [
                $this->MiningWithGuid,
                $this->AutoMining,
                ($this->Control ?? ''),
                ($this->Signature ?? ''),
                $this->BC_Guid
            ]
        );
    }

    private function InitializeChain()
    {
        $this->Chain = new CCollection([], 0, Block::ClassName);
    }

    private function CreateGenesisBlock($data)
    {
        $GenesisBlock = new Block(null, $data, $this->Signature, $this->Control);
        $GenesisBlock->AutoMining($this->AutoMining);
        $GenesisBlock->Mine();
        return $GenesisBlock;
    }

    private function AddGenesisBlock($data)
    {
        $this->Chain->Add($this->CreateGenesisBlock($data));
    }

    public function Last()
    {
        return $this->Chain->Last();
    }

    public function AddBlock($data)
    {
        $latestBlock = $this->Last();

        if (!$latestBlock->IsLock() && !$this->AutoMining)
            throw new \Exception("Le block précédent n'est pas miné");

        if (!$latestBlock->IsLock())
            $this->Mine();

        $newBlock = new Block($latestBlock->Hash(), $data, $latestBlock->Index + 1, $this->Signature, $this->Control);
        $newBlock->MineIncludeGuid($this->MiningWithGuid);
        $newBlock->AutoMining($this->AutoMining);

        $this->Chain->Add($newBlock);
    }

    public function IsValid()
    {
        for ($i = 1; $i < $this->Chain->count(); $i++) {
            $currentBlock = $this->Chain[$i];
            $previousBlock = $this->Chain[$i - 1];

            if (($currentBlock->Hash() != $currentBlock->ComputeHash())
                || ($currentBlock->PreviousHash() != $previousBlock->Hash())
            ) {
                return false;
            }
        }

        return true;
    }

    public function Mine()
    {
        $last = $this->Last();
        $b = false;

        if ($last->IsLock()) {
            if (!is_null($this->Mined))
                call_user_func($this->Mined, $last->Index, $last->Hash());

            return;
        }

        $last->Mine();

        if ($this->Mined != null)
            call_user_func($this->Mined, $last->Index, $last->Hash);
    }

    public function Load(string $DirectoryName, bool $Encryption = false): bool
    {
        $EDModule = new EncryptDecryptModule(null);

        if ($Encryption && is_null($this->cert))
            $Encryption = false;

        $head1 = _string::EmptyString;
        $head2 = _string::EmptyString;

        $method = StorageMethod::ByBlock;
        $size = 0;

        $srheader = new StreamReader($DirectoryName + "\\fbc.header");
        $head1 = $srheader->ReadLine(); //((int)method).ToString("0") + ";" + size.ToString("0"));
        $head2 = $srheader->ReadLine(); //(Encryption ? EDModule.Encrypt(cert, toOutput()).Value : toOutput()));
        $srheader->Close();
        unset($srheader);

        $header = explode(';', $head1);
        $method = intval($header[0]);
        $size = intval($header[1]);

        try {
            $EDModule->Decrypt($this->cert, $head2);
        } catch (NeedAdministratorAccesException $eAdmin) {
            Console::WriteLine("Accès administrateur nécessaire");
            return false;
        } catch (PrivateKeyUnavailableException $ePK) {
            Console::WriteLine("Clé non disponible");
            return false;
        }

        $header = explode(';', ($Encryption ? $EDModule->Decrypt($this->cert, $head2) : $head2));
        $this->MiningWithGuid = boolval($header[0]);
        $this->AutoMining = boolval($header[1]);
        $this->Control = ($header[2] == _string::EmptyString ? null : $header[2]);
        $this->Signature = ($header[3] == _string::EmptyString ? null : $header[3]);
        $this->BC_Guid = new Guid($header[4]);

        switch ($method) {
            case StorageMethod::ByBlock:
            case StorageMethod::By5Block:
            case StorageMethod::By10Block:
            case StorageMethod::ByNbBlock:
                $this->Chain = new CCollection([], 0, Block::ClassName);

                foreach (glob($DirectoryName . "/*.fbc") as $file) {
                    $_datasC = new CCollection([], 0, _string::ClassName);
                    $_datasU = new CCollection([], 0, _string::ClassName);
                    $_srLine = _string::EmptyString;
                    $_sr = new StreamReader($file);

                    do {
                        $_srLine = $_sr->ReadLine();

                        if (is_null($_srLine))
                            break;

                        $_datasC->Add(explode(Alphabetics::HidedChar, $_srLine)[0]);
                    } while (!is_null($_srLine));

                    $_sr->Close();

                    foreach ($_datasC as $data)
                        $_datasU->Add($EDModule->Decrypt($this->cert, $data));

                    $final = _string::EmptyString;
                    $temp = null; // String Null

                    for ($b = 0; $b < $_datasU->count(); $b++) {
                        $end = strpos(
                            Blockchain::BlockSep,
                            ($_datasU[$b] + (
                                ($b < ($_datasU->count() - 1))
                                ? $_datasU[$b + 1]
                                : _string::EmptyString)
                            )
                        );

                        if (!is_null($temp) || (($end < 0) && is_null($temp))) {
                            $final .= $temp ?? $_datasU[$b];
                            $temp = null;
                        } else {
                            $final .= substr(
                                ($_datasU[$b] + (
                                    ($b < ($_datasU->count() - 1))
                                    ? $_datasU[$b + 1]
                                    : _string::EmptyString)),
                                0,
                                $end
                            );

                            $this->Chain->Add(new Block($final));
                            $final = _string::EmptyString;

                            $temp = substr(
                                ($_datasU[$b] + (
                                    ($b < ($_datasU->count() - 1))
                                    ? $_datasU[$b + 1]
                                    : _string::EmptyString)),
                                $end + strlen(Blockchain::BlockSep)
                            );
                        }
                    }
                }
                return true;
            case StorageMethod::ByFileSize:
                return false;
            default:
                throw new ArgumentOutOfRangeException("method", $method, null);
        }
    }

    public function Save(
        string $DirectoryName,
        int $method = StorageMethod::ByBlock,
        int $size = 0,
        bool $Encryption = false,
        int $backupMethod = BackupMethod::Standard
    ): bool {
        $EDModule = new EncryptDecryptModule(null);

        try {
            foreach (glob($DirectoryName) as $file)
                File::Delete($file);
        } catch (\Exception $e) {
            throw $e;
        }

        if ($Encryption && is_null($this->cert))
            $Encryption = false;

        if (in_array($method, [
            StorageMethod::ByBlock,
            StorageMethod::By5Block,
            StorageMethod::By10Block,
        ]))
            $size = $method;

        $swheader = new StreamWriter($DirectoryName . "\\fbc.header");
        $swheader->WriteLine($method + ";" + $size);
        $swheader->WriteLine(($Encryption ? $EDModule->Encrypt($this->cert, $this->toOutput())->Value() : $this->toOutput()));
        $swheader->Flush();
        $swheader->Close();
        unset($swheader);

        switch ($method) {
            case StorageMethod::ByBlock:
            case StorageMethod::By5Block:
            case StorageMethod::By10Block:
            case StorageMethod::ByNbBlock:
                #region Ecriture des fichiers client
                if (($backupMethod == BackupMethod::Standard)
                    || ($backupMethod == BackupMethod::Full)
                ) {
                    for ($i = 0; $i < $this->Chain->count(); $i += $size) {
                        $xml_blocks = System_Array::CreateArray($size);

                        for ($j = 0; $j < $size; $j++) {
                            $xml = serialize($this->Chain[$i]);
                            $xml_blocks[$j] = $xml . Blockchain::BlockSep;
                        }

                        $fileout = new StreamWriter($DirectoryName . "\\" . Guid::NewGuid() . ".fbc");
                        for ($z = 0; $z < count($xml_blocks); $z++) {
                            if (!is_null($xml_blocks[$z]) && ($xml_blocks[$z] != _string::EmptyString)) {
                                if ($Encryption) {
                                    $cuttedStrings = System_String::Cut($xml_blocks[$z], 65);
                                    for ($cs = 0; $cs < count($cuttedStrings); $cs++) {
                                        $fileout->WriteLine(
                                            $EDModule->Encrypt(
                                                $this->cert,
                                                $cuttedStrings[$cs]
                                            )->Value()
                                                . Alphabetics::HidedChar
                                        );
                                        $fileout->Flush();
                                    }
                                } else {
                                    $fileout->WriteLine($xml_blocks[$z]);
                                }

                                $fileout . Flush();
                            }
                        }

                        $fileout->Close();
                        unset($fileout);
                    }
                }
                #endregion

                #region Ecriture des fichiers de sécurité

                if ($backupMethod >= 1) {
                    $secIDFile = 1;
                    $fs = new FileStream(
                        $DirectoryName . "\\fbc.1.security",
                        AccessMode::WriteOnly_Create
                    );

                    $full = new CCollection([], 0, _string::ClassName);
                    $finalByteses = new CCollection([], 0, _sbyte::ClassName);

                    foreach ($this->Chain as $block)
                        $full->Add(
                            System_String::Cut(serialize($block) . Alphabetics::HidedChar, 200)
                        );

                    for ($fullID = 0; $fullID < $full->count(); $fullID++) {
                        $items = $full[$fullID];
                        for ($strs = 0; $strs < count($items); $strs++)
                            $finalByteses->Add(
                                $EDModule->Encrypt(
                                    $items[$strs],
                                    RsaEncryptionType::PreCalculate,
                                    EncryptionSize::enc2048
                                )->ByteValue()
                            );
                    }

                    for ($i = 0; $i < $finalByteses->count(); $i++) {
                        $fName = $DirectoryName + "\\fbc." . $secIDFile . ".security";

                        if (File::Exists($fName)) {
                            $fi = new FileInfo($fName);

                            if ((FileInfo::PrettyOctetsPhysicalSize($fi)->Item1() >= 10) &&
                                (FileInfo::PrettyBytesPhysicalSize($fi)->Item2() ==
                                    SuffixesRange::OctetSuffixes[SuffixesSizes::Mega])
                            ) {
                                $fs->Flush();
                                $fs->Close();
                                $i++;

                                $fName = $DirectoryName . "\\fbc." . $secIDFile . ".security";
                                $fs = new FileStream($fName, AccessMode::WriteOnly_Create);
                            }
                        }

                        $fs->Write($finalByteses[$i], 0, $finalByteses[$i]->count());
                        $fs->Flush();
                    }

                    $fs->Close();
                    unset($fs);
                }

                #endregion

                return true;
            case StorageMethod::ByFileSize:
                return false;
            default:
                throw new ArgumentOutOfRangeException("method", $method, null);
        }
    }



    private function CreateCert(?string $path = null)
    {
        $config = [
            "digest_alg" => "sha256",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        // Générer une clé privée
        $privateKey = openssl_pkey_new($config);

        // Créer un CSR
        $dn = [
            "countryName" => "FR",
            "stateOrProvinceName" => "IDF",
            "localityName" => "St CYR SUR MORIN",
            "organizationName" => "Firstruner",
            "commonName" => Blockchain::CertName,
        ];

        $csr = openssl_csr_new($dn, $privateKey, $config);

        // Auto-signer le certificat
        $certificate = openssl_csr_sign($csr, null, $privateKey, 365);

        // Sauvegarder les fichiers
        openssl_pkey_export_to_file($privateKey, (is_null($path) ? __DIR__ : $path) . Blockchain::CertName . "private.key");
        openssl_csr_export_to_file($csr, (is_null($path) ? __DIR__ : $path) . Blockchain::CertName . "request.csr");
        openssl_x509_export_to_file($certificate, (is_null($path) ? __DIR__ : $path) . Blockchain::CertName . "certificate.crt");
    }

    private function loadCert(?string $path = null)
    {
        $certFile = (is_null($path) ? __DIR__ : $path) . Blockchain::CertName . "certificate.crt";

        $_cert = file_get_contents($certFile);
        if ($_cert === false)
            die("Erreur de lecture du fichier de certificat.");

        $parsedCert = openssl_x509_read($_cert);

        if ($parsedCert === false)
            die("Le certificat est invalide.");

        $this->cert = new X509Certificate2();
        $this->cert->Certificate($parsedCert);
    }
}
