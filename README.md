# PostfixAdmin\PasswordHashing

Standalone PHP library for creating various password hashes

Note, this library is still quite new (2021/07/02). It's quite likely to have major refactoring / renaming as it's reviewed.


## Example usage

```PHP
$tool = new \PostfixAdmin\PasswordHashing\Crypt();

// should output something to indicate what your system supports (may be dependent on PHP variant, PHP modules etc)
var_dump($tool->supportedMechanisms()); // e.g. MD5, SHA1, ARGON2I. 

var_dump($tool->hash(Crypt::CLEARTEXT, 'fishbeans'));

var_dump($tool->hash(Crypt::MD5, 'fishbeans'));

var_dump($tool->verify(Crypt::MD5, 'fishbeans', 'some-hash'));

var_dump($tool->verify(Crypt::ARGON2I, 'fishbeans', 'some-hash'));

```


## ChangeLog

2021/07/03 - Initial release / copying of code from https://github.com/postfixadmin/postfixadmin ...
