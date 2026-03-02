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

namespace Firstruner\Cryptography;

use System\_String as System_String;
use System\Default\_array;
use System\Default\_string;
use System\Exceptions\ArgumentNullException;
use System\Exceptions\ArgumentOutOfRangeException;
use System\Exceptions\EncryptionException;
use System\Exceptions\IOException;
use System\Exceptions\NeedAdministratorAccesException;
use System\Exceptions\NotImplementedException;
use System\Exceptions\PrivateKeyUnavailableException;
use System\IO\File;
use System\IO\MemoryStream;
use System\IO\StreamReader;
use System\IO\StreamWriter;
use System\ObjectExtension;
use System\Random;
use System\Runtime\InteropService\Marshal;
use System\Security\Cryptography\CipherMode;
use System\Security\Cryptography\CryptoStream;
use System\Security\Cryptography\CryptoStreamMode;
use System\Security\Cryptography\CspParameters;
use System\Security\Cryptography\EncryptionMode;
use System\Security\Cryptography\EncryptionSize;
use System\Security\Cryptography\MD5CryptoServiceProvider;
use System\Security\Cryptography\PaddingMode;
use System\Security\Cryptography\RijndaelManaged;
use System\Security\Cryptography\RSACryptoServiceProvider;
use System\Security\Cryptography\RsaEncryptionType;
use System\Security\Cryptography\SHA1Managed;
use System\Security\Cryptography\SHA256Managed;
use System\Security\Cryptography\SHA512Managed;
use System\Security\Cryptography\TripleDESCryptoServiceProvider;
use System\Security\Cryptography\X509Certificates\X509Certificate2;
use System\Security\SecureString;
use System\Text\Encoding\ASCII;
use System\Text\Encoding\ASCIIEncoding;
use System\Text\Encoding\Unicode;
use System\Text\Encoding\UnicodeEncoding;
use System\Text\Encoding\UTF8;
use System\Text\StringBuilder;
use System\Tuple;

final class EncryptDecryptModule
{
      // Fields
      private bool $_LockKey = false;
      private array $CryptoKeys = array(0);
      private int $k = -1;
      private RSACryptoServiceProvider $rsa;
      //private UnicodeEncoding _encoder = new UnicodeEncoding();
      private RijndaelManaged $myRijndael;
      private X509Certificate2 $certX509;
      private string $init_publicKey;
      private string $init_privateKey;
      private string $openSSLConfigFile = "";

      // Properties
      public function LockKey(bool $value): void
      {
            $this->_LockKey = $value;
            if (!$value)
                  $k = -1;
      }

      public function __construct(
            array|string|null $keys = null,
            string|int $encryption = -1,
            ?SecureString $securePassword = null,
            string $OpenSSLConfigFile = _string::EmptyString)
      {
            $this->openSSLConfigFile = $OpenSSLConfigFile;
            if ($OpenSSLConfigFile != _string::EmptyString)
            {
                  if (!File::Exists($OpenSSLConfigFile))
                        throw new IOException("Fichier $OpenSSLConfigFile introuvable");

                  $this->rsa = new RSACryptoServiceProvider($OpenSSLConfigFile);
            }

            $this->myRijndael = new RijndaelManaged();

            if (($keys == null)
                  && ($encryption < 0)
                  && ($securePassword == null))
                  $this->init_ByArray([]);

            if (gettype($keys) == _array::ClassName)
                  $this->init_ByArray($keys);

            if (
                  (gettype($keys) == _string::ClassName)
                  && (gettype($encryption) == _string::ClassName)
            )
                  $this->init_ByKeys($keys, $encryption);

            if (
                  (gettype($keys) == _string::ClassName)
                  && (gettype($encryption) == _string::ClassName)
                  && (gettype($securePassword) != null)
            )
                  $this->init_ByFile($keys, $encryption, $securePassword);
      }

      // Constructor / Deconstructor
      private function init_ByKeys(?string $PublicKey = null, ?string $PrivateKey = null): void
      {
            $this->init_publicKey = $PublicKey;
            $this->init_privateKey = $PrivateKey;
      }

      #region Private Methodes

      private function init_ByArray(array $keys_list)
      {
            $this->CryptoKeys = $keys_list;
      }

      private function init_ByFile(string $keyFile, int $mode, SecureString $password): void
      {
            switch ($mode) {
                  case EncryptionMode::MD5_Value:
                        $this->MD5_LoadKeysFile($keyFile);
                        break;

                  case EncryptionMode::RSA_Value:
                        $this->RSA_loadKeyFromFile($keyFile);
                        break;

                  case EncryptionMode::X509_Value:
                        $this->X509_LoadKeyFromFile($keyFile, $password);
                        break;

                  case EncryptionMode::AES_Value:
                        $this->AES_LoadKeysFromFile(
                              explode($keyFile, '|')[0],
                              explode($keyFile, '|')[1]
                        );
                        break;

                  default:
                        throw new \Exception("Not disponible");
            }
      }

      private function Private_Decrypt_RSA(CryptedValue $CValue, bool $RSAMode, string $privateKey): string
      {
            if (!$RSAMode)
                  return $this->Private_Decrypt_Main($CValue, EncryptionMode::RSA_Value);

            return $this->RSA_DecryptFromByteArray($CValue->ByteValue(), $privateKey);
      }

      private function Private_Decrypt_Main(CryptedValue $CValue, int $mode, string $specKey = _string::EmptyString): string|null
      {
            $index = $CValue->ID_Key();

            try {
                  switch ($mode) {
                        case EncryptionMode::MD5_Value:
                              return $this->MD5_Decrypt($CValue->Value(), $index, true);

                        case EncryptionMode::SHA1_Value:
                        case EncryptionMode::SHA256_Value:
                        case EncryptionMode::SHA512_Value:
                              throw new \Exception("No SHA decryption possible"); //Cannot Decrypt SHA value

                        case EncryptionMode::RSA_Value:
                              return $this->RSA_DecryptFromString($CValue->Value());
                        case EncryptionMode::X509_Value:
                              return $this->X509_DecryptFromArray($CValue->ByteValue());
                        case EncryptionMode::AES_Value:
                              return $this->AES_Decrypt($CValue->ByteValue());

                        case EncryptionMode::Caesar_Value:
                              return $this->Caesar_EncryptDecrypt($CValue->Value(), -$CValue->ID_Key())->Value();
                        case EncryptionMode::PlayFair_Value:
                              return $this->PlayFair_Decrypt($CValue->Value(), $specKey ?? $this->CryptoKeys[0]);
                  }

                  return null;
            } catch (\Exception $e) {
                  //Console.WriteLine(e.Message);
                  return null;
            }
      }

      private function Private_EncryptRSAByPublicKey(string $value, bool $RSAMode, string $publicKey): CryptedValue
      {
            if (!$RSAMode)
                  return $this->Private_EncryptMain($value, EncryptionMode::RSA_Value);

            return $this->RSA_EncryptFromStringPublicKey($value, $publicKey);
      }

      private function Private_EncryptByKey(string $value, int $key, int $mode): CryptedValue
      {
            $this->_LockKey = true;
            $this->k = $key;
            $value2 = $this->Encrypt($value, $mode);
            $this->_LockKey = false;
            return $value2;
      }

      private function Private_EncryptMain(string $value, int $mode, string $specKey = _string::EmptyString): ?CryptedValue
      {
            $i = $this->k;

            if ($mode == EncryptionMode::MD5_Value)
            {
                  $i = (new Random())->Next(0, count($this->CryptoKeys) - 1);

                  if ($this->_LockKey && ($this->k >= 0))
                        $i = $this->k;

                  if ($this->_LockKey)
                        $this->k = $i;
            }

            try {
                  switch ($mode) {
                        case EncryptionMode::AES_Value:
                              return new CryptedValue($this->AES_Encrypt($value));

                        case EncryptionMode::MD5_Value:
                              if (count($this->CryptoKeys) == 0)
                                    throw new EncryptionException("No CryptoKeys defined");

                              return new CryptedValue(
                                    $this->MD5_Encrypt($value, $i, true),
                                    $i
                                    );

                        case EncryptionMode::RSA_Value:
                              return new CryptedValue($this->RSA_EncryptFromString($value), 0);

                        case EncryptionMode::X509_Value:
                              return new CryptedValue($this->X509_DecryptFromArray(Unicode::GetBytes($value)), 0);

                        case EncryptionMode::SHA1_Value:
                        case EncryptionMode::SHA256_Value:
                        case EncryptionMode::SHA512_Value:
                              return $this->SHA_Encrypt($mode, $value);

                        case EncryptionMode::Caesar_Value:
                              return $this->Caesar_EncryptDecrypt($value, $i);

                        case EncryptionMode::PlayFair_Value:
                              return new CryptedValue($this->PlayFair_Encrypt($value, $specKey ?? $this->CryptoKeys[0]));
                  }
            } catch (\Exception $e) {
                  //Console.WriteLine(e.Message);
                  return null;
            }

            return null;
      }

      private function Private_AES_GenerateNewKeys(int $size): void
      {
            $this->myRijndael->KeySize($size > 256 ? 256 : $size);
            $this->myRijndael->GenerateKey();
            $this->myRijndael->GenerateIV();
      }

      #endregion Private Methodes

      function __destruct()
      {
            $this->k = -1;
            $this->_LockKey = false;
            unset($this->CryptoKeys);
            unset($this->init_privateKey);
            unset($this->init_publicKey);
            unset($this->certX509);
            unset($this->myRijndael);
            //unset(_encoder);
            unset($this->rsa);
      }

      // Methods

      #region DecryptCallMethods
      public function Decrypt(): ?string
      {
            $args = func_get_args();
            $numArgs = func_num_args();

            if (($numArgs == 2)
                  && ($args[0] instanceof CryptedValue)
                  && (is_int($args[1]))
            )
                  return $this->Decrypt_A($args[0], $args[1]);

            if (($numArgs == 3)
                  && ($args[0] instanceof CryptedValue)
                  && (is_bool($args[1]))
                  && (is_string($args[2]))
            )
                  return $this->Decrypt_B($args[0], $args[1], $args[2]);

            if (($numArgs == 2)
                  && (is_string($args[0]))
                  && (is_int($args[1]))
            )
                  return $this->Decrypt_C($args[0], $args[1]);

            if (($numArgs == 3)
                  && (is_string($args[0]))
                  && (is_int($args[1]))
                  && (is_int($args[2]))
            )
                  return $this->Decrypt_D($args[0], $args[1], $args[2]);

            if (($numArgs == 2)
                  && (is_string($args[0]))
                  && ($args[1] instanceof Tuple)
            )
                  return $this->Decrypt_E($args[0], $args[1]);

            if (($numArgs == 3)
                  && (is_string($args[0]))
                  && ($args[1] instanceof RsaEncryptionType)
                  && (is_string($args[2]))
            )
                  return $this->Decrypt_RSA($args[0], $args[1], $args[2]);

            if (($numArgs == 2)
                  && ($args[0] instanceof X509Certificate2)
                  && (is_string($args[1]))
            )
                  return $this->Decrypt_Cert($args[0], $args[1]);

            return null;
      }

      /// <summary>
      /// Décryptage de valeur
      /// </summary>
      /// <param name="mode">SHA non disponible</param>
      /// <returns></returns>
      private function Decrypt_A(CryptedValue $CValue, int $mode): string
      {
            return $this->Private_Decrypt_Main($CValue, $mode);
      }

      public function Decrypt_B(CryptedValue $CValue, bool $RSAkey, string $privateKey): string
      {
            if (!$RSAkey)
                  return $this->Decrypt_A($CValue, EncryptionMode::RSA_Value);

            return $this->Private_Decrypt_RSA($CValue, $RSAkey, $privateKey);
      }

      public function Decrypt_C(string $CValue, int $mode): string
      {
            return $this->Decrypt_A(new CryptedValue($CValue), $mode);
      }

      public function Decrypt_D(string $CValue, int $mode, int $keyCode): string
      {
            return $this->Decrypt_A(new CryptedValue($CValue, $keyCode), $mode);
      }

      public function Decrypt_E(string $CValue, Tuple $MethodKey): string
      {
            if ($MethodKey->Item1() == EncryptionMode::PlayFair_Value)
                  return $this->PlayFair_Decrypt($CValue, $MethodKey->Item2());

            return $this->Decrypt_C($CValue, $MethodKey->Item1());
      }

      /** <summary>
       *
       * </summary>
       * <param name="CValue">Valeur à crypter</param>
       * <param name="RSA_EncryptionType">Type d'encryptage RSA<remarks>
       * Init : Utilise la clé fournie à l'instanciation,
       * User : Utilise la clé fournie dans la méthode,
       * Auto : Non utilisable,
       * Default : Non utilisate,
       * PreCalculate : Utilise une clé standard
       * </remarks></param>
       * <param name="_privateKey">Clé public fourni par l'utilisateur</param>
       * <returns></returns>
       */
      public function Decrypt_RSA(string $CValue, RsaEncryptionType $RSA_EncryptionType, ?string $_privateKey = null): string
      {
            switch ($RSA_EncryptionType) {
                  case RsaEncryptionType::Init:
                        return $this->Decrypt(new CryptedValue($CValue), true, $this->init_privateKey);

                  case RsaEncryptionType::User:
                        if (is_null($_privateKey))
                              throw new \Exception("Clé indiponible");

                        return $this->Decrypt(new CryptedValue($CValue), true, $_privateKey);

                  case RsaEncryptionType::Auto:
                  case RsaEncryptionType::PreCalculate:
                  case RsaEncryptionType::Default:
                        throw new \Exception("Non disponible");
                  default:
                        throw new ArgumentOutOfRangeException("$RSA_EncryptionType", 0, null);
            }
      }

      public function Decrypt_Cert(X509Certificate2 $cert, string $value): string
      {
            if (!$cert->HasPrivateKey())
                  throw new PrivateKeyUnavailableException();

            try {
                  ObjectExtension::IsNull($cert->PrivateKey());
            } catch (\Exception $e) {
                  if (Marshal::GetHRForException($e) == -2146893802) // Clé indisponible car non admin
                        throw new NeedAdministratorAccesException($e->getMessage());
            }

            return $this->X509_Decrypt($cert, $value);
      }

      #endregion DecryptCallMethods

      #region EncryptCallMethods

      public function Encrypt(): ?CryptedValue
      {
            $args = func_get_args();
            $numArgs = func_num_args();

            if (($numArgs == 2)
                  && (is_string($args[0]))
                  && (is_int($args[1]))
            )
                  return $this->Encrypt_A($args[0], $args[1]);

            if (($numArgs == 3)
                  && (is_string($args[0]))
                  && (is_int($args[1]))
                  && (is_int($args[2]))
            )
                  return $this->Encrypt_B($args[0], $args[1], $args[2]);

            if (($numArgs == 4)
                  && (is_string($args[0]))
                  && ($args[1] instanceof RsaEncryptionType)
                  && (is_string($args[2]))
                  && (is_int($args[3]))
            )
                  return $this->Encrypt_RSA($args[0], (int)$args[1], $args[2], $args[3]);


            if (($numArgs == 2)
                  && ($args[0] instanceof X509Certificate2)
                  && (is_string($args[1]))
            )
                  return $this->Encrypt_Cert($args[0], $args[1]);

            return null;
      }

      private function Encrypt_A(string $value, int $mode): CryptedValue
      {
            return $this->Private_EncryptMain($value, $mode);
      }

      private function Encrypt_B(string $value, int $mode, int $keyCode): CryptedValue
      {
            return $this->Private_EncryptByKey($value, $keyCode, $mode);
      }

      private function Encrypt_C(string $CValue, Tuple $MethodKey): CryptedValue
      {
            if ($MethodKey->Item1() == EncryptionMode::PlayFair_Value)
                  return new CryptedValue($this->PlayFair_Encrypt($CValue, $MethodKey->Item2()));

            return $this->Encrypt_A($CValue, $MethodKey->Item1());
      }

      /// <summary>
      ///
      /// </summary>
      /// <param name="value">Valeur à crypter</param>
      /// <param name="RSA_EncryptionType">Type d'encryptage RSA<remarks>
      /// Auto : Recherche la clé automatiquement,
      /// Default : Utilise une clé 1024 pour l'encryptage
      /// Init : Utilise la clé fournie à l'instanciation,
      /// PreCalculate : Utilise une clé standard,
      /// User : Utilise la clé fournie dans la méthode
      /// </remarks></param>
      /// <param name="_publicKey">Clé public fourni par l'utilisateur</param>
      /// <param name="ECSize">Clé précalculée</param>
      /// <returns></returns>
      private function Encrypt_RSA(
            string $value,
            int $RSA_EncryptionType,
            ?string $_publicKey = null,
            int $ECSize = EncryptionSize::encAuto
      ): CryptedValue {
            switch ($RSA_EncryptionType) {
                  case RsaEncryptionType::PreCalculate:
                        return $this->RSA_EncryptFromStringEncryptionSize($value, $ECSize);

                  case RsaEncryptionType::Auto:
                        return $this->RSA_EncryptFromStringEncryptionSize($value, EncryptionSize::encAuto);

                  case RsaEncryptionType::Default:
                        //return $this->Private_Encrypt($value, true, Firstruner.Security.Keys.PublicKey_1024);
                        throw new NotImplementedException("Fonction non disponible dans ce langage");

                  case RsaEncryptionType::Init:
                        return $this->Private_EncryptRSAByPublicKey($value, true, $this->init_publicKey);

                  case RsaEncryptionType::User:
                        if (is_null($_publicKey))
                              throw new \Exception("Clé non définie");

                        return $this->Private_EncryptRSAByPublicKey($value, true, $_publicKey);

                  default:
                        throw new ArgumentOutOfRangeException("$RSA_EncryptionType", $RSA_EncryptionType, null);
            }
      }

      // public function Encrypt(string $value, int $key, EncryptMode mode) : CryptedValue
      // {
      //       return $this->Private_Encrypt(value, key, mode);
      // }

      private function Encrypt_Cert(X509Certificate2 $cert, string $value): CryptedValue
      {
            return $this->X509_Encrypt($cert, $value);
      }

      #endregion EncryptCallMethods

      #region Cryptages

      #region TripleDES

      //public static readonly string Key = ConfigurationManager.AppSettings["Encryption_Key"];
      //public static readonly Encoding Encoder = Encoding.UTF8;

      //public static string TripleDesEncrypt(string plainText)
      //{
      //    var des = CreateDes(Key);
      //    var ct = des.CreateEncryptor();
      //    var input = Encoding.UTF8.GetBytes(plainText);
      //    var output = ct.TransformFinalBlock(input, 0, input.Length);
      //    return Convert.ToBase64String(output);
      //}

      //public static string TripleDesDecrypt(string cypherText)
      //{
      //    var des = CreateDes(Key);
      //    var ct = des.CreateDecryptor();
      //    var input = Convert.FromBase64String(cypherText);
      //    var output = ct.TransformFinalBlock(input, 0, input.Length);
      //    return Encoding.UTF8.GetString(output);
      //}

      //public static TripleDES CreateDes(string key)
      //{
      //    MD5 md5 = new MD5CryptoServiceProvider();
      //    TripleDES des = new TripleDESCryptoServiceProvider();
      //    var desKey = md5.ComputeHash(Encoding.UTF8.GetBytes(key));
      //    des.Key = desKey;
      //    des.IV = new byte[des.BlockSize / 8];
      //    des.Padding = PaddingMode.PKCS7;
      //    des.Mode = CipherMode.ECB;
      //    return des;
      //}

      //private string TDES_Encrypt(string toEncrypt, string passPhrase, bool useHashing)
      //{
      //    byte[] keyArray;
      //    byte[] toEncryptArray = UTF8Encoding.UTF8.GetBytes(toEncrypt);

      //    string key = passPhrase;

      //    //System.Windows.Forms.MessageBox.Show(key);
      //    //If hashing use get hashcode regards to your key
      //    if (useHashing)
      //    {
      //        MD5CryptoServiceProvider hashmd5 = new MD5CryptoServiceProvider();
      //        keyArray = hashmd5.ComputeHash(UTF8Encoding.UTF8.GetBytes(key));
      //        //Always release the resources and flush data
      //        // of the Cryptographic service provide. Best Practice

      //        hashmd5.Clear();
      //    }
      //    else
      //        keyArray = UTF8Encoding.UTF8.GetBytes(key);

      //    TripleDESCryptoServiceProvider tdes = new TripleDESCryptoServiceProvider();
      //    //set the secret key for the tripleDES algorithm
      //    tdes.Key = keyArray;
      //    //mode of operation. there are other 4 modes.
      //    //We choose ECB(Electronic code Book)
      //    tdes.Mode = CipherMode.ECB;
      //    //padding mode(if any extra byte added)

      //    tdes.Padding = PaddingMode.PKCS7;

      //    ICryptoTransform cTransform = tdes.CreateEncryptor();
      //    //transform the specified region of bytes array to resultArray
      //    byte[] resultArray =
      //      cTransform.TransformFinalBlock(toEncryptArray, 0,
      //      toEncryptArray.Length);
      //    //Release resources held by TripleDes Encryptor
      //    tdes.Clear();
      //    //Return the encrypted data into unreadable string format
      //    return Convert.ToBase64String(resultArray, 0, resultArray.Length);
      //}

      //public string Decrypt(string cipherString, string passPhrase, bool useHashing)
      //{
      //    byte[] keyArray;
      //    //get the byte code of the string

      //    byte[] toEncryptArray = Convert.FromBase64String(cipherString);

      //    //Get your key from config file to open the lock!
      //    string key = passPhrase

      //    if (useHashing)
      //    {
      //        //if hashing was used get the hash code with regards to your key
      //        MD5CryptoServiceProvider hashmd5 = new MD5CryptoServiceProvider();
      //        keyArray = hashmd5.ComputeHash(UTF8Encoding.UTF8.GetBytes(key));
      //        //release any resource held by the MD5CryptoServiceProvider

      //        hashmd5.Clear();
      //    }
      //    else
      //    {
      //        //if hashing was not implemented get the byte code of the key
      //        keyArray = UTF8Encoding.UTF8.GetBytes(key);
      //    }

      //    TripleDESCryptoServiceProvider tdes = new TripleDESCryptoServiceProvider();
      //    //set the secret key for the tripleDES algorithm
      //    tdes.Key = keyArray;
      //    //mode of operation. there are other 4 modes.
      //    //We choose ECB(Electronic code Book)

      //    tdes.Mode = CipherMode.ECB;
      //    //padding mode(if any extra byte added)
      //    tdes.Padding = PaddingMode.PKCS7;

      //    ICryptoTransform cTransform = tdes.CreateDecryptor();
      //    byte[] resultArray = cTransform.TransformFinalBlock(
      //                         toEncryptArray, 0, toEncryptArray.Length);
      //    //Release resources held by TripleDes Encryptor
      //    tdes.Clear();
      //    //return the Clear decrypted TEXT
      //    return UTF8Encoding.UTF8.GetString(resultArray);
      //}

      #endregion TripleDES

      #region MD5

      private function MD5_LoadKeysFile(string $path): void
      {
            $sr = new StreamReader($path);
            $content = $sr->ReadToEnd();
            $sr->Close();

            $this->CryptoKeys = explode('\n', str_replace("\r", _string::EmptyString, $content));
      }

      private function MD5_Encrypt(string $toEncrypt, int $IDCrypto, bool $useHashing): string
      {
            $buffer = [];
            $bytes = UTF8::GetBytes($toEncrypt);
            //AppSettingsReader reader = new AppSettingsReader();
            //$s = $this->CryptoKeys[$IDCrypto];

            // if ($useHashing) {
            //       $provider = new MD5CryptoServiceProvider();
            //       $buffer = $provider->ComputeHash($s);
            //       $provider->Clear();
            // } else {
            //       $buffer = UTF8::GetBytes($s);
            // }
            $buffer = hex2bin($this->CryptoKeys[$IDCrypto]);

            try {
                  $provider2 = new TripleDESCryptoServiceProvider();
                  $provider2->Key($buffer);
                  $provider2->Mode(CipherMode::ECB);
                  $provider2->Padding(PaddingMode::PKCS7);
            } catch (\Exception $e) {
                  die($e->getMessage());
            }

            $inBase64 = System_String::ToBase64((string)$provider2->CreateEncryptor());
            $provider2->Clear();
            return $inBase64;
      }

      private function MD5_Decrypt(string $cipherString, int $IDCrypto, bool $useHashing): string
      {
            $buffer = [];
            $inputBuffer = System_String::FromBase64($cipherString);
            //AppSettingsReader reader = new AppSettingsReader();
            $s = $this->CryptoKeys[$IDCrypto];

            if ($useHashing) {
                  $provider = new MD5CryptoServiceProvider();
                  $buffer = $provider->ComputeHash($s);
                  $provider->Clear();
            } else {
                  $buffer = UTF8::GetBytes($s);
            }

            $provider2 = new TripleDESCryptoServiceProvider();
            $provider2->Key($buffer);
            $provider2->Mode(CipherMode::ECB);
            $provider2->Padding(PaddingMode::PKCS7);

            $bytes = $provider2->CreateDecryptorByKeyIV()->Transform($inputBuffer);
            $provider2->Clear();
            return UTF8::GetString(System_String::ToByteArray($bytes));
      }

      #endregion MD5

      #region RSA

      private function RSA_DecryptFromString(string $data): string
      {
            //$dataArray = explode($data, ',');
            $dataByte = System_String::ToByteArray($data);

            $decryptedByte = $this->rsa->Decrypt($dataByte, false);
            return (new UnicodeEncoding())->GetString($decryptedByte);
      }

      private function RSA_DecryptFromByteArray(array $encryptBytes, string $privateKey): string
      {
            $cspParams = new CspParameters();
            $cspParams->ProviderType(1);
            $rsaProvider = new RSACryptoServiceProvider(
                  $this->openSSLConfigFile,
                  $cspParams::KeySize);

            $rsaProvider->ImportCspBlob(System_String::FromBase64($privateKey));

            $plainBytes = $rsaProvider->Decrypt($encryptBytes, false);

            $plainText = UTF8::GetString(System_String::ToByteArray($plainBytes));

            return $plainText;
      }

      private function RSA_EncryptFromString(string $data): string
      {
            $dataToEncrypt = (new UnicodeEncoding())->GetBytes($data);
            $encryptedByteArray = System_String::ToByteArray($this->rsa->Encrypt($dataToEncrypt, false));
            $length = count($encryptedByteArray);
            $item = 0;
            $sb = new StringBuilder();

            foreach ($encryptedByteArray as $x) {
                  $item++;
                  $sb->Append($x);

                  if ($item < $length)
                        $sb->Append(",");
            }

            return $sb->ToString();
      }

      private function RSA_EncryptFromStringEncryptionSize(string $data, int $size): CryptedValue
      {
            $dataSize = count(UTF8::GetBytes($data));
            $maxSize = (((int)$size - 384) / 8) + 37;

            $k = _string::EmptyString;

            if ($size == EncryptionSize::encAuto) {
                  $notEnouth = true;
                  $valK = 512;

                  do {
                        $size = $valK;
                        $maxSize = (((int)$size - 384) / 8) + 37;

                        if ($maxSize < $dataSize) {
                              $valK *= 2;
                        } else {
                              $notEnouth = false;
                              break;
                        }

                        if (($valK > 4096) && $notEnouth)
                              throw new \Exception("Taille de cryptage indisponible");
                  } while ($notEnouth);
            } else {
                  if ($maxSize < $dataSize)
                        throw new \Exception("Taille de cryptage invalide");
            }

            switch ($size) {
                  // case EncryptionSize::enc512:
                  //       k = Firstruner.Security.Keys.PublicKey_512;
                  //       break;

                  // case EncryptionSize::enc1024:
                  //       k = Firstruner.Security.Keys.PublicKey_1024;
                  //       break;

                  // case EncryptionSize::enc2048:
                  //       k = Firstruner.Security.Keys.PublicKey_2048;
                  //       break;

                  // case EncryptionSize::enc4096:
                  //       k = Firstruner.Security.Keys.PublicKey_4096;
                  //       break;
                  default:
                        throw new NotImplementedException("Non disponible dans ce langage");
            }

            $cv = $this->RSA_EncryptFromStringPublicKey($data, $k);
            $cv->ID_Key = (int)$size;
            return $cv;
      }

      private function RSA_EncryptFromStringPublicKey(string $data, string $publicKey): CryptedValue
      {
            $cspParams = new CspParameters();
            $cspParams->ProviderType(1);
            $rsaProvider = new RSACryptoServiceProvider($cspParams::KeySize);

            $rsaProvider->ImportCspBlob(System_String::FromBase64($publicKey));

            $plainBytes = UTF8::GetBytes($data);
            $encryptedBytes = $rsaProvider->Encrypt($plainBytes, false);

            return new CryptedValue($encryptedBytes);
      }

      private function RSA_loadKeyFromFile(string $filename): void
      {
            $sr = new StreamReader($filename);
            $value = $sr->ReadToEnd();
            $sr->Close();

            $this->rsa->FromXmlString($value);
      }

      #endregion RSA

      #region SHA1/SHA256/SHA512

      private function SHA_Encrypt(int $mode, string $value): CryptedValue
      {
            $algorithm = new SHA1Managed();
            switch ($mode) {
                  case EncryptionMode::SHA1_Value:
                        $algorithm = new SHA1Managed();
                        break;

                  case EncryptionMode::SHA256_Value:
                        $algorithm = new SHA256Managed();
                        break;

                  case EncryptionMode::SHA512_Value:
                        $algorithm = new SHA512Managed();
                        break;
            }

            $bytes = UTF8::GetBytes($value);
            return new CryptedValue(System_String::ToBase64($algorithm->ComputeHash(System_String::FromByteArray($bytes))), 0);
      }

      #endregion SHA1/SHA256/SHA512

      #region X509

      private function X509_LoadKeyFromFile(string $path, SecureString $password): void
      {
            $this->certX509 = new X509Certificate2($path, $password->ToString());
      }

      private function X509_EncryptFromString(string $value): array
      {
            $publicKey = $this->certX509->PublicKey()->Key();
            return $publicKey->Encrypt((new ASCIIEncoding())->GetBytes($value), false);
      }

      private function X509_DecryptFromArray(array $datas): string
      {
            if (!$this->certX509->HasPrivateKey())
                  throw new \Exception("Not privateKey");

            $privateKey = $this->certX509->PrivateKey();
            return UTF8::GetString($privateKey->Decrypt($datas, false));
      }

      private function X509_GetSignature(string $value, int $mode): array
      {
            if (!$this->certX509->HasPrivateKey())
                  throw new \Exception("Not privateKey");

            $privateKey = $this->certX509->PrivateKey();
            return $privateKey->SignData((new ASCIIEncoding())->GetBytes($value), strval($mode));
      }

      private function X509_CheckSignature(string $value, int $mode, array $sign): bool
      {
            $publicKey = $this->certX509->PublicKey()->Key;
            return $publicKey->VerifyData((new ASCIIEncoding())->GetBytes($value), strval($mode), $sign);
      }

      private function X509_Encrypt(X509Certificate2 $x509, string $value): CryptedValue
      {
            if (is_null($x509) || System_String::IsNullOrEmpty($value))
                  throw new \Exception("A x509 certificate and string for encryption must be provided");

            $rsa = $x509->PublicKey()->Key;
            $bytestoEncrypt = ASCII::GetBytes($value);
            $encryptedBytes = $rsa->Encrypt($bytestoEncrypt, false);
            return new CryptedValue(System_String::ToBase64($encryptedBytes));
      }

      private function X509_Decrypt(X509Certificate2 $x509, string $value): string
      {
            if (is_null($x509) || System_String::IsNullOrEmpty($value))
                  throw new \Exception("A x509 certificate and string for decryption must be provided");

            if (!$x509->HasPrivateKey())
                  throw new \Exception("x509 certicate does not contain a private key for decryption");

            $rsa = $x509->PrivateKey();
            $bytestodecrypt = System_String::FromBase64($value);
            $plainbytes = $rsa->Decrypt($bytestodecrypt, false);
            $enc = new ASCIIEncoding();
            return $enc->GetString($plainbytes);
      }

      #endregion X509

      #region AES

      /// <param name="size">Maximum size : 256</param>
      public function AES_GenerateNewKeys(int $size = 256): void
      {
            $this->Private_AES_GenerateNewKeys($size);
      }

      private function AES_LoadKeysFromFile(string $pkFile, string $IVFile): void
      {
            $this->myRijndael->Key(File::ReadAllText($pkFile));
            $this->myRijndael->IV(File::ReadAllText($IVFile));
      }

      private function AES_Encrypt(string $plainText): array
      {
            // Check arguments.
            if (is_null($plainText) || strlen($plainText) <= 0)
                  throw new ArgumentNullException("plainText");

            $Key = $this->myRijndael->Key();
            $IV = $this->myRijndael->IV();

            $encrypted = [];
            // Create an RijndaelManaged object
            // with the specified key and IV.
            $rijAlg = new RijndaelManaged();
            $rijAlg->Key($Key);
            $rijAlg->IV($IV);

            // Create a decryptor to perform the stream transform.
            $encryptor = $rijAlg->CreateEncryptor($rijAlg->Key(), $rijAlg->IV());

            // Create the streams used for encryption.
            $msEncrypt = new MemoryStream();
            $csEncrypt = new CryptoStream(
                  EncryptionMode::AES_256_ProtocolName,
                  $rijAlg->Key(),
                  $rijAlg->IV(),
                  CryptoStreamMode::Write);
            
            $csEncrypt->Write($plainText);

            // récupère les bytes chiffrés
            $encryptedRaw = $csEncrypt->GetEncryptedBytes();

            // si ton API attend un tableau de bytes:
            $encrypted = array_values(unpack('C*', $encryptedRaw));

            $csEncrypt->Close();

            unset($csEncrypt);
            unset($swEncrypt);

            return $encrypted;
      }

      private function AES_Decrypt(array $cipherText): string
      {
            // Check arguments.
            if (is_null($cipherText) || strlen(System_String::FromByteArray($cipherText)) <= 0)
                  throw new ArgumentNullException("cipherText");

            $Key = $this->myRijndael->Key();
            $IV = $this->myRijndael->IV();

            // Declare the string used to hold
            // the decrypted text.
            $plaintext = null;

            // Create an RijndaelManaged object
            // with the specified key and IV.
            $rijAlg = new RijndaelManaged();
            $rijAlg->Key($Key);
            $rijAlg->IV($IV);

            // Create a decrytor to perform the stream transform.
            $decryptor = $rijAlg->CreateDecryptor($rijAlg->Key(), $rijAlg->IV());

            // Create the streams used for decryption.
            $srDecrypt = new CryptoStream(
                  System_String::FromByteArray($cipherText),
                  $decryptor->Key(),
                  $decryptor->IV(),
                  CryptoStreamMode::Read
            );

            // Read the decrypted bytes from the decrypting stream
            // and place them in a string.
            $plaintext = $srDecrypt->Read();

            unset($rijAlg);
            unset($decryptor);
            unset($srDecrypt);

            return $plaintext;
      }

      #endregion AES

      #region Caesar

      // Décalage d'un caractère
      private function shiftChar(string $char, int $shift): string
      {
            $isLower = ctype_lower($char);  // Vérifie si le caractère est une minuscule
            $base = $isLower ? ord('a') : ord('A');  // Définit la base (soit 'a' soit 'A')

            $newChar = chr(($ord = ord($char)) - $base + $shift) % 26 + $base;  // Applique le décalage

            return $newChar;
      }

      private function Caesar_EncryptDecrypt(string $value, int $decal): CryptedValue
      {
            $result = '';

            foreach (str_split($value) as $char) {
                  if (ctype_alpha($char)) {
                        $shiftedChar = $this->shiftChar($char, $decal);
                        $result .= $shiftedChar;
                  } else {
                        $result .= $char; // On conserve les autres caractères tels quels (espace, ponctuation)
                  }
            }

            return new CryptedValue($result, $decal);
      }

      #endregion Caesar

      #region PlayFair
      // Chiffrement d'un texte avec la méthode Playfair
      private function PlayFair_Encrypt(string $text, string $key): string
      {
            $keySquare = $this->createKeySquare($key);
            $text = $this->prepareText($text);
            $pairs = $this->createPairs($text, $keySquare);
            $ciphertext = '';

            foreach ($pairs as $pair) {
                  $row1 = $pair[0]['row'];
                  $col1 = $pair[0]['col'];
                  $row2 = $pair[1]['row'];
                  $col2 = $pair[1]['col'];

                  if ($row1 == $row2) {
                        // Même ligne : décaler les colonnes
                        $ciphertext .= $keySquare[$row1][$this->mod($col1 + 1)] . $keySquare[$row2][$this->mod($col2 + 1)];
                  } elseif ($col1 == $col2) {
                        // Même colonne : décaler les lignes
                        $ciphertext .= $keySquare[$this->mod($row1 + 1)][$col1] . $keySquare[$this->mod($row2 + 1)][$col2];
                  } else {
                        // Différents lignes et colonnes : former un rectangle
                        $ciphertext .= $keySquare[$row1][$col2] . $keySquare[$row2][$col1];
                  }
            }

            return $ciphertext;
      }

      // Déchiffrement d'un texte avec la méthode Playfair
      public function PlayFair_Decrypt(string $text, string $key): string
      {
            $keySquare = $this->createKeySquare($key);
            $pairs = $this->createPairs($text, $keySquare);
            $plaintext = '';

            foreach ($pairs as $pair) {
                  $row1 = $pair[0]['row'];
                  $col1 = $pair[0]['col'];
                  $row2 = $pair[1]['row'];
                  $col2 = $pair[1]['col'];

                  if ($row1 == $row2) {
                        // Même ligne : décaler les colonnes
                        $plaintext .= $keySquare[$row1][$this->mod($col1 - 1)] . $keySquare[$row2][$this->mod($col2 - 1)];
                  } elseif ($col1 == $col2) {
                        // Même colonne : décaler les lignes
                        $plaintext .= $keySquare[$this->mod($row1 - 1)][$col1] . $keySquare[$this->mod($row2 - 1)][$col2];
                  } else {
                        // Différents lignes et colonnes : former un rectangle
                        $plaintext .= $keySquare[$row1][$col2] . $keySquare[$row2][$col1];
                  }
            }

            return $plaintext;
      }

      // Préparer le texte pour le chiffrement ou déchiffrement (remplacer les 'J' par 'I', ajouter des X si nécessaire)
      private function prepareText(string $text): string
      {
            $text = strtoupper($text);
            $text = str_replace('J', 'I', $text); // J devient I
            $text = preg_replace('/[^A-Z]/', '', $text); // Supprimer les caractères non alphabétiques

            // Ajouter un 'X' entre les lettres identiques
            $text = preg_replace('/([A-Z])\1/', '$1X$1', $text);

            // Si le texte est impair, ajouter un 'X' à la fin
            if (strlen($text) % 2 != 0) {
                  $text .= 'X';
            }

            return $text;
      }

      // Créer la grille 5x5 à partir de la clé
      private function createKeySquare(string $key): array
      {
            $key = strtoupper($key);
            $key = str_replace('J', 'I', $key); // J devient I
            $key = preg_replace('/[^A-Z]/', '', $key); // Supprimer les caractères non alphabétiques

            $keySquare = [];
            $used = [];
            $alphabet = 'ABCDEFGHIKLMNOPQRSTUVWXYZ'; // Le 'J' est exclu

            // Ajouter les lettres de la clé dans la grille
            foreach (str_split($key) as $char) {
                  if (!isset($used[$char])) {
                        $used[$char] = true;
                        $keySquare[] = $char;
                  }
            }

            // Ajouter les lettres restantes de l'alphabet
            foreach (str_split($alphabet) as $char) {
                  if (!isset($used[$char])) {
                        $used[$char] = true;
                        $keySquare[] = $char;
                  }
            }

            // Organiser la grille 5x5
            $grid = [];
            for ($i = 0; $i < 5; $i++) {
                  $grid[] = array_slice($keySquare, $i * 5, 5);
            }

            return $grid;
      }

      // Trouver les coordonnées d'un caractère dans la grille
      private function findPosition(string $char, array $keySquare): array|null
      {
            for ($row = 0; $row < 5; $row++) {
                  for ($col = 0; $col < 5; $col++) {
                        if ($keySquare[$row][$col] === $char) {
                              return ['row' => $row, 'col' => $col];
                        }
                  }
            }

            return null;
      }

      // Créer des paires de caractères pour le texte
      private function createPairs(string $text, array $keySquare): array
      {
            $pairs = [];
            $text = str_split($text, 2);

            foreach ($text as $pair) {
                  $pairs[] = [
                        $this->findPosition($pair[0], $keySquare),
                        $this->findPosition($pair[1], $keySquare)
                  ];
            }

            return $pairs;
      }

      // Modulo pour gérer les indices circulaires de la grille
      private function mod(int $n): int
      {
            return ($n + 5) % 5;
      }
      #endregion

      #endregion Cryptages
}
