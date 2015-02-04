<?php
require_once __DIR__ . '/classes/users.php';
require_once __DIR__ . '/classes/log.php';

$hash = User::GetPasswordHash('Abracadabra');
$check_hash = crypt('Abracadabra', $hash);
$equal = ($hash === $check_hash);


//update();
//createNew();
$a = 0;

function update() {
  try {
    $users = new Users(Database::GetInstance());
    $user = $users->GetByUserID(1);
    $user->Login = 'JohnDoe';
    $newRecord = $users->Update($user);
    $a = 0;
  }
  catch (AppException $e) {
    $a = 0;
  }
  catch (Exception $e) {
    $b = 0;
  }
}

function createNew() {
  try {
    $user = new User();
    $user->Login = 'johndoe';
    $user->PasswordHash = '768768';
    $user->FirstName = 'John';
    $user->LastName = 'Doe';
    $user->EMail = 'johndoe@nowhere.com';
    $user->Phone = '+75674532345';
    $user->Birthday = '1985-06-23';

    $users = new Users(Database::GetInstance());
    $users->Create($user);
  }
  catch (AppException $e) {
    $a = 0;
  }
  catch (Exception $e) {
    $b = 0;
  }
}


//try {
//
//  $users = new Users(Database::GetInstance());
//
//
//  $user = $users->GetByUserID(8);
//
//  $newUser = $users->Update($user);
//  $a = 0;
//}
//catch(AppException $e) {
//  $issueID = uniqid();
//  Log::Write($e, $issueID);
//  // echo: $e->getMessage(), $issueID
//}
//catch(Exception $e) {
//  $issueID = uniqid();
//  Log::Write($e, uniqid());
//  // echo: Strings:SERVER_ERROR, $issueID
//}
