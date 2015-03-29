<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('sign in');
$I->amOnPage('/profile/new');
$I->fillField('Login', 'jdoe');
$I->fillField('Password', '$1mpleP@$$w0rd');
$I->fillField('Confirm password', '$1mpleP@$$w0rd');
$I->fillField('First name', 'Jane');
$I->fillField('Last name', 'Doe');
$I->click('OK');
$I->see('The new account has been successfully created');
$I->click('OK');
$I->fillField('User name', 'jdoe');
$I->fillField('Password', '$1mpleP@$$w0rd');
$I->click('Enter');
$I->amOnPage('/profile');
$I->see('Profile');
$I->click('Edit');
//$I->click('#dtp_icon');
//sleep(4);