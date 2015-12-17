<?php
/**
* This is a very VERY basic password generator PHP class by Ákos Nikházy
*
* I made this because I've seen lot of user registration tutorials and nobody bothers to hash passwords... 
* not to speak about salting them. It is very concerning. Here I lay out some basic ideas how to store passwords.
*
* Nobody, not even the site's or database's admin should know anyone's passwords.
* Never store passwords as plain text. Never encrypt passwords. Encryption is for cases when you want to retrieve
* data. You do not want to retrieve passwords. If the user forgets it: generate a new one.
*
* You should store hashed password. Never plain text. But not simply hash it. Put a salt in the hash. Every 
* password needs its own unique salt. So the same passwords hasn't got the same hashes. This kind of security is 
* the most basic you can provide and it is strong enough so when the database is stolen at least the passwords 
* can't be cracked easily.
*
* Why the salt?
*
* As I mentioned before with salt there will be different hashes for the same password. If you simply hash a password
* without salt when the database is stolen they can guess who uses the same passwords. Same goes if you use the 
* same salt for every password. Also without salt it is easy to find hash-text pairs in databases that already have
* million hashes listed. So you need the salt. An unique one for every password non less.
*
* When user logs in, you check the password's salt hashed version and compare it to the typed in password salted version 
* with the same salt value. So if they the same hash: logged in.
*
* When an user decides to change password you generate a new salt too on the fly.
*
* Use other hash algorithm than sha1 if you want. The strong part of this protection is the salt itslef anyway.
*/

class password
{
	public function generateSalt($key = '')
	{/**
	  * Generate a salt. In a good system every salt is unique. When user creates new password it is good to generate new salt
	  * Using a random number and the time() there is almost no chance you get the same salt for two passwords and it is hard 
	  * to predict
	  * param: $key: if you want a system level + value for harder guess work. More secure. Overdone.
	  * 
	  * return: string(32) SALT
	  */
		return sha1(rand() . $key . time());
	}
	
	public function hashPassword($pass,$salt = false)
	{/**
	  * Return password and salt. In a good system every salt is unique. When user creates new password it is good to generate new salt
	  * 
	  * param: $pass: The password that the user provided
	  * param: $salt: The salt we generated before with generateSalt() or any other way as you please. If false it generated here.
	  * 
	  * return: array(2) { ["saltedPasswordHash"]=> string(40) SALTED PASSWORD HASH ["salt"]=> string(*) SALT }
	  */
		if(!$salt)
			$salt = $this->generateSalt();
			
		return array('saltedPasswordHash' => $this->saltPassword($pass,$salt),'salt'=>$salt);
	}
	
	private function saltPassword($pass,$salt)
	{/**
	  * Generate a salted password. In a good system every salt is unique. When user creates new password it is good to generate new salt
	  * 
	  * param: $pass: The password that the user provided
	  * param: $salt: The salt	  
	  * return: string(40) SALTED PASSWORD HASH
	  */
	  
		// this could be any mumbojumbo. Change as you please, but always put in $salt and $pass in some combination
		// WARNING: if you have an already working system with registered user never change this. Any change in this
		// after the first usages makes the passwords unusable as you have to salt the typed in passwords too so you 
		// can compare them to the stored hashed passwords.
		//
		// examples:
		// return sha1($pass.md5($salt));
		// return sha1(md5($salt).$pass.$salt.'lol i am so random');
		// return sha1(strlen($pass).$salt);
		return sha1($pass.md5($salt).$pass);
	}
	
}
?>
