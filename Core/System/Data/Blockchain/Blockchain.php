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

use System\Collections\CCollection;
use System\Data\Blockchain\LoadingCert;
use System\Default\_boolean;
use System\Default\_string;
use System\Guid;

class Blockchain
{
      /**
       *     Pattern attendu Mine_Ended(int blockID, string hash)
       */
      public ?\Closure $Mined = null;

      private string $blockSep = "[_BLOCK_SEP_]";
      private ?X509Certificate2 $cert = null;
      private ?string $signature = null;
      private ?Guid $bC_Guid = null;

      public ?string $Control = null;
      public ?CCollection $Chain = null;
      public bool $MiningWithGuid = false;
      public bool $AutoMining = false;

      private function toOutput() : string
      {
            return
                  ($this->MiningWithGuid ? _boolean::TrueTextValue : _boolean::FalseTextValue) + ";"
                  + ($this->AutoMining ? _boolean::TrueTextValue : _boolean::FalseTextValue) + ";" 
                  + ($this->Control ?? _string::EmptyString) + ";"
                  + ($this->signature ?? _string::EmptyString) + ";" 
                  + $this->bC_Guid;
      }

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
                Firstruner.Tools.Cryptography.Tools.Certificate_Expired += ToolsOnCertificateExpired;
                Firstruner.Tools.Cryptography.Tools.Certificate_NotFind += ToolsOnCertificateNotFind;
                Firstruner.Tools.Cryptography.Tools.Certificate_OverCount += ToolsOnCertificateOverCount;
            }
      }

      #region Events des certificats

      private void ToolsOnCertificateOverCount(int count)
      {
            throw new Exception("Il y a plus d'un certificat (" + count.ToString("#0") + ")");
      }

      private void ToolsOnCertificateNotFind()
      {
            throw new Exception("Certificat introuvable");
      }

      private void ToolsOnCertificateExpired(DateTime dateTime)
      {
            throw new Exception("Le certificat a expiré");
      }

      #endregion

      private void InitializeChain()
      {
          Chain = new List<Block>();
      }

      private Block CreateGenesisBlock(string data)
      {
          Block GenesisBlock = new Block(null, data, Sign: Signature, ControlFormat: Control) { autoMining = autoMining };
          GenesisBlock.Mine();
          return GenesisBlock;
      }

      private void AddGenesisBlock(string data)
      {
          Chain.Add(CreateGenesisBlock(data));
      }

      public Block Last()
      {
          return Chain.Last();
      }

      public void AddBlock(string data)
      {
          Block latestBlock = Last();

          if (!latestBlock.isLock && !autoMining)
              throw new Exception("Le block précédent n'est pas miné");

          if (!latestBlock.isLock)
              Mine();

          Chain.Add(new Block(latestBlock.Hash, data, latestBlock.Index + 1, Signature, Control)
          {
              MineIncludeGuid = MiningWithGuid,
              autoMining = autoMining
          });
      }

      public bool IsValid()
      {
          for (int i = 1; i < Chain.Count; i++)
          {
              Block currentBlock = Chain[i];
              Block previousBlock = Chain[i - 1];

              if ((currentBlock.Hash != currentBlock.ComputeHash()) ||
                  (currentBlock.PreviousHash != previousBlock.Hash))
                  return false;
          }

          return true;
      }

      public async void Mine()
      {
          Block last = Last();
          bool b = false;

          if (last.isLock)
          {
              if (Mined != null)
                  Mined(last.Index, last.Hash);

              return;
          }

          await last.Mine();

          if (Mined != null)
              Mined(last.Index, last.Hash);
      }
      
      public async Task<bool> Load(string DirectoryName, bool Encryption = false)
      {
          Firstruner.Tools.Cryptography.EncryptDecryptModule EDModule =
              new EncryptDecryptModule(null);
          
          if (Encryption && (cert == null))
              Encryption = false;

          string head1 = string.Empty, head2 = String.Empty;
          Enumerations.StockageMethod method = Enumerations.StockageMethod.ByBlock;
          int size = 0;

          using (StreamReader srheader = new StreamReader(DirectoryName + "\\fbc.header"))
          {
              head1 = srheader.ReadLine(); //((int)method).ToString("0") + ";" + size.ToString("0"));
              head2 = srheader.ReadLine(); //(Encryption ? EDModule.Encrypt(cert, toOutput()).Value : toOutput()));
              srheader.Close();
          }

          string[] header = head1.Split(';');
          method = (Enumerations.StockageMethod)int.Parse(header[0]);
          size = int.Parse(header[1]);

          try
          {
              EDModule.Decrypt(cert, head2);
          }
          catch (Firstruner.Exceptions.NeedAdministratorAccesException eAdmin)
          {
              Console.WriteLine("Accès administrateur nécessaire");
              return false;
          }
          catch (Firstruner.Exceptions.PrivateKeyUnavailableException ePK)
          {
              Console.WriteLine("Clé non disponible");
              return false;
          }

          header = (Encryption ? EDModule.Decrypt(cert, head2) : head2).Split(';');
          MiningWithGuid = bool.Parse(header[0]);
          autoMining = bool.Parse(header[1]);
          Control = (header[2] == string.Empty ? null : header[2]);
          Signature = (header[3] == string.Empty ? null : header[3]);
          BC_Guid = new Guid(header[4]);

          switch (method)
          {
              case Enumerations.StockageMethod.ByBlock:
              case Enumerations.StockageMethod.By5Block:
              case Enumerations.StockageMethod.By10Block:
              case Enumerations.StockageMethod.ByNbBlock:
                  Chain = new List<Block>();

                  foreach (string file in System.IO.Directory.GetFiles(DirectoryName, "*.fbc"))
                  {
                      List<string> datasC = new List<string>();
                      List<string> datasU = new List<string>();
                      string srLine = string.Empty;
                      StreamReader sr = new StreamReader(file);
                      do
                      {
                          srLine = sr.ReadLine();

                          if (srLine.IsNull())
                              break;

                          datasC.Add(
                              srLine.Split(Firstruner.Enumerations.Langages.Alphabetics.HidedChar.ToCharArray()[0])[0]);
                      } while (!srLine.IsNull());
                      sr.Close();

                      foreach (string data in datasC)
                          datasU.Add(EDModule.Decrypt(cert, data));

                      string final = string.Empty, temp = null;

                      for (int b = 0; b < datasU.Count; b++)
                      {
                          int end = (datasU[b] + ((b < (datasU.Count - 1)) ? datasU[b + 1] : string.Empty)).IndexOf(BlockSep);

                          if (!temp.IsNull() || ((end < 0) && (temp.IsNull())))
                          {
                              final += temp ?? datasU[b];
                              temp = null;
                          }
                          else
                          {
                              final +=
                                  (datasU[b] + ((b < (datasU.Count - 1)) ? datasU[b + 1] : string.Empty)).Substring(
                                      0, end);
                              
                              Chain.Add(new Block(final));
                              final = string.Empty;
                              temp =
                                  (datasU[b] + ((b < (datasU.Count - 1)) ? datasU[b + 1] : string.Empty)).Substring(
                                      end + BlockSep.Length);
                          }
                      }
                  }
                  return true;
              case Enumerations.StockageMethod.ByFileSize:
                  return false;
              default:
                  throw new ArgumentOutOfRangeException(nameof(method), method, null);
          }
      }

      public async Task<bool> Save(string DirectoryName,
          Enumerations.StockageMethod method = Enumerations.StockageMethod.ByBlock, int size = 0,
          bool Encryption = false, Enumerations.BackupMethod backupMethod = Enumerations.BackupMethod.Standard)
      {
          Firstruner.Tools.Cryptography.EncryptDecryptModule EDModule =
              new EncryptDecryptModule(null);
          
          try
          {
              foreach (string file in System.IO.Directory.GetFiles(DirectoryName))
                  System.IO.File.Delete(file);
          }
          catch (Exception e)
          {
              throw e;
          }

          if (Encryption && (cert == null))
              Encryption = false;

          size = (method == Enumerations.StockageMethod.By5Block
              ? 5
              : (method == Enumerations.StockageMethod.By10Block
                  ? 10
                  : (method == Enumerations.StockageMethod.ByBlock
                      ? 1
                      : size)));

          using (StreamWriter swheader = new StreamWriter(DirectoryName + "\\fbc.header"))
          {
              swheader.WriteLine(((int)method).ToString("0") + ";" + size.ToString("0"));
              swheader.WriteLine((Encryption ? EDModule.Encrypt(cert, toOutput()).Value : toOutput()));
              swheader.Flush();
              swheader.Close();
          }

          switch (method)
          {
              case Enumerations.StockageMethod.ByBlock:
              case Enumerations.StockageMethod.By5Block:
              case Enumerations.StockageMethod.By10Block:
              case Enumerations.StockageMethod.ByNbBlock:
                  #region Ecriture des fichiers client

                  if ((backupMethod == Enumerations.BackupMethod.Standard) || (backupMethod == Enumerations.BackupMethod.Full))
                  {
                      for (int i = 0; i < Chain.Count; i += size)
                      {
                          string[] xml_blocks = new string[size];

                          for (int j = 0; j < size; j++)
                          {
                              string xml = Chain[i].Serialize();
                              xml_blocks[j] = xml + BlockSep;
                          }

                          using (
                              StreamWriter fileout =
                                  new StreamWriter(DirectoryName + "\\" + Guid.NewGuid().ToString() + ".fbc"))
                          {
                              for (int z = 0; z < xml_blocks.Length; z++)
                              {
                                  if ((xml_blocks[z] != null) && (xml_blocks[z] != String.Empty))
                                  {
                                      if (Encryption)
                                      {
                                          string[] cuttedStrings = cuttedStrings = xml_blocks[z].Cut(65);
                                          for (int cs = 0; cs < cuttedStrings.Length; cs++)
                                          {
                                              fileout.WriteLine(EDModule.Encrypt(cert, cuttedStrings[cs]).Value +
                                                                Langages.Alphabetics.HidedChar);
                                              fileout.Flush();
                                          }
                                      }
                                      else
                                      {
                                          fileout.WriteLine(xml_blocks[z]);
                                      }

                                      fileout.Flush();
                                  }
                              }

                              fileout.Close();
                          }
                      }
                  }
                  #endregion

                  #region Ecriture des fichiers de sécurité

                  if ((int) backupMethod >= 1)
                  {
                      int secIDFile = 1;
                      FileStream fs = new FileStream(DirectoryName + "\\fbc.1.security", FileMode.Create,
                          FileAccess.Write);

                      List<string[]> full = new List<string[]>();
                      List<byte[]> finalByteses = new List<byte[]>();

                      foreach (Block block in Chain)
                          full.Add(
                              (block.Serialize() + Firstruner.Enumerations.Langages.Alphabetics.HidedChar).Cut(200));

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
                                  fs.Flush();
                                  fs.Close();
                                  i++;

                                  fName = DirectoryName + "\\fbc." + secIDFile.ToString() + ".security";
                                  fs = new FileStream(fName, FileMode.Create, FileAccess.Write);
                              }
                          }

                          fs.Write(finalByteses[i], 0, finalByteses[i].Length);
                          fs.Flush();
                      }

                      fs.Close();
                  }

                  #endregion

                  return true;
              case Enumerations.StockageMethod.ByFileSize:
                  return false;
              default:
                  throw new ArgumentOutOfRangeException(nameof(method), method, null);
          }
      }

      private void CreateCert()
      {
          SelfCert.Create(CertificateName:"Firstruner_BlockChain", ValidationRange:1);
      }

      private void loadCert()
      {
          cert = Firstruner.Tools.Cryptography.Tools.getStoredCertificate(
              "Firstruner_BlockChain",
              StoreName.TrustedPublisher,
              StoreLocation.LocalMachine);
      }
}