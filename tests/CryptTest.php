<?php

namespace PostfixAdmin\Tests;

use PostfixAdmin\PasswordHashing\Crypt;

class CryptTest extends \PHPUnit\Framework\TestCase
{
    public function testMd5Crypt()
    {
        $h = new Crypt('MD5-CRYPT');
        $hash = $h->cryptMd5('test');
        $check = $h->crypt('test', $hash);

        $this->assertEquals($check, $hash);

        $this->assertNotEmpty($hash);
        $this->assertNotEquals('test', $hash);
        $this->assertRegExp('/^{MD5-CRYPT}\$1\$/', $hash);
        $this->assertEquals($hash, $h->crypt('test', $hash));
    }

    public function testMd5()
    {
        $h = new Crypt('PLAIN-MD5');

        $hash = $h->hashMd5('test');
        $expected = '{PLAIN-MD5}' . md5('test');
        $this->assertEquals($hash, $expected);

        $h = new Crypt('MD5');
        $hash = $h->hashMd5('test', 'MD5');
        $hash2 = $h->crypt('test');
        $this->assertEquals($hash, $hash2);

        $expected = md5('test');
        $this->assertEquals($hash, $expected);
    }


    public function testEverything()
    {
        global $CONF;

        $algo_list = [
            'SHA1',
            'SHA1.B64',

            'BLF-CRYPT',
            'BLF-CRYPT.B64',

            'SHA512-CRYPT',
            'SHA512-CRYPT.B64',

            'SHA512',
            'SHA512.B64',

            // 'DES-CRYPT',
            'CRYPT',
            'MD5-CRYPT',
            'PLAIN-MD5',
            'PLAIN',
            'CLEAR',
            'CLEARTEXT',
            'ARGON2I',
            "ARGON2ID",
            //'MD5', // seems to be identical to MD5-CRYPT and clashes with {MD5} form courier. Avoid?
            'SHA256',
            'SHA256-CRYPT',
            'SHA256-CRYPT.B64',
        ];

        // should all be from 'test123', generated via dovecot.
        $example_json = <<<'EOF'
{
    "SHA1": "{SHA1}cojt0Pw\/\/L6ToM8G41aOKFIWh7w=",
    "SHA1.B64": "{SHA1.B64}cojt0Pw\/\/L6ToM8G41aOKFIWh7w=",
    "BLF-CRYPT": "{BLF-CRYPT}$2y$05$cEEZv2h\/NtLXII.emi2TP.rMZyB7VRSkyToXWBqqz6cXDoyay166q",
    "BLF-CRYPT.B64": "{BLF-CRYPT.B64}JDJ5JDA1JEhlR0lBeGFHR2tNUGxjRWpyeFc0eU9oRjZZZ1NuTWVOTXFxNWp4bmFwVjUwdGU3c2x2L1VT",
    "SHA512-CRYPT": "{SHA512-CRYPT}$6$MViNQUSbWyXWL9wZ$63VsBU2a\/ZFb9f\/dK4EmaXABE9jAcNltR7y6a2tXLKoV5F5jMezno.2KpmtD3U0FDjfa7A.pkCluVMlZJ.F64.",
    "SHA512-CRYPT.B64": "{SHA512-CRYPT.B64}JDYkR2JwY3NiZXNMWk9DdERXbiRYdXlhdEZTdy9oa3lyUFE0d24wenpGQTZrSlpTUE9QVWdPcjVRUC40bTRMTjEzdy81aWMvWTdDZllRMWVqSWlhNkd3Q2Z0ZnNjZEFpam9OWjl3OU5tLw==",
    "SHA512": "{SHA512}2u9JU7l4M2XK1mFSI3IFBsxGxRZ80Wq1APpZeqCP+WTrJPsZaH8012Zfd4\/LbFNY\/ApbgeFmLPkPc6JnHFP5kQ==",
    "SHA512.B64": "{SHA512.B64}2u9JU7l4M2XK1mFSI3IFBsxGxRZ80Wq1APpZeqCP+WTrJPsZaH8012Zfd4\/LbFNY\/ApbgeFmLPkPc6JnHFP5kQ==",
    "CRYPT": "{CRYPT}$2y$05$ORqzr0AagWr25v3ixHD5QuMXympIoNTbipEFZz6aAmovGNoij2vDO",
    "MD5-CRYPT": "{MD5-CRYPT}$1$AIjpWveQ$2s3eEAbZiqkJhMYUIVR240",
    "SYSTEM": "abRcsZmlrrKFA",
    "PLAIN-MD5": "{PLAIN-MD5}cc03e747a6afbbcbf8be7668acfebee5",
    "SSHA": "{SSHA}ZkqrSEAhvd0FTHaK1IxAQCRa5LWbxGQY",
    "PLAIN": "{PLAIN}test123",
    "CLEAR": "{CLEAR}test123",
    "CLEARTEXT": "{CLEARTEXT}test123",
    "ARGON2I": "{ARGON2I}$argon2i$v=19$m=32768,t=4,p=1$xoOcAGa27k0Sr6ZPbA9ODw$wl\/KAZVmJooD\/35IFG5oGwyQiAREXrLss5BPS1PDKfA",
    "ARGON2ID": "{ARGON2ID}$argon2id$v=19$m=65536,t=3,p=1$eaXP376O9/VxleLw9OQIxg$jOoDyECeRRV4eta3eSN/j0RdBgqaA1VBGAA/pbviI20",
    "ARGON2ID.B64" : "{ARGON2ID.B64}JGFyZ29uMmlkJHY9MTkkbT02NTUzNix0PTMscD0xJEljdG9DWko1T04zWlYzM3I0TVMrNEEkMUVtNTJRWkdsRlJzNnBsRXpwVmtMeVd4dVNPRUZ2dUZnaVNhTmNlb08rOA==",
    "SHA256": "{SHA256}7NcYcNGWMxapfjrDQIyYNa2M8PPBvHA1J8MCZVNPda4=",
    "SHA256-CRYPT": "{SHA256-CRYPT}$5$CFly6wzfn2az3U8j$EhfQPTdjpMGAisfCjCKektLke5GGEmtdLVaCZSmsKw2",
    "SHA256-CRYPT.B64": "{SHA256-CRYPT.B64}JDUkUTZZS1ZzZS5sSVJoLndodCR6TWNOUVFVVkhtTmM1ME1SQk9TR3BEeGpRY2M1TzJTQ1lkbWhPN1YxeHlD"
}
EOF;

        $algo_example = json_decode($example_json, true);

        foreach ($algo_example as $algorithm => $example_hash) {
            $CONF['encrypt'] = $algorithm;

            $h = new Crypt($algorithm);

            $pfa_new_hash = $h->crypt('test123');

            $pacrypt_check = $h->crypt('test123', $example_hash);
            $pacrypt_sanity = $h->crypt('zzzzzzz', $example_hash);

            $this->assertNotEquals($pacrypt_sanity, $example_hash, "Should not match, zzzz password. $algorithm / $pacrypt_sanity");

            $this->assertEquals($pacrypt_check, $example_hash, "Should match, algorithm: $algorithm generated:{$pacrypt_check} vs example:{$example_hash}");

            $new_new = $h->crypt('test123', $pfa_new_hash);

            $this->assertEquals($pfa_new_hash, $new_new, "Trying: $algorithm => gave: $new_new with $pfa_new_hash ... ");
        }
    }

    public function testWeSupportWhatWeSayWeDo()
    {
        foreach (Crypt::SUPPORTED as $algorithm) {
            $c = new Crypt($algorithm);
            $hash1 = $c->crypt('test123');

            $this->assertEquals($hash1, $c->crypt('test123', $hash1));
            $this->assertNotEquals($hash1, $c->crypt('9999test9999', $hash1));
        }
    }
}
