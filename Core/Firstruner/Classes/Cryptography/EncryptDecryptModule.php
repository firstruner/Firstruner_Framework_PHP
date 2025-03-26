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

namespace Firstruner\Cryptography;

use System\Default\_array;
use System\Default\_string;
use System\Random;
use System\Security\Cryptography\Encryption;
use System\Security\Cryptography\EncryptionMode;
use System\Security\Cryptography\RSACryptoServiceProvider;
use System\Security\Cryptography\X509Certificates\X509Certificate2;
use System\Security\SecureString;
use System\Text\Encoding;

final class EncryptDecryptModule
{
      // Fields
      private bool $_LockKey;
      private array $CryptoKeys = array(0);
      private int $k = -1;
      private RSACryptoServiceProvider $rsa;
      //private UnicodeEncoding _encoder = new UnicodeEncoding();
      private RijndaelManaged $myRijndael;
      private X509Certificate2 $certX509;
      private string $init_publicKey;
      private string $init_privateKey;

      // Properties
      public function LockKey(bool $value) : void
      {
            $this->_LockKey = $value;
            if (!$value)
                  $k = -1;
      }

      public function __construct()
      {
            $this->rsa = new RSACryptoServiceProvider();
            $this->myRijndael = new RijndaelManaged();

            $args = func_get_args();
            $numArgs = func_num_args();

            if ($numArgs == 0)
                  $this->init_ByArray([]);

            if (($numArgs == 1) && (gettype($args[0]) == _array::ClassName))
                  $this->init_ByArray($args[0]);

            if (($numArgs == 2)
                  && (gettype($args[0]) == _string::ClassName)
                  && (gettype($args[1]) == _string::ClassName))
                  $this->init_ByKeys($args[0], $args[1]);

            if (($numArgs == 3)
                  && (gettype($args[0]) == _string::ClassName)
                  && (gettype($args[1]) == _string::ClassName)
                  && (gettype($args[2]) == _string::ClassName))
                  $this->init_ByFile($args[0], $args[1], $args[2]);
      }

      // Constructor / Deconstructor
      public function init_ByKeys(?string $PublicKey = null, ?string $PrivateKey = null) : void
      {
            $this->init_publicKey = $PublicKey;
            $this->init_privateKey = $PrivateKey;
      }

      #region Private Methodes

      private function init_ByArray(array $keys_list)
      {
            $this->CryptoKeys = $keys_list;
      }

      private function init_ByFile(string $keyFile, int $mode, SecureString $password) : void
      {
            switch ($mode)
            {
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
                              explode($keyFile, '|')[1]);
                        break;

                  default:
                        throw new \Exception("Not disponible");
            }
      }

      private function Private_Decrypt(CryptedValue $CValue, bool $RSAMode, string $privateKey) : string
      {
            if (!$RSAMode)
                  return Private_Decrypt($CValue, EncryptionMode::RSA_Value);

            return RSA_Decrypt($CValue->ByteValue, $privateKey);
      }

      private function Private_Decrypt(CryptedValue $CValue, int $mode, string $specKey = _string::EmptyString) : string|null
      {
            $index = $CValue->ID_Key;

            try
            {
                  switch ($mode)
                  {
                        case EncryptionMode::MD5:
                              return $this->MD5_Decrypt($CValue->Value, $index, true);

                        case EncryptionMode::SHA1:
                        case EncryptionMode::SHA256:
                        case EncryptionMode::SHA512:
                              throw new \Exception("No SHA decryption possible"); //Cannot Decrypt SHA value

                        case EncryptionMode::RSA:
                              return $this->RSA_Decrypt($CValue->Value());
                        case EncryptionMode::X509:
                              return $this->X509_Decrypt($CValue->ByteValue());
                        case EncryptionMode::AES:
                              return $this->AES_Decrypt($CValue->ByteValue());

                        case EncryptionMode::Caesar:
                              return $this->Caesar_EncryptDecrypt($CValue->Value, -$CValue->ID_Key)->Value;
                        case EncryptionMode::PlayFair:
                              return $this->PlayFair_Decrypt($CValue->Value, $specKey ?? $this->CryptoKeys[0]);
                  }

                  return null;
            }
            catch (\Exception $e)
            {
                  //Console.WriteLine(e.Message);
                  return null;
            }
      }

      private function Private_EncryptRSAByPublicKey(string $value, bool $RSAMode, string $publicKey) : CryptedValue
      {
            if (!$RSAMode)
                  return $this->Private_EncryptMain($value, EncryptionMode::RSA_Value);

            return RSA_Encrypt(value, publicKey);
      }

      private function Private_EncryptByKey(string $value, int $key, int $mode) : CryptedValue
      {
            $this->_LockKey = true;
            $this->k = $key;
            $value2 = $this->Encrypt($value, $mode);
            $this->_LockKey = false;
            return $value2;
      }

      private function Private_EncryptMain(string $value, int $mode, string $specKey = _string::EmptyString) : ?CryptedValue
      {
            $i = $this->k;

            if ($mode != EncryptionMode::Caesar_Value)
            {
                  $i = (new Random())->Next(0, count($this->CryptoKeys) - 1);

                  if ($this->_LockKey && ($this->k >= 0))
                        $i = $this->k;

                  if ($this->_LockKey)
                        $this->k = $i;
            }

            try
            {
                  switch (mode)
                  {
                        case EncryptionMode::AES_Value:
                              return new CryptedValue($this->AES_Encrypt($value));

                        case EncryptionMode::MD5_Value:
                              return new CryptedValue($this->MD5_Encrypt($value, $i, true), $i);

                        case EncryptionMode::RSA_Value:
                              return new CryptedValue($this->RSA_Encrypt($value), 0);

                        case EncryptionMode::X509_Value:
                              return new CryptedValue($this->X509_Decrypt(Encoding.Unicode.GetBytes($value)), 0);

                        case EncryptionMode::SHA1_Value:
                        case EncryptionMode::SHA256_Value:
                        case EncryptionMode::SHA512_Value:
                              return $this->SHA_Encrypt($mode, $value);

                        case EncryptionMode::Caesar_Value:
                              return $this->Caesar_EncryptDecrypt($value, $i);

                        case EncryptionMode::PlayFair_Value:
                              return new CryptedValue($this->PlayFair_Encrypt($value, $specKey ?? $this->CryptoKeys[0]));
                  }
            }
            catch (\Exception $e)
            {
                  //Console.WriteLine(e.Message);
                  return null;
            }

            return null;
      }

      private function Private_AES_GenerateNewKeys(int $size) : void
      {
            $this->myRijndael->KeySize = (size > 256 ? 256 : size);
            $this->myRijndael->GenerateKey();
            $this->myRijndael->GenerateIV();
      }

      #endregion Private Methodes

      function __destruct()
      {
            $this->k = -1;
            $this->_LockKey = false;
            $this->CryptoKeys = null;
            $this->init_privateKey = null;
            $this->init_publicKey = null;
            $this->certX509 = null;
            $this->myRijndael = null;
            //_encoder = null;
            $this->rsa = null;
      }

      // Methods

      #region DecryptCallMethods

      /// <summary>
      /// Décryptage de valeur
      /// </summary>
      /// <param name="mode">SHA non disponible</param>
      /// <returns></returns>
      public function Decrypt(CryptedValue $CValue, int $mode) : string
      {
            return $this->Private_Decrypt(CValue, mode);
      }

      public function Decrypt(CryptedValue $CValue, bool $RSAkey, string $privateKey) : string
      {
            if (!$RSAkey)
                  return $this->Decrypt($CValue, EncryptionMode::RSA_Value);

            return $this->Private_Decrypt($CValue, $RSAkey, $privateKey);
      }

      public function Decrypt(string $CValue, EncryptMode $mode) : string
      {
            return $this->Decrypt(new CryptedValue($CValue), mode: $mode);
      }

      public function Decrypt3(string $CValue, EncryptMode $mode, int $keyCode) : string
      {
            return Decrypt(new CryptedValue($CValue, $keyCode), mode: $mode);
      }

      public function Decrypt2(string $CValue, Tuple $MethodKey) : string
      {
            if ($MethodKey->Item1 == EncryptionMode::PlayFair_Value)
                  return $this->PlayFair_Decrypt($CValue, $MethodKey->Item2);

            return Decrypt($CValue, $MethodKey->Item1);
      }

      /// <summary>
      ///
      /// </summary>
      /// <param name="CValue">Valeur à crypter</param>
      /// <param name="RSA_EncryptionType">Type d'encryptage RSA<remarks>
      /// Init : Utilise la clé fournie à l'instanciation,
      /// User : Utilise la clé fournie dans la méthode,
      /// Auto : Non utilisable,
      /// Default : Non utilisate,
      /// PreCalculate : Utilise une clé standard
      /// </remarks></param>
      /// <param name="_privateKey">Clé public fourni par l'utilisateur</param>
      /// <returns></returns>
      public function Decrypt(string $CValue, RsaEncryptionType $RSA_EncryptionType, string $_privateKey = null) : string
      {
            switch ($RSA_EncryptionType)
            {
                  case RsaEncryptionType.Init:
                        return Decrypt(new CryptedValue(CValue), true, init_privateKey);

                  case RsaEncryptionType.User:
                        if (_privateKey.IsNull())
                        throw new Exception("Clé indiponible");

                        return Decrypt(new CryptedValue(CValue), true, _privateKey);

                  case RsaEncryptionType.Auto:
                  case RsaEncryptionType.PreCalculate:
                  case RsaEncryptionType.Default:
                        throw new Exception("Non disponible");
                  default:
                        throw new ArgumentOutOfRangeException(nameof(RSA_EncryptionType), RSA_EncryptionType, null);
            }
      }

      public string Decrypt(X509Certificate2 cert, string value)
      {
            if (!cert.HasPrivateKey)
                  throw new PrivateKeyUnavailableException();

            try
            {
                  ObjectExtension.IsNull(cert.PrivateKey);
            }
            catch (Exception e)
            {
                  if (Marshal.GetHRForException(e) == -2146893802) // Clé indisponible car non admin
                        throw new NeedAdministratorAccesException(e.Message);
            }

            return X509_Decrypt(cert, value);
      }

      #endregion DecryptCallMethods

      #region EncryptCallMethods

      public CryptedValue Encrypt(string value, EncryptMode mode)
      {
      return Private_Encrypt(value, mode);
      }

      public CryptedValue Encrypt3(string value, EncryptMode mode, int keyCode)
      {
      return Private_Encrypt(value, keyCode, mode);
      }

      public CryptedValue Encrypt2(string CValue, Tuple<EncryptMode, string> MethodKey)
      {
      if (MethodKey.Item1 == EncryptionMode::PlayFair)
            return new CryptedValue(PlayFair_Encrypt(CValue, MethodKey.Item2));

      return Encrypt(CValue, MethodKey.Item1);
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
      public CryptedValue Encrypt(string value, RsaEncryptionType RSA_EncryptionType,
      string _publicKey = null,
      EncryptionSize ECSize = EncryptionSize.encAuto)
      {
      switch (RSA_EncryptionType)
      {
            case RsaEncryptionType.PreCalculate:
                  return RSA_Encrypt(value, ECSize);

            case RsaEncryptionType.Auto:
                  return RSA_Encrypt(value, EncryptionSize.encAuto);

            case RsaEncryptionType.Default:
                  return Private_Encrypt(value, true, Firstruner.Security.Keys.PublicKey_1024);

            case RsaEncryptionType.Init:
                  return Private_Encrypt(value, true, init_publicKey);

            case RsaEncryptionType.User:
                  if (_publicKey.IsNull())
                  throw new Exception("Clé non définie");

                  return Private_Encrypt(value, true, _publicKey);

            default:
                  throw new ArgumentOutOfRangeException(nameof(RSA_EncryptionType), RSA_EncryptionType, null);
      }
      }

      public CryptedValue Encrypt(string value, int key, EncryptMode mode)
      {
      return Private_Encrypt(value, key, mode);
      }

      public CryptedValue Encrypt(X509Certificate2 cert, string value)
      {
      return X509_Encrypt(cert, value);
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

      private void MD5_LoadKeysFile(string path)
      {
      System.IO.StreamReader sr = new StreamReader(path);
      string content = sr.ReadToEnd();
      sr.Close();

      CryptoKeys = content.Replace("\r", string.Empty).Split('\n');
      }

      private string MD5_Encrypt(string toEncrypt, int IDCrypto, bool useHashing)
      {
      byte[] buffer;
      byte[] bytes = Encoding.UTF8.GetBytes(toEncrypt);
      //AppSettingsReader reader = new AppSettingsReader();
      string s = CryptoKeys[IDCrypto];

      if (useHashing)
      {
            MD5CryptoServiceProvider provider = new MD5CryptoServiceProvider();
            buffer = provider.ComputeHash(Encoding.UTF8.GetBytes(s));
            provider.Clear();
      }
      else
      {
            buffer = Encoding.UTF8.GetBytes(s);
      }

      TripleDESCryptoServiceProvider provider2 = new TripleDESCryptoServiceProvider
      {
            Key = buffer,
            Mode = CipherMode.ECB,
            Padding = PaddingMode.PKCS7
      };
      byte[] inArray = provider2.CreateEncryptor().TransformFinalBlock(bytes, 0, bytes.Length);
      provider2.Clear();
      return Convert.ToBase64String(inArray, 0, inArray.Length);
      }

      private string MD5_Decrypt(string cipherString, int IDCrypto, bool useHashing)
      {
      byte[] buffer;
      byte[] inputBuffer = Convert.FromBase64String(cipherString);
      //AppSettingsReader reader = new AppSettingsReader();
      string s = CryptoKeys[IDCrypto];
      if (useHashing)
      {
            MD5CryptoServiceProvider provider = new MD5CryptoServiceProvider();
            buffer = provider.ComputeHash(Encoding.UTF8.GetBytes(s));
            provider.Clear();
      }
      else
      {
            buffer = Encoding.UTF8.GetBytes(s);
      }
      TripleDESCryptoServiceProvider provider2 = new TripleDESCryptoServiceProvider
      {
            Key = buffer,
            Mode = CipherMode.ECB,
            Padding = PaddingMode.PKCS7
      };
      byte[] bytes = provider2.CreateDecryptor().TransformFinalBlock(inputBuffer, 0, inputBuffer.Length);
      provider2.Clear();
      return Encoding.UTF8.GetString(bytes);
      }

      #endregion MD5

      #region RSA

      private string RSA_Decrypt(string data)
      {
      var dataArray = data.Split(new char[] { ',' });
      byte[] dataByte = new byte[dataArray.Length];
      for (int i = 0; i < dataArray.Length; i++)
      {
            dataByte[i] = Convert.ToByte(dataArray[i]);
      }

      var decryptedByte = rsa.Decrypt(dataByte, false);
      return new UnicodeEncoding().GetString(decryptedByte);
      }

      private string RSA_Encrypt(string data)
      {
      var dataToEncrypt = new UnicodeEncoding().GetBytes(data);
      var encryptedByteArray = rsa.Encrypt(dataToEncrypt, false).ToArray();
      var length = encryptedByteArray.Count();
      var item = 0;
      var sb = new StringBuilder();
      foreach (var x in encryptedByteArray)
      {
            item++;
            sb.Append(x);

            if (item < length)
                  sb.Append(",");
      }

      return sb.ToString();
      }

      private CryptedValue RSA_Encrypt(string data, EncryptionSize size)
      {
      int dataSize = Encoding.UTF8.GetBytes(data).Length;
      int maxSize = (((int)size - 384) / 8) + 37;

      string k = string.Empty;

      if (size == EncryptionSize.encAuto)
      {
            bool notEnouth = true;
            int valK = 512;

            do
            {
                  size = (EncryptionSize)valK;
                  maxSize = (((int)size - 384) / 8) + 37;

                  if (maxSize < dataSize)
                  {
                  valK *= 2;
                  }
                  else
                  {
                  notEnouth = false;
                  break;
                  }

                  if ((valK > 4096) && notEnouth)
                  throw new Exception("Taille de cryptage indisponible");
            } while (notEnouth);
      }
      else
      {
            if (maxSize < dataSize)
                  throw new Exception("Taille de cryptage invalide");
      }

      switch (size)
      {
            case EncryptionSize.enc512:
                  k = Firstruner.Security.Keys.PublicKey_512;
                  break;

            case EncryptionSize.enc1024:
                  k = Firstruner.Security.Keys.PublicKey_1024;
                  break;

            case EncryptionSize.enc2048:
                  k = Firstruner.Security.Keys.PublicKey_2048;
                  break;

            case EncryptionSize.enc4096:
                  k = Firstruner.Security.Keys.PublicKey_4096;
                  break;
      }

      CryptedValue cv = RSA_Encrypt(data, k);
      cv.ID_Key = (int)size;
      return cv;
      }

      private CryptedValue RSA_Encrypt(string data, string publicKey)
      {
      CspParameters cspParams = new CspParameters { ProviderType = 1 };
      RSACryptoServiceProvider rsaProvider = new RSACryptoServiceProvider(cspParams);

      rsaProvider.ImportCspBlob(Convert.FromBase64String(publicKey));

      byte[] plainBytes = Encoding.UTF8.GetBytes(data);
      byte[] encryptedBytes = rsaProvider.Encrypt(plainBytes, false);

      return new CryptedValue(encryptedBytes);
      }

      private string RSA_Decrypt(byte[] encryptBytes, string privateKey)
      {
      CspParameters cspParams = new CspParameters { ProviderType = 1 };
      RSACryptoServiceProvider rsaProvider = new RSACryptoServiceProvider(cspParams);

      rsaProvider.ImportCspBlob(Convert.FromBase64String(privateKey));

      byte[] plainBytes = rsaProvider.Decrypt(encryptBytes, false);

      string plainText = Encoding.UTF8.GetString(plainBytes, 0, plainBytes.Length);

      return plainText;
      }

      private void RSA_loadKeyFromFile(string filename)
      {
      StreamReader sr = new StreamReader(filename);
      string value = sr.ReadToEnd();
      sr.Close();

      rsa.FromXmlString(value);
      }

      #endregion RSA

      #region SHA1/SHA256/SHA512

      private CryptedValue SHA_Encrypt(EncryptMode mode, string value)
      {
      HashAlgorithm algorithm = new SHA1Managed();
      switch (mode)
      {
            case EncryptionMode::SHA1:
                  algorithm = new SHA1Managed();
                  break;

            case EncryptionMode::SHA256:
                  algorithm = new SHA256Managed();
                  break;

            case EncryptionMode::SHA512:
                  algorithm = new SHA512Managed();
                  break;
      }

      byte[] bytes = Encoding.UTF8.GetBytes(value);
      return new CryptedValue(Convert.ToBase64String(algorithm.ComputeHash(bytes)), 0);
      }

      #endregion SHA1/SHA256/SHA512

      #region X509

      private void X509_LoadKeyFromFile(string path, SecureString password)
      {
      certX509 = new X509Certificate2(path, password.ToString());
      }

      private byte[] X509_Encrypt(string value)
      {
      RSACryptoServiceProvider publicKey = (RSACryptoServiceProvider)certX509.PublicKey.Key;
      return publicKey.Encrypt(new ASCIIEncoding().GetBytes(value), false);
      }

      private string X509_Decrypt(byte[] datas)
      {
      if (!certX509.HasPrivateKey)
            throw new Exception("Not privateKey");

      RSACryptoServiceProvider privateKey = (RSACryptoServiceProvider)certX509.PrivateKey;
      return Encoding.UTF8.GetString(privateKey.Decrypt(datas, false));
      }

      private byte[] X509_GetSignature(string value, EncryptMode mode)
      {
      if (!certX509.HasPrivateKey)
            throw new Exception("Not privateKey");

      RSACryptoServiceProvider privateKey = (RSACryptoServiceProvider)certX509.PrivateKey;
      return privateKey.SignData(new ASCIIEncoding().GetBytes(value), mode.ToString());
      }

      private bool X509_CheckSignature(string value, EncryptMode mode, byte[] sign)
      {
      RSACryptoServiceProvider publicKey = (RSACryptoServiceProvider)certX509.PublicKey.Key;
      return publicKey.VerifyData(new ASCIIEncoding().GetBytes(value), mode.ToString(), sign);
      }

      private CryptedValue X509_Encrypt(X509Certificate2 x509, string value)
      {
      if (x509.IsNull() || string.IsNullOrEmpty(value))
            throw new Exception("A x509 certificate and string for encryption must be provided");

      RSACryptoServiceProvider rsa = (RSACryptoServiceProvider)x509.PublicKey.Key;
      byte[] bytestoEncrypt = Encoding.ASCII.GetBytes(value);
      byte[] encryptedBytes = rsa.Encrypt(bytestoEncrypt, false);
      return new CryptedValue(Convert.ToBase64String(encryptedBytes));
      }

      private string X509_Decrypt(X509Certificate2 x509, string value)
      {
      if (x509.IsNull() || string.IsNullOrEmpty(value))
            throw new Exception("A x509 certificate and string for decryption must be provided");

      if (!x509.HasPrivateKey)
            throw new Exception("x509 certicate does not contain a private key for decryption");

      RSACryptoServiceProvider rsa = (RSACryptoServiceProvider)x509.PrivateKey;
      byte[] bytestodecrypt = Convert.FromBase64String(value);
      byte[] plainbytes = rsa.Decrypt(bytestodecrypt, false);
      System.Text.ASCIIEncoding enc = new System.Text.ASCIIEncoding();
      return enc.GetString(plainbytes);
      }

      #endregion X509

      #region AES

      /// <param name="size">Maximum size : 256</param>
      public void AES_GenerateNewKeys(int size = 256)
      {
      Private_AES_GenerateNewKeys(size);
      }

      private void AES_LoadKeysFromFile(string pkFile, string IVFile)
      {
      myRijndael.Key = File.ReadAllBytes(pkFile);
      myRijndael.IV = File.ReadAllBytes(IVFile);
      }

      private byte[] AES_Encrypt(string plainText)
      {
      // Check arguments.
      if (plainText.IsNull() || plainText.Length <= 0)
            throw new ArgumentNullException("plainText");

      byte[] Key = myRijndael.Key;
      byte[] IV = myRijndael.IV;

      byte[] encrypted;
      // Create an RijndaelManaged object
      // with the specified key and IV.
      using (RijndaelManaged rijAlg = new RijndaelManaged())
      {
            rijAlg.Key = Key;
            rijAlg.IV = IV;

            // Create a decryptor to perform the stream transform.
            ICryptoTransform encryptor = rijAlg.CreateEncryptor(rijAlg.Key, rijAlg.IV);

            // Create the streams used for encryption.
            using (MemoryStream msEncrypt = new MemoryStream())
            {
                  using (CryptoStream csEncrypt = new CryptoStream(msEncrypt, encryptor, CryptoStreamMode.Write))
                  {
                  using (StreamWriter swEncrypt = new StreamWriter(csEncrypt))
                  {
                        //Write all data to the stream.
                        swEncrypt.Write(plainText);
                  }
                  encrypted = msEncrypt.ToArray();
                  }
            }
      }

      // Return the encrypted bytes from the memory stream.
      return encrypted;
      }

      private string AES_Decrypt(byte[] cipherText)
      {
      // Check arguments.
      if (cipherText.IsNull() || cipherText.Length <= 0)
            throw new ArgumentNullException("cipherText");

      byte[] Key = myRijndael.Key, IV = myRijndael.IV;

      // Declare the string used to hold
      // the decrypted text.
      string plaintext = null;

      // Create an RijndaelManaged object
      // with the specified key and IV.
      using (RijndaelManaged rijAlg = new RijndaelManaged())
      {
            rijAlg.Key = Key;
            rijAlg.IV = IV;

            // Create a decrytor to perform the stream transform.
            ICryptoTransform decryptor = rijAlg.CreateDecryptor(rijAlg.Key, rijAlg.IV);

            // Create the streams used for decryption.
            using (
                  StreamReader srDecrypt =
                  new StreamReader(new CryptoStream(new MemoryStream(cipherText), decryptor, CryptoStreamMode.Read))
            )
            {
                  // Read the decrypted bytes from the decrypting stream
                  // and place them in a string.
                  plaintext = srDecrypt.ReadToEnd();
            }
      }

      return plaintext;
      }

      #endregion AES

      #region Caesar

      private CryptedValue Caesar_EncryptDecrypt(string value, int decal)
      {
      if (decal == 0)
            decal = new Random().Next(3, 26);

      int mod(int val, int m) => val % m + (val < 0 ? m : 0);

      char[] chars = value.ToCharArray();
      for (int i = 0; i < value.Length; i++)
      {
            int c = chars[i];
            if ('a' <= c && c <= 'z')
                  c = 'a' + mod(c - 'a' + decal, 26);
            else if ('A' <= c && c <= 'Z')
                  c = 'A' + mod(c - 'A' + decal, 26);
            else if ('0' <= c && c <= '9')
                  c = '0' + mod(c - '9' + decal, 10);
            chars[i] = (char)c;
      }

      return new CryptedValue(new string(chars), decal);
      }

      #endregion Caesar

      #region PlayFair
      /*
      'Prepare' removes all characters that are not letters i.e. all numbers, punctuation,
      spaces etc. are removed (uppercase is also converted to lowercase).

      If the second letter of a pair is the same as the first letter, an 'x' is inserted.

      Also, if the length of the string is odd, an 'x' is appended to make it an even length
      as Playfair can only encrypt even length strings.

      If you want numbers, punctuation etc. you must spell it out e.g.
      'stop' for period, 'one', 'two' etc.
      */
      private static string PlayFair_Prepare(string originalText)
      {
      int length = originalText.Length;
      originalText = originalText.ToLower();
      StringBuilder sb = new StringBuilder();

      for (int i = 0; i < length; i++)
      {
            char c = originalText[i];
            if (c >= 97 && c <= 122)
            {
                  // If the second letter of a pair is the same as the first, insert an 'x'
                  if (sb.Length % 2 == 1 && sb[sb.Length - 1] == c)
                  {
                  sb.Append('x');
                  }
                  sb.Append(c);
            }
      }

      // If the string is an odd length, append an 'x'
      if (sb.Length % 2 == 1)
      {
            sb.Append('x');
      }

      return sb.ToString();
      }

      private static string PlayFair_PrepareKey(string key)
      {
      string skipLetter = "w";
      key = key.Replace(" ", string.Empty);

      if (!Config.AppCulture.IsNull())
            if (!Config.AppCulture.Name.ToLower().Contains("fr"))
                  skipLetter = "j";

      key = key.Replace(skipLetter, string.Empty);

      string finalK = key.Substring(0, 1);

      for (int i = 1; i < key.Length; i++)
      {
            string l = key.Substring(i, 1);
            if (!finalK.Contains(l) && !l.Equals(skipLetter))
                  finalK += l;
      }

      for (int i = 0; i < Languages.Alphabets.Letters_Lower.Length; i++)
      {
            string l = Languages.Alphabets.Letters_Lower.Substring(i, 1);
            if (!finalK.Contains(l) && !l.Equals(skipLetter))
                  finalK += l;
      }

      return finalK;
      }

      private static string PlayFair_PreparePlainText(string plainText, string key)
      {
      if (plainText.Length.Parité() == System.Common.Math.EPairImpair.Impair)
            return plainText + key.Substring(0, 1);

      return plainText;
      }

      /*
      'Encipher' uses the Playfair cipher to encipher some text.
      The key is a string containing all 26 letters in the alphabet, except one'.
      */
      private static string PlayFair_Encrypt(string plainText, string key)
      {
      key = PlayFair_PrepareKey(key.ToLower());
      plainText = PlayFair_PreparePlainText(plainText.ToLower(), key);

      int length = plainText.Length;
      char a, b;
      int a_ind, b_ind, a_row, b_row, a_col, b_col;
      StringBuilder sb = new StringBuilder();

      for (int i = 0; i < length; i += 2)
      {
            a = plainText[i];
            b = plainText[i + 1];

            a_ind = key.IndexOf(a);
            b_ind = key.IndexOf(b);
            a_row = a_ind / 5;
            b_row = b_ind / 5;
            a_col = a_ind % 5;
            b_col = b_ind % 5;

            if (a_row == b_row)
            {
                  if (a_col == 4)
                  {
                  sb.Append(key[a_ind - 4]);
                  sb.Append(key[b_ind + 1]);
                  }
                  else if (b_col == 4)
                  {
                  sb.Append(key[a_ind + 1]);
                  sb.Append(key[b_ind - 4]);
                  }
                  else
                  {
                  sb.Append(key[a_ind + 1]);
                  sb.Append(key[b_ind + 1]);
                  }
            }
            else if (a_col == b_col)
            {
                  if (a_row == 4)
                  {
                  sb.Append(key[a_ind - 20]);
                  sb.Append(key[b_ind + 5]);
                  }
                  else if (b_row == 4)
                  {
                  sb.Append(key[a_ind + 5]);
                  sb.Append(key[b_ind - 20]);
                  }
                  else
                  {
                  sb.Append(key[a_ind + 5]);
                  sb.Append(key[b_ind + 5]);
                  }
            }
            else
            {
                  sb.Append(key[5 * a_row + b_col]);
                  sb.Append(key[5 * b_row + a_col]);
            }
      }
      return sb.ToString();
      }

      /*
      'Decipher' uses the Playfair cipher to decipher some text.
      The key is a string containing all 26 letters of the alphabet, except one.
      */
      private static string PlayFair_Decrypt(string cipherText, string key)
      {
      key = PlayFair_PrepareKey(key.ToLower());
      cipherText = cipherText.ToLower();

      int length = cipherText.Length;
      char a, b;
      int a_ind, b_ind, a_row, b_row, a_col, b_col;
      StringBuilder sb = new StringBuilder();

      for (int i = 0; i < length; i += 2)
      {
            a = cipherText[i];
            b = cipherText[i + 1];

            a_ind = key.IndexOf(a);
            b_ind = key.IndexOf(b);
            a_row = a_ind / 5;
            b_row = b_ind / 5;
            a_col = a_ind % 5;
            b_col = b_ind % 5;

            if (a_row == b_row)
            {
                  if (a_col == 0)
                  {
                  sb.Append(key[a_ind + 4]);
                  sb.Append(key[b_ind - 1]);
                  }
                  else if (b_col == 0)
                  {
                  sb.Append(key[a_ind - 1]);
                  sb.Append(key[b_ind + 4]);
                  }
                  else
                  {
                  sb.Append(key[a_ind - 1]);
                  sb.Append(key[b_ind - 1]);
                  }
            }
            else if (a_col == b_col)
            {
                  if (a_row == 0)
                  {
                  sb.Append(key[a_ind + 20]);
                  sb.Append(key[b_ind - 5]);
                  }
                  else if (b_row == 0)
                  {
                  sb.Append(key[a_ind - 5]);
                  sb.Append(key[b_ind + 20]);
                  }
                  else
                  {
                  sb.Append(key[a_ind - 5]);
                  sb.Append(key[b_ind - 5]);
                  }
            }
            else
            {
                  sb.Append(key[5 * a_row + b_col]);
                  sb.Append(key[5 * b_row + a_col]);
            }
      }
      return sb.ToString();
      }
      #endregion

      #endregion Cryptages
}